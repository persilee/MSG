<?php
/**
 * Created by Sublime.
 * User: Hipr
 * Date: 2015/10/6
 * Time: 16:43
 * 功能包含：个人账户信息管理
 * 修改历史：
 *       日期           修改人             修改功能
 *     xxxx/xx/xx      习大大            这是一个示例模板
 */

namespace Admin\Controller;
use Common\Common\EmpTools;
use Common\Common\MailTools;
use Common\Common\MailTplTools;

class MailController extends AdminController{

    /*================================================================================================*/
    /*  邮件信息管理
    /*      功能清单：
    /*          01-mailTplList       ： 邮件模板列表
    /*          02-mailTplAdd        ： 邮件模板添加
    /*          03-mailTplUpdate     ： 邮件模板修改
    /*          04-mailTplDelete     ： 邮件模板删除
    /*          05-mailTester        ： 邮件测试
    /*================================================================================================*/
    // 邮件模板列表
    public function mailTplList(){
    	$name = I('name');
    	$map = array('name'=>array('LIKE','%'.$name.'%'));
    	$list = $this->lists ( 'Mailtpl', $map);
    	// 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('name',$name);
        $this->assign('list',$list);
        $this->display('mailTplList');
    }

    // 邮件模板添加
    public function mailTplAdd(){
    	if(IS_POST){
            $data = array(
                'name' => I('name'),
                'type' => I('type'),
                // 'mail_type' => I('mail_type'),
                'remark' => I('remark'),
                'title_en' => I('title_en'),
                'title_zh_s' => I('title_zh_s'),
                'title_zh_t' => I('title_zh_t'),
                'en_content' => I('en_content'),
                'zh_s_content' => I('zh_s_content'),
                'zh_t_content' => I('zh_t_content'),
                'temp_file' => I('temp_file'),
                'cc_user_group' => I('cc_user_group',0,'intval'),
            );
            $mailTplTools = new MailTplTools();
            if($mailTplTools->add($data)){
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }else{
                $this->error($mailTplTools->getError());
            }
        }else{
            $Usergroup = M('Usergroup');
            $usergroupArr = $Usergroup->order('sort')->field('id,pid,name')->select();
            $usergroupArr = levelTree($usergroupArr);
            $this->assign('usergroupArr',$usergroupArr);
            $this->display('mailTplAdd');
        }
    }

    // 邮件模板修改
    public function mailTplUpdate(){
        $mailTplTools = new MailTplTools();
        if(IS_POST){
            $data = array(
                'id' => I('id',0,'intval'),
                'name' => I('name'),
                'type' => I('type'),
                // 'mail_type' => I('mail_type'),
                'remark' => I('remark'),
                'title_en' => I('title_en'),
                'title_zh_s' => I('title_zh_s'),
                'title_zh_t' => I('title_zh_t'),
                'en_content' => I('en_content'),
                'zh_s_content' => I('zh_s_content'),
                'zh_t_content' => I('zh_t_content'),
                'temp_file' => I('temp_file'),
                'cc_user_group' => I('cc_user_group',0,'intval'),
            );
            if($mailTplTools->update($data)){
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }else{
                $this->error($mailTplTools->getError());
            }
        }else{
          //载入配置项
            $id = I('id',0,'intval');
            $result = $mailTplTools->getRecord($id);
            if(false === $result){
                $this->error($mailTplTools->getError());
            }
            $Usergroup = M('Usergroup');
            $usergroupArr = $Usergroup->order('sort')->field('id,pid,name')->select();
            $usergroupArr = levelTree($usergroupArr);
            $this->assign('usergroupArr',$usergroupArr);
            $this->assign('result',$result);
            $this->display('mailTplUpdate');
        }
    }

