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

class AccountController extends AdminController{

    /*================================================================================================*/
    /*  个人账户信息管理
    /*      功能清单：
    /*          01-account         ： 个人账户信息查询
    /*          02-update          ： 个人账户信息更新
    /*          03-accountmessage  ： 个人消息队列浏览
    /*          04-msgRead         ： 读取账户消息
    /*          05-msgSend         ： 发送账户消息
    /*          06-msgNew          ： 发送新消息
    /*          07-changePwd       ： 修改账户密码
    /*          08-imgUpload       ： 头像上传
    /*================================================================================================*/
    //个人账户列表
    public function account(){
        $Emp = D('Emp');
        $result = $Emp->find(UID);
        //取得部门缓存列表
        $Dept = M('Dept');
        $deptName = $Dept->where(array('id'=>$result['dept_id']))->getField('name');
        $this->assign('deptName',$deptName);
        $Usergroup_user = M('Usergroup_user');
        $usergroupArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
        $groupNameArr = array();
        if(empty($usergroupArr)){
            $groupNameArr[] = array(0);
        }else{
            $Usergroup = M('Usergroup');
            $groupNameArr = $Usergroup->where(array('id'=>array('in',$usergroupArr)))->getField('name',true);
        }
        $groupNameStr = implode(',',$groupNameArr);
        $this->assign('groupNameStr',$groupNameStr);
        $this->assign('result',$result);
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->display('account');
    }

    //更新简历信息、用于个人更新，从SESSION中取得ID号，为了权限控制，所以分开
    public function update(){
        $id = UID;
        $nickname = I('nickname');
        //$birthday = I('birthday');
        $mobile = I('mobile');
        //$sex = I('sex');
        // $real_email = I('real_email');
        // $school = I('school');
        // $education = I('education');
        // $graduation_date = I('graduation_date');
        // $bron = I('bron');
        // $major = I('major');
        // $idcard = I('idcard');
        // $join_work_date = I('join_work_date');
        // $residence = I('residence');
        // $specialty = I('specialty');
        // $others = I('others');

        // 输入检查
        //if(("" != $birthday) && !date_check("-",$birthday)){
        //    $this->error(L('TEMP_ACCOUNT_BIRTHDAY').','.L('SYSTEM_ERROR_FORMAT'));
        //}
        // if(("" != $graduation_date) && !date_check("-",$graduation_date)){
        //     $this->error("毕业日期格式有误！");
        // }
        // if(("" != $join_work_date) && !date_check("-",$join_work_date)){
        //     $this->error("参加工作日期格式有误！");
        // }

        $Emp = D('Emp');

        $data = array(
            'id' => $id,
        //    'birthday' => date_to_int("-",$birthday),
            'nickname' => $nickname,
            'mobile' => $mobile,
        //    'sex' => $sex,
            // 'real_email' => $real_email,
            // 'school' => $school,
            // 'education' => $education,
            // 'graduation_date' => date_to_int("-",$graduation_date),
            // 'bron' => $bron,
            // 'major' => $major,
            // 'idcard' => $idcard,
            // 'join_work_date' => date_to_int("-",$join_work_date),
            // 'residence' => $residence,
            // 'specialty' => $specialty,
            // 'others' => $others,
        );
        // if("" != $nikename){
        //     $data['nikename'] = $nikename;
        // }
        if($Emp->create($data)){
            if($Emp->save()){
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
            }else{
                $this->error(L('SYSTEM_MESSAGE_NOT_CHANGE'));
            }
        }else{
            $this->error($Emp->getError());
        }
    }

