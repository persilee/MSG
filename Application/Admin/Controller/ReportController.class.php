<?php
/**
 * Created by Sublime.
 * User: lsy
 * Date: 2017/6/20
 * Time: 16:43
 * 功能包含：报表管理
 * 修改历史：
 *       日期           修改人             修改功能
 *     xxxx/xx/xx      ：）              这是一个Report模板
 */

namespace Admin\Controller;

use Common\Common\ReportTools;
use Common\Common\EmpTools;
use Common\Common\ChromePhp;
use Common\Common\MailTools;

class ReportController extends AdminController
{

    /*================================================================================================*/
    /*  报表管理
    /*      功能清单：
    /*          01-reportList        ： 报表列表
    /*          02-reportInquery     ： 报表查询
    /*          02-reportPause       ： 报表暂停
    /*          03-reportDelete      ： 报表删除
    /*          03-reportDownload    ： 报表下载
    /*================================================================================================*/
    // 报表列表
    public function reportList()
    {
        $name = I('name');
        $map = array('name'=>array('LIKE','%'.$name.'%'));
        $list = $this->lists('report',$map,null);
        //转换启用和停用状态为说明
        $statusArray = array(
            'status' => L('COMMON_STATUS_TEXT'),
        );
        status_to_string($list, $statusArray);
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->assign('name', $name);
        $this->assign('list', $list);
        $this->display('reportList');
    }
    //报表查询
    public function reportInquery()
    {
      $seq = I('seq', 0, 'intval');
      if (empty($seq)) {
          $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
      }
      $Report = M('Report');
      $name = $Report->where("seq = $seq")->getField('name');
      $Report_list = M('report_list');
      $list = $Report_list->where("report_id = $seq")->select();
      $this->assign('name', $name);
      $this->assign('list', $list);
      $this->display('reportInquery');
    }

    //报表暂停
    public function reportPause(){
      $seq = I('seq', 0, 'intval');
      if (empty($seq)) {
          $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
      }
      $Report = M('Report');
      $Autotask = M('Autotask');

      $result = $Report->where("seq = $seq")->select();
      if ($result[0]['status'] == 1) {
        $result[0]['status'] = 0;
      }else{
        $result[0]['status'] = 1;
      }
      $code = $result[0]['code'];
      $map = array(
        'code'     =>$code,
      );
      $ReportData['status'] = $result[0]['status'];
      $AutotaskData['switch'] = $result[0]['status'];
      if (false === $Report->where("seq = $seq")->save($ReportData)) {
          $this->error($Report->getError());
      } else {
        if (false === $Autotask->where($map)->save($AutotaskData)) {
          $this->error($Autotask->getError());
        }else{
          $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
      }
    }
    // 报表删除
    public function reportDelete()
    {
      $seq = I('seq');
      $name = I('name');
      $Report_list = M('report_list');
      if (false === $Report_list->where("seq = $seq")->delete()) {
        $this->error($Report_list->getError());
      }else{
        $reportTools = new ReportTools();
        if ($reportTools->reportDelete($name)) {
          $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }else{
          $this->error(L('SYSTEM_MESSAGE_ERROR'));
        }
      }
    }
    // 报表下载
    public function reportDownload()
    {
      $name = I('name');
      $reportTools = new ReportTools();
      $reportTools->reportDownload($name);
      exit;
    }

}