    // 邮件模板删除
    public function mailTplDelete(){
    	$id = I('id',0,'intval');
        $mailTplTools = new MailTplTools();
        if(false === $mailTplTools->delete($id)){
            $this->error($mailTplTools->getError());
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
    }

    // 邮件测试
    public function mailTester(){
    	if(IS_POST){
	    	$receiver = I('receiver');
            $mailRoute = I('route');
            if(empty($receiver) || empty($mailRoute) ){
                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
            }
            //取得测试邮件模板
            $Mailtpl = M('Mailtpl');
            $mailtplId = $Mailtpl->where(array('type'=>'TEST'))->getField('id');
	    	    $mailTools = new MailTools();
            if('ESB' == $mailRoute){
                $messgeSendRoute = MailTools::MAIL_SEND_ROUTE_ESB;
            }else{
                $messgeSendRoute = MailTools::MAIL_SEND_ROUTE_TERMINAL;
            }
	    	if(false === $mailTools->prepareMail($mailtplId,'test')){
	    		$this->error($mailTools->getError());
	    	}elseif(false === $mailTools->sendMail($receiver,$messgeSendRoute)){
                $this->error($mailTools->getError());
            }else{
	    		$this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
	    	}
	    }else{
	    	// 记录当前列表页的cookie
            Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
	    	$this->display('mailTester');
	    }
    }
    /*================================================================================================*/
    /*  邮件LOG管理
    /*      功能清单：
    /*          01-maillogList       ： 邮件记录列表
    /*          02-maillogInquery    ： 邮件记录查询
    /*          03-maillogFileInq    ： 邮件文件查看
    /*================================================================================================*/
    //邮件记录列表
    public function maillogList(){
        $date = I('date');
        $outside_flag = I('outside_flag',0,intval);
        if(empty($date)){
            $date = date('Y-m-d');
        }
        //组合查询条件
        $where = array(
            'date' => date_to_int('-',$date),
            'outside_flag' => $outside_flag,
        );
        //if(0 != $seq){
        //    $where['seq'] = array('EGT',$seq);
        //}
        //取得当前操作用户同组及下属组所有的用户
        $empTools = new EmpTools();
        $empArr = $empTools->getGroupUser(EmpTools::INCLUDE_SELF);
        $Client = M('Client');
        $clientIdArr = $Client->where(array('create_emp'=>array('IN',$empArr)))->getField('ci_no',true);
        $clientIdArr[] = 0;
        if($clientIdArr) {
            $where['ci_no'] = array('IN', $clientIdArr);
            $list = $this->lists('Maillog', $where, '`date` DESC', '', true, true);
            //转换成功及失败状态为说明
            $statusArray = array(
                'status' => L('COMMON_SUCCESS_TEXT'),
            );
            status_to_string($list, $statusArray);
            //转换内外部标志为说明
            $outsideArray = array(
                'outside_flag' => L('MAIL_OUTSIDE_FLAG'),
            );
            status_to_string($list, $outsideArray);
        }else{
            $list = null;
        }
        $this->assign('list',$list);
        $this->assign('date',$date);
        $this->assign('outside_flag',$outside_flag);
        $this->display('maillogList');
    }

    //邮件记录查询
    public function maillogInquery(){
        $date = I('date');
        $seq = I('seq',0,'intval');
        if(empty($date) || empty($seq)){
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Maillog = D('Maillog');
        if(!is_array($result = $Maillog->relation(true)->where(array('date'=>$date,'seq'=>$seq))->find())){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        $this->assign('result',$result);
        $this->display('maillogInquery');
    }

    //邮件文件查看
    public function maillogFileInq(){
        $date = I('date');
        $seq = I('seq',0,'intval');
        if(empty($date) || empty($seq)){
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Maillog = D('Maillog');
        if(!is_array($result = $Maillog->where(array('date'=>$date,'seq'=>$seq))->find())){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        $filename = "./Data/mailFileBackup/".day_format($date)."_".$seq.".html";
        $conf = file_get_contents($filename);
        if(false === $conf){
            $conf = L('SYSTEM_ERROR_FILE_NOT_EXIST');
        }
        $this->assign('content',$conf);
        $this->display('maillogFileInq');
    }
}