    //浏览消息队列  == 个人账户
    public function accountmessage(){
        // $stdate = I('stdate');
        // $enddate = I('enddate');
        $status = I('status',0,'intval');
        $type = I('type',0,'intval');
        // if("" != $stdate && !date_check("-",$stdate)){
        //     $this->error("起始日期格式有误！");
        // }
        // if("" != $enddate && !date_check("-",$enddate)){
        //     $this->error("截止日期格式有误！");
        // }
        // $keywords = I('keywords');
        // $read = I('read');
        $map = array();
        // if(''!=$stdate && ''!=$enddate){
        //     $map['create_time'] = array('between',array(date_to_int('-',$stdate),date_to_int('-',$enddate,23,59,59)));
        // }elseif(''!=$stdate){
        //     $map['create_time'] = array('egt',date_to_int('-',$stdate));
        // }elseif(''!=$enddate){ 
        //     $map['create_time'] = array('elt',date_to_int('-',$enddate,23,59,59));
        // }
        $map['status'] = $status;
        if(0 == $type){
            $map['recv_emp'] = UID;
        }else{
            $map['send_emp'] = UID;
        }
        // if('' != $keywords){
        //     $map['title'] = array('like','%'.$keywords.'%');
        // }
        // 取得当前用户所在部门的所有父级部门
        // $parentDept = getParentTree(get_deptTree(),get_current_deptid());
        // $parentDeptArr = array();
        // foreach ($parentDept as $key => $value) {
        //     $parentDeptArr[] = $value['id'];
        // }
        // $parentDeptStr = implode(',', $parentDeptArr);
        // $map['dept_id'] = array('in',$parentDeptArr);
        // $condition = array(
        //     'mapping' => 'Messread',
        //     'where' => 'Messread.empid='.is_login(),
        // );
        // 分页查询
        // if("" == $read){
            //查询所有消息
        $list = $this->lists('Message', $map, '`create_time` DESC','',true,true);
        // }elseif('R' == $read){
        //     // 查询已读消息
        //     $list = $this->lists('MessageAcReadView', $map, '`create_time` DESC','',true,false,$condition );
        // }else{
        //     // 查询未读消息
        //     $Messread = M('Messread');
        //     $messageResult = $Messread->where(array('empid'=>is_login()))->field('messageid')->select();
        //     $messIdArray = array();
        //     foreach($messageResult as $key=>$val){
        //         $messIdArray[] = $val['messageid'];
        //     }
        //     if(!empty($messIdArray)){
        //         $map['id'] = array('not in',$messIdArray);
        //     }
        //     $list = $this->lists('Message', $map, '`create_time` DESC','',true,'Emp');
        // }
        //清除菜单项COOKIE
        // cookie('liactive',null);
        // cookie('ulactive',null);

        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        // $this->assign('stdate',$stdate);
        // $this->assign('enddate',$enddate);
        // $this->assign('keywords',$keywords);
        $this->assign('status',$status);
        $this->assign('type',$type);
        $this->assign('list',$list);
        $this->display('accountmessage');
    }

    // 取得个人未读消息标签
    // public function msgMenu(){
    //     $map = array(
    //         'recv_emp' => UID,
    //         'status' => 0,
    //     );
    //     $Message = D('message');
    //     $msgCount = $Message->where($map)->count();
    //     if($msgCount > 0){
    //         $msgResult = $Message->relation(true)->where($map)->limit(5)->select();
    //     }else{
    //         $msgResult = null;
    //     }
    //     $this->assign('msgCount',$msgCount);
    //     $this->assign('msgResult',$msgResult);
    //     $this->display('msgMenu');
    // }

    // //账户读取消息
    // public function msgRead(){
    //     $group_id = I('group_id',0,'intval');
    //     if(0 == $group_id){
    //         $this->error('请指定记录！');
    //     }
    //     $Message = D('Message');
    //     $Message->where(array('group_id'=>$group_id,'recv_emp'=>UID))->setField(array('status'=>1));
    //     $msgResult = $Message->relation(true)->where(array('group_id'=>$group_id))->limit(10)->select();
    //     $this->assign('msgResult',$msgResult);
    //     $this->assign('group_id',$group_id);
    //     $this->display('msgRead');
    // }

    //发送消息
    // public function msgSend(){
    //     $group_id = I('group_id',0,'intval');
    //     $recv_emp = I('recv_emp',0,'intval');
    //     $content = I('content');
    //     if(0 == $recv_emp){
    //         $this->error('请指定接收人！');
    //     }
    //     if("" == $content){
    //         $this->error('请输入消息内容！');
    //     }
    //     $Message = D('Message');
    //     if(0 == $group_id){
    //         $maxGruop = $Message->max('group_id');
    //         $group_id = $maxGruop + 1;
    //     }
    //     $data = array(
    //         'group_id' => $group_id,
    //         'send_emp' => UID,
    //         'recv_emp' => $recv_emp,
    //         'create_time' => NOW_TIME,
    //         'content' => $content,
    //     );
    //     if(!$Message->add($data)){
    //         $this->error($Message->getError());
    //     }
    //     $msgResult = $Message->relation(true)->where(array('group_id'=>$group_id))->limit(10)->select();
    //     $this->assign('msgResult',$msgResult);
    //     $this->assign('group_id',$group_id);
    //     $this->display('msgRead');
    // }

    //发送新消息
    // public function msgNew(){
    //     if(IS_POST){
    //         $recv_emp = I('recv_emp',0,'intval');
    //         $content = I('content');
    //         if(0 == $recv_emp){
    //             $this->error('请指定接收人！');
    //         }
    //         if("" == $content){
    //             $this->error('请输入消息内容！');
    //         }
    //         $Message = D('Message');
    //         $maxGruop = $Message->max('group_id');
    //         $group_id = $maxGruop + 1;
    //         $data = array(
    //             'group_id' => $group_id,
    //             'send_emp' => UID,
    //             'recv_emp' => $recv_emp,
    //             'create_time' => NOW_TIME,
    //             'content' => $content,
    //         );
    //         if(!$Message->add($data)){
    //             $this->error($Message->getError());
    //         }
    //         $msgResult = $Message->relation(true)->where(array('group_id'=>$group_id))->limit(10)->select();
    //         $this->assign('msgResult',$msgResult);
    //         $this->assign('group_id',$group_id);
    //         $this->display('msgRead');
    //     }else{
    //         $this->display('msgNew');
    //     }
    // }

