<?php
/**
 * Created by Sublime.
 * User: per
 * Date: 2017/6/12
 * Time: 16:43
 * 功能包含：短信模板管理
 * 修改历史：
 *       日期           修改人             修改功能
 *     xxxx/xx/xx      ：）              这是一个sms模板
 */

namespace Admin\Controller;

use Common\Common\SmsTplTools;
use Common\Common\EmpTools;
use Common\Common\ChromePhp;

class SmsController extends AdminController
{

    /*================================================================================================*/
    /*  短信模板管理
    /*      功能清单：
    /*          01-smsTplList        ： 短信模板列表
    /*          02-smsTplAdd         ： 短信模板添加
    /*          03-smsTplUpdate      ： 短信模板修改
    /*          04-smsTplDelete      ： 短信模板删除
    /*          05-smsTester         ： 短信测试
    /*================================================================================================*/
    // 短信模板列表
    public function smsTplList()
    {
        $name = I('name');
        $map = array('name'=>array('LIKE','%'.$name.'%'));
        $list = $this->lists('Smstpl', $map);
      // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->assign('name', $name);
        $this->assign('list', $list);
        $this->display('smsTplList');
    }
    // 短信模板添加
    public function smsTplAdd()
    {
        if (IS_POST) {
            $data = array(
                'name' => I('name'),
                'remark' => I('remark'),
                'en_content' => I('en_content'),
                'zh_s_content' => I('zh_s_content'),
                'zh_t_content' => I('zh_t_content'),
                'temp_file' => I('temp_file'),
            );
            $smsTplTools = new SmsTplTools();
            if ($smsTplTools->add($data)) {
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($smsTplTools->getError());
            }
        } else {
            $this->display('smsTplAdd');
        }
    }

    //短信模板修改
    public function smsTplUpdate()
    {
        $smsTplTools = new SmsTplTools();
        if (IS_POST) {
            $data = array(
            'id' => I('id'),
            'name' => I('name'),
            'remark' => I('remark'),
            'en_content' => I('en_content'),
            'zh_s_content' => I('zh_s_content'),
            'zh_t_content' => I('zh_t_content'),
            'temp_file' => I('temp_file'),
          );
            if ($smsTplTools->update($data)) {
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($smsTplTools->getError());
            }
        } else {
            //载入配置项
          $id = I('id', 0, 'intval');
            $result = $smsTplTools->getRecord($id);
            if (false === $result) {
                $this->error($smsTplTools->getError());
            }
            $this->assign('result', $result);
            $this->display('smsTplUpdate');
        }
    }

    // 短信模板删除
    public function smsTplDelete()
    {
        $id = I('id', 0, 'intval');
        $smsTplTools = new SmsTplTools();
        if (false === $smsTplTools->delete($id)) {
            $this->error($smsTplTools->getError());
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
    }

    // 短信测试
    public function smsTester()
    {
        if (IS_POST) {
            $receiver = I('receiver');
            $smsRoute = I('route');
            if (empty($receiver) || empty($smsRoute)) {
                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
            }
            //取得测试邮件模板
            $Smstpl = M('Smstpl');
            $smstplId = $Smstpl->where(array('type'=>'TEST'))->getField('id');
            $smsTools = new SmsTools();
            if ('ESB' == $smsRoute) {
                $messgeSendRoute = SmsTools::SMS_SEND_ROUTE_ESB;
            } else {
                $messgeSendRoute = SmsTools::SMS_SEND_ROUTE_TERMINAL;
            }
            if (false === $smsTools->prepareMail($smstplId, 'test')) {
                $this->error($smsTools->getError());
            } elseif (false === $smsTools->sendMail($receiver, $messgeSendRoute)) {
                $this->error($smsTools->getError());
            } else {
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        } else {
            // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
            $this->display('smsTester');
        }
    }

    /*================================================================================================*/
    /*  邮件LOG管理
    /*      功能清单：
    /*          01-smslogList       ： 短信记录列表
    /*          02-smslogInquery    ： 短信记录查询
    /*================================================================================================*/
    //短信记录列表
    public function smslogList()
    {
        $date = I('date');
        if (empty($date)) {
            $date = date('Y-m-d');
        }

      //组合查询条件
      $where = array(
          'date' => date_to_int('-', $date),
      );
      //取得当前操作用户同组及下属组所有的用户
      $empTools = new EmpTools();
        $empArr = $empTools->getGroupUser(EmpTools::INCLUDE_SELF);
        $Client = M('Client');
        $clientIdArr = $Client->where(array('create_emp'=>array('IN',$empArr)))->getField('ci_no', true);
        $clientIdArr[] = 0;
        if ($clientIdArr) {
            $where['ci_no'] = array('IN', $clientIdArr);
            $list = $this->lists('Smslog', $where, '`date` DESC', '', true, true);
          //转换成功及失败状态为说明
          $statusArray = array(
              'status' => L('COMMON_SUCCESS_TEXT'),
          );
            status_to_string($list, $statusArray);
        } else {
            $list = null;
        }
        $this->assign('list', $list);
        $this->assign('date', $date);
        $this->display('smslogList');
    }

    //短信记录查询
    public function smslogInquery()
    {
        $date = I('date');
        $seq = I('seq', 0, 'intval');
        if (empty($date) || empty($seq)) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Smslog = D('Smslog');
        if (!is_array($result = $Smslog->relation(true)->where(array('date'=>$date,'seq'=>$seq))->find())) {
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        $this->assign('result', $result);
        $this->display('smslogInquery');
    }
}