    // 修改密码
    public function changePwd(){
        if(IS_POST){
            $pwd = I('pwd');
            $pwd1 = I('pwd1');
            $pwd2 = I('pwd2');
            if("" == $pwd){
                $this->error(L('ACCOUNT_ERROR_PLS_ENTRY_ORIG_PWD'));
            }
            if("" == $pwd1 || "" == $pwd2 ){
                $this->error(L('ACCOUNT_ERROR_PLS_ENTRY_NEW_PWD'));
            }
            if($pwd1 != $pwd2){
                $this->error(L('ACCOUNT_ERROR_PWD_NOT_MATCH'));
            }
            if($pwd == $pwd1){
                $this->error(L('ACCOUNT_ERROR_PWD_OLD_NEW_SAME'));
            }
            //密码格式检查
            if(false === pwdFormatCheck($pwd1)){
                $this->error(L('EMP_ERROR_PWD_FORMAT_ERR'));
            }
            $Emp = M('Emp');
            $data = array(
                'id' => UID,
                'password' => system_md5($pwd1),
                'pwd_change_date' => date_to_int('-',date('Y-m-d')),
            );
            if(system_md5($pwd) != $Emp->where(array('id'=>UID))->getField('password')){
                $this->error(L('ACCOUNT_ERROR_PWD_ORIG_ERROR'));
            }elseif(!$Emp->save($data)){
                $this->error(L('SYSTEM_MESSAGE_ERROR'));
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'),U('Account/account'));
            }
        }else{
            $this->display('changePwd');
        }
    }

    public function initPwd(){
        $flag = I('flag');
        if(!empty($flag)) {
            $pwd1 = I('pwd1');
            $pwd2 = I('pwd2');
            if ("" == $pwd1 || "" == $pwd2) {
                $this->error(L('ACCOUNT_ERROR_PLS_ENTRY_NEW_PWD'));
            }
            if ($pwd1 != $pwd2) {
                $this->error(L('ACCOUNT_ERROR_PWD_NOT_MATCH'));
            }
            if ('123' == $pwd1) {
                $this->error(L('ACCOUNT_ERROR_PWD_OLD_NEW_SAME'));
            }
            //密码格式检查
            if(false === pwdFormatCheck($pwd1)){
                $this->error(L('EMP_ERROR_PWD_FORMAT_ERR'));
            }
            $Emp = M('Emp');
            $data = array(
                'id' => UID,
                'password' => system_md5($pwd1),
                'pwd_change_date' => date_to_int('-',date('Y-m-d')),
            );
            $returnCode = $Emp->save($data);
            if ($returnCode) {
                session('pwd',system_md5($pwd1));
                session('pwd_change_date',date_to_int('-',date('Y-m-d')));
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), U('Admin/home'));
            } else {
                $this->error($Emp->getError());
            }
        }else{
            $this->display('initPwd');
        }
    }

    // 头像上传
    public function imgUpload(){
        $return  = array('status' => 1, 'info' => L('SYSTEM_MESSAGE_UPLOAD_SUCCESS'), 'data' => '');
        // 读取上传文件并写表
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     2000000 ;// 设置附件上传大小(Byte)
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/empImg/'; // 设置附件上传目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $upload->autoSub   =     false;// 上传文件 
        $upload->saveName  =     UID;// 文件名称 
        $upload->replace   =     true;  //覆盖先前文件
        $info   =   $upload->uploadOne($_FILES['temp_file']);
        if(!$info) {// 上传错误提示错误信息
            $return['status'] = 0;
            $return['info']   = $upload->getError();
        }else{// 上传成功 获取上传文件信息
            $filename = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/empImg/'.$info['savename'];
            $Emp = M('Emp');
            $returnCode = $Emp->where(array('id'=>UID))->setField(array('img_file'=>$info['savename']));
            if($returnCode === false){
                $return  = array('status' => 0, 'info' => L('SYSTEM_MESSAGE_UPLOAD_ERROR'), 'data' => '');
                unlink($filename);
            }else{
                $return['data'] = $info['savename'];
                session('img_url',__ROOT__.'/Uploads/empImg/'.$info['savename']);
            }
        }
        $this->ajaxReturn($return);
    }
}