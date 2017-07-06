<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/8/7
 * Time: 16:43
 * 功能包含：员工信息管理、部门信息管理、职位信息管理、人员成本维护
 * 修改历史：
 *       日期           修改人             修改功能
 *     2015/12/15      林海宾            添加成员成本维护
 */

namespace Admin\Controller;

use Admin\Model\EmpRelationModel;
use Common\Common\AccessTools;
use Common\Common\EmpTools;
use Common\Common\LogTools;
use Common\Common\RoleTools;
use Common\Common\UserGroupTools;

class EmpController extends AdminController{

    /*================================================================================================*/
    /*  员工信息管理
    /*      功能清单：
    /*          1-empList     ： 员工列表浏览
    /*          2-empAdd      ： 员工信息添加
    /*          3-empDelete   ： 员工信息删除
    /*          4-empUpdate   ： 员工信息更新
    /*          5-empInquery  ： 员工信息查询
    /*          6-empHold     ： 员工冻结（不允许登录）
    /*          7-empRelease  ： 员工解冻（允许登录）
    /*          8-empPwdReset ： 密码重置
    /*================================================================================================*/
    //浏览人员信息
    public function empList()
    {
        $email = I('email');
        $name = I('name');
        $group_id = I('group_id', 0, 'intval');
        $map = array(
            'status' => 'A',
        );
        if ('' != $email) {
            $map['email'] = array('like', '%' . $email . '%');
        }
        if ('' != $name) {
            $map['name'] = array('like', '%' . $name . '%');
        }
        //====================用户组处理========================//
        //取得当前用户所在用户组,及其下级用户组
        $Usergroup = M('Usergroup');
        $allGroupIdArr = $Usergroup->field(array('id', 'pid', 'name'))->order('sort')->select();
        //取得当前用户的归属用户组
        $Usergroup_user = M('Usergroup_user');
        //$userGroupIdArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
        //取得当前用户归属用户的所有子组
        //$groupIdArr = array();
        //foreach ($userGroupIdArr as $value){
        //    $groupIdArr[] = $value;
        //    foreach (getChildTree($allGroupIdArr,$value) as $child){
        //        $groupIdArr[] = $child['id'];
        //    }
        //}
        //数组去重
        //$groupIdArr = array_unique($groupIdArr);
        //foreach ($allGroupIdArr as $key => $item) {
        //    if(in_array($item['id'],$groupIdArr)){
        //        $allGroupIdArr[$key]['have'] = '1';
        //    }
        //}
        $groupIdLevelTree = levelTree($allGroupIdArr);
        if (0 != $group_id) {
            //$groupIdStr = implode(',',$groupIdArr);
            //}else{
            //$groupIdStr = $group_id . "";
            if($allUserId = $Usergroup_user->where(array('usergroup_id' => $group_id))->getField('user_id', true)){
                $map['id'] = array('IN',$allUserId);
            }else{
                $map['id'] = 0;
            }
        }
        //=======================================================//
        // 改成分页查询
        //if($allUserId){
//        dump($map);die;
        //    $map['id'] = array('IN',$allUserId);
            $list = $this->lists ( 'Emp', $map, '`status` ASC' );
        //}else{
        //    $list = array();
        //}
        //转换LIST中的状态STATUS栏位为说明 
        $statusArray = array(
            'status'=>L('EMP_STATUS_TEXT'),
        );
        status_to_string($list,$statusArray);
        //转换LIST中的是否允许登录系统栏位为说明 
        $switchArray = array(
            'login_switch'=>L('TEMP_EMP_EMP_LOGIN_SWICH_TEXT'),
        );
        status_to_string($list,$switchArray);
        //转换LIST中的是否主管标志栏位转为说明
        $isDirectorArray = array(
            'is_director'=>L('COMMON_FLAG_TEXT'),
        );
        status_to_string($list,$isDirectorArray);
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('list',$list);
        $this->assign('email',$email);
        $this->assign('name',$name);
        $this->assign('group_id',$group_id);
        $this->assign('groupTree',$groupIdLevelTree);
        $this->display('empList');
    }

    //添加人员信息
    public function empAdd(){
        $Usergroup = M('Usergroup');
        $groupResult = $Usergroup->field(array('id','pid','name'))->order('sort')->select();
        if(IS_POST){
//            $email = I('email');
//            $name = I('name');
//            $sex = I('sex');
////            $birthday = I('birthday');
//            $mobile = I('mobile');
//            $dept_id = I('dept_id',0,'intval');
//            $is_director = I('is_director',0,'intval');
//            $remark = I('remark');
//            $password = system_md5('123');
//            $groupid = I('groupid',0,'intval');
//            // 输入检查
//            if("" == $name){
//                $this->error(L('TEMP_EMP_EMP_NAME').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            if("" == $email && "" == $mobile){
//                $this->error(L('EMP_ERROR_EMAIL_PHONE_INPUT'));
//            }
//            if(0 == $dept_id){
//                $this->error(L('TEMP_EMP_DEPT').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            if("" != $birthday && !date_check('-',$birthday)){
//                $this->error(L('TEMP_EMP_EMP_BIRTHDAY').L('SYSTEM_ERROR_DATE_FORMAT'));
//            }
//            // 检查部门号存不存在
//            $Dept = M('Dept');
//            if(!($Dept->find($dept_id))){
//                $this->error(L('EMP_ERROR_DEPT_NOT_EXIST'));
//            }
//            $Emp = D('Emp');
//            if('' != $email && $Emp->where(array('email'=>$email))->count() > 0){
//                $this->error(L('TEMP_EMP_EMP_EMAIL').L('SYSTEM_ERROR_RECORD_EXIST'));
//            }
//            if('' != $mobile && $Emp->where(array('mobile'=>$mobile))->count() > 0){
//                $this->error(L('TEMP_EMP_EMP_PHONE').L('SYSTEM_ERROR_RECORD_EXIST'));
//            }
            $data = array(
                'email' => I('email'),
                'name' => I('name'),
                'mobile' => I('mobile'),
                'dept_id' => I('dept_id',0,'intval'),
                'is_director' => I('is_director',0,'intval'),
                'remark' => I('remark'),
                'img_file' => 'noneImg.jpg',
                'status'   => 'A',
                'groupid' => I('groupid',0,'intval'),
                'password' => system_md5('123'),
            );
//            if("" != $email){
//                $data['email'] = $email;
//            }
//            if("" != $mobile){
//                $data['mobile'] = $mobile;
//            }
//            if("" != $birthday){
//                $data['birthday'] = date_to_int('-',$birthday);
//            }
//            $data['status'] = 'A';
            // 启动事务处理
//            $Emp->startTrans();
//            if ($Emp->create($data)) {
//                $empId = $Emp->add();
//                if(!$empId){
//                    $Emp->rollback();
//                    $this->error(L('SYSTEM_MESSAGE_ERROR'));
//                }
//            } else {
//                $Emp->rollback();
//                $this->error($Emp->getError());
//            }
//            //用户组处理
//            //检查记录是否重复
//            if (count($groupid) != count(array_unique($groupid))) {
//                $this->error(L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_RECORD_REPEAT'));
//            }
//            $groupIdArr = array();
//            foreach ($groupResult as $key => $value) {
//                $groupIdArr[] = $value['id'];
//            }
//            $usergroupData = array();
//            foreach ($groupid as $key => $value) {
//                if(0 == $value){
//                    $Emp->rollback();
//                    $this->error(L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT'));
//                }elseif(!in_array($value,$groupIdArr)){
//                    $Emp->rollback();
//                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'.'1001'));
//                }
//                $usergroupData[] = array(
//                    'usergroup_id' => $value,
//                    'user_id' => $empId,
//                );
//            }
//            if(empty($usergroupData)){
//                $Emp->rollback();
//                $this->error(L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            // 分组写入处理
//            $Usergroup_user = M('Usergroup_user');
//            if(!$Usergroup_user->addAll($usergroupData)){
//                $Emp->rollback();
//                $this->error($Usergroup_user->getError());
//            }
//            $Emp->commit();
            $empTools = new EmpTools();
            if(false === $empTools->add($data)){
                $this->error($empTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }else{
            //取得部门缓存列表
            $deptTree = get_deptTree();
            $this->assign('deptTree',$deptTree);
            //====================用户组处理========================//
            //取得当前用户的归属用户组
            //$Usergroup_user = M('Usergroup_user');
            //$userGroupIdArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
            //取得当前用户归属用户的所有子组
            //$groupIdArr = array();
            //foreach ($userGroupIdArr as $value){
            //    $groupIdArr[] = $value;
            //    foreach (getChildTree($groupResult,$value) as $child){
            //        $groupIdArr[] = $child['id'];
            //    }
            //}
            //数组去重
            //$groupIdArr = array_unique($groupIdArr);
            //foreach ($groupResult as $key => $item) {
            //    if(in_array($item['id'],$groupIdArr)){
            //        $groupResult[$key]['have'] = '1';
            //    }
            //}
            $groupResult = levelTree($groupResult);
            $this->assign('groupResult',$groupResult);
            //=======================================================//
            //渲染输出
            $this->display('empAdd');
        }
    }

    //删除人员信息
    public function empDelete(){
        $id = I('id',0,'intval');
//        if(0 == $id){
//            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
//        }
//        if(UID == $id){
//            $this->error(L('EMP_ERROR_NOT_DEL_SELF'));
//        }
//        $Emp = D('Emp');
//        //取得记录数据
//        if(!is_array($result = $Emp->find($id))){
//            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
//        }elseif($result['login_count'] > 0){
//            $this->error(L('EMP_ERROR_USER_HAS_LOGIN_TRACES'));
//        }
//        //检查是否存在待复核记录
//        $Appove = M('Appove');
//        if($Appove->where(array('type'=>'EMP','reference'=>$id))->count() > 0){
//            $this->error(L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST'));
//        }
//        //启动事务处理
//        $Emp->startTrans();
//        if($Emp->where(array('id'=>$id))->relation(true)->delete()){
//            //删除对应分组信息
//            $Usergroup_user = D('Usergroup_user');
//            if(false === $Usergroup_user->where(array('user_id'=>$id))->delete()){
//                $Emp->rollback();
//                $this->error($Usergroup_user->getError());
//            }
//        }else{
//            $Emp->rollback();
//            $this->error($Emp->getError());
//        }
//        $Emp->commit();
//        LogTools::activeLog($result);
        $empTools = new EmpTools();
        if(false === $empTools->delete($id)){
            $this->error($empTools->getError());
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }

    //人员信息修改
    public function empUpdate(){
        $id = I('id',0,'intval');
        if(0 == $id){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        if(IS_POST){
//            $email = I('email');
//            $name = I('name');
//            $sex = I('sex');
//            $birthday = I('birthday');
//            // $hireday = I('hireday');
//            // $leave_date = I('leave_date');
//            $mobile = I('mobile');
//            // $probation = I('probation',0,'intval');
//            $dept_id = I('dept_id',0,'intval');
//            $is_director = I('is_director',0,'intval');
//            // $post_id = I('post_id',0,'intval');
//            // $real_email = I('real_email');
//            $remark = I('remark');
            // $is_cost = I('is_cost',0,'intval');
            // 输入检查
//            if("" == $name){
//                // $this->error("请输入姓名！");
//                $this->error(L('TEMP_EMP_EMP_NAME').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            if("" == $email && "" == $mobile){
//                // $this->error("邮箱地址及手机号码，必输其一！");
//                $this->erorr(L('EMP_ERROR_EMAIL_PHONE_INPUT'));
//            }
//            if(0 == $dept_id){
//                // $this->error("请指定部门！");
//                $this->error(L('TEMP_EMP_DEPT').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            if("" != $birthday && !date_check('-',$birthday)){
//                // $this->error("生日格式错误！");
//                $this->error(L('TEMP_EMP_EMP_BIRTHDAY').L('SYSTEM_ERROR_DATE_FORMAT'));
//            }else{
//                $birthdayInt = date_to_int('-',$birthday);
//            }
            // if("" != $hireday && !date_check('-',$hireday)){
            //     $this->error("入职日期格式错误！");
            // }else{
            //     $hiredayInt = date_to_int('-',$hireday);
            // }
            // if("" != $leave_date && !date_check('-',$leave_date)){
            //     $this->error("离职日期格式错误！");
            // }else{
            //     $leave_dateInt = date_to_int('-',$leave_date);
            // }
            // if($leave_dateInt <= $hiredayInt){
            //     $this->error("离职日期需要大于入职日期！");
            // }
            // if(0 != $probation && "" == $hireday){
            //     $this->error("指定了试用期时，必须指定入职日期！");
            // }
            // 检查部门号及职位存不存在
//            $Dept = M('Dept');
//            if(!($Dept->find($dept_id))){
//                // $this->error("指定的部门不存在！");
//                $this->error(L('EMP_ERROR_DEPT_NOT_EXIST'));
//            }
//            if('' != $email && $Emp->where(array('id'=>array('NEQ',$id),'email'=>$email))->count() > 0){
//                $this->error(L('TEMP_EMP_EMP_EMAIL').L('SYSTEM_ERROR_RECORD_EXIST'));
//            }
//            if('' != $mobile && $Emp->where(array('id'=>array('NEQ',$id),'mobile'=>$mobile))->count() > 0){
//                $this->error(L('TEMP_EMP_EMP_PHONE').L('SYSTEM_ERROR_RECORD_EXIST'));
//            }
            $data = array(
                'id' => $id,
                'email' => I('email'),
                'name' => I('name'),
                'sex' => I('sex'),
                'mobile' => I('mobile'),
                // 'hireday' => $hiredayInt,
                // 'leave_date' => $leave_dateInt,
                'dept_id' => I('dept_id',0,'intval'),
                'is_director' => I('is_director',0,'intval'),
                // 'probation' => $probation,
                // 'real_email' => $real_email,
                'remark' => I('remark'),
                'groupid' => I('groupid',0,'intval'),
                // 'is_cost' => $is_cost,
            );
//            if($empRecord['email'] != $email) {
//                $data['email'] = $email;
//            }
//            if($empRecord['mobile'] != $mobile) {
//                $data['mobile'] = $mobile;
//            }
            // 分组处理
//            $groupid = I('groupid',0,'intval');
//            //检查记录是否重复
//            if (count($groupid) != count(array_unique($groupid))) {
//                $this->error(L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_RECORD_REPEAT'));
//            }
//            $groupIdArr = array();
//            foreach ($groupResult as $key => $value) {
//                $groupIdArr[] = $value['id'];
//            }
//            $usergroupData = array();
//            foreach ($groupid as $key => $value) {
//                if(0 == $value){
//                    $this->error(L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT'));
//                }elseif(!in_array($value,$groupIdArr)){
//                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'.'1001'));
//                }
//                $usergroupData[] = array(
//                    'usergroup_id' => $value,
//                    'user_id' => $id,
//                );
//            }
//            if(empty($usergroupData)){
//                $this->error(L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//
//            // 启动事务处理
//            $Emp->startTrans();
//            if($Emp->create($data)){
//                $returnCode = $Emp->save() ;
//                if($returnCode !== 0 && $returnCode !== 1 ){
//                    $Emp->rollback();
//                    $this->error($Emp->getError());
//                }
//            }else{
//                $Emp->rollback();
//                $this->error($Emp->getError());
//            }
//
//            // 用户分组写入处理
//            $Usergroup_user = M('Usergroup_user');
//            if(false === $Usergroup_user->where(array('user_id'=>$id))->delete()){
//                $Emp->rollback();
//                $this->error($Usergroup_user->getError());
//            }
//            if(!$Usergroup_user->addAll($usergroupData)){
//                $Emp->rollback();
//                $this->error($Usergroup_user->getError());
//            }
//            $Emp->commit();
            $empTools = new EmpTools();
            if(false === $empTools->update($data)){
                $this->error($empTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }else{
            $Emp = D('Emp');
            if(!($empRecord = $Emp->find($id))){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            $Usergroup = M('Usergroup');
            $groupResult = $Usergroup->field(array('id','pid','name'))->order('sort')->select();
            $this->assign('emp',$empRecord);

            //取得部门缓存列表
            $deptTree = get_deptTree();
            $this->assign('deptTree',$deptTree);

            $Usergroup_user = M('Usergroup_user');
            $usergroupArr = $Usergroup_user->where(array('user_id'=>$id))->getField('usergroup_id',true);
            if(empty($usergroupArr)){
                $usergroupArr[] = array();
            }
            $this->assign('usergroupArr',$usergroupArr);
            //====================用户组处理========================//
            //取得当前用户的归属用户组
            //$Usergroup_user = M('Usergroup_user');
            //$userGroupIdArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
            //取得当前用户归属用户的所有子组
            //$groupIdArr = array();
            //foreach ($userGroupIdArr as $value){
            //    $groupIdArr[] = $value;
            //    foreach (getChildTree($groupResult,$value) as $child){
            //        $groupIdArr[] = $child['id'];
            //    }
            //}
            //数组去重
            //$groupIdArr = array_unique($groupIdArr);
            //foreach ($groupResult as $key => $item) {
            //    if(in_array($item['id'],$groupIdArr)){
            //        $groupResult[$key]['have'] = '1';
            //    }
            //}
            $groupResult = levelTree($groupResult);
            $this->assign('groupResult',$groupResult);
            //=======================================================//

            //渲染输出
            $this->display('empUpdate');
        }
    }

    //人员信息查询
    public function empInquery(){
        $id = I('id',0,'intval');
        if(0 == $id){
            // $this->error('请指定人员！');
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Emp = M('Emp');
        if($empRecord = $Emp->find($id)){
            $this->assign('emp',$empRecord);

            //取得部门缓存列表
            $deptTree = get_deptTree();
            $this->assign('deptTree',$deptTree);

            $Usergroup_user = M('Usergroup_user');
            $usergroupArr = $Usergroup_user->where(array('user_id'=>$id))->getField('usergroup_id',true);
            $groupNameArr = array();
            if(empty($usergroupArr)){
                $groupNameArr[] = array(0);
            }else{
                $Usergroup = M('Usergroup');
                $groupNameArr = $Usergroup->where(array('id'=>array('in',$usergroupArr)))->getField('name',true);
            }
            $this->assign('groupNameArr',$groupNameArr);
            // $this->assign('roleResult',levelTree($roleResult));
            //渲染输出
            $this->display('empInquery');
        }else{
            // $this->error('未找到指定人员信息！');
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
    }

    //设置禁止登录状态
    public function empHold(){
        $id = I('id',0,'intval');
        if(0 == $id){
            // $this->error('请指定要禁止登录的人员！');
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Emp = D('Emp');
        $result = $Emp->find($id);
        if(!is_array($result)){
            // $this->error("未找到指定记录！");
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if($Emp->where(array('id'=>$id))->setField('login_switch',0)){
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $this->error($Emp->getError());
        }
    }
    //解除禁止登录状态
    public function empRelease(){
        $id = I('id',0,'intval');
        $empTools = new EmpTools();
        if(false === $empTools->release($id)){
            $this->error($empTools->getError());
        }else{
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }
//        if(0 == $id){
//            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
//        }
//        $Emp = D('Emp');
//        $result = $Emp->find($id);
//        if(!is_array($result)){
//            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
//        }
//        $data = array(
//            'id' => $id,
//            'login_switch' => 1,
//            'pwd_err_count' => 0,
//        );
//        if($Emp->save($data)){
//            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
//        }else{
//            $this->error($Emp->getError());
//        }
    }

    //密码重置
    public function empPwdReset(){
        $id = I('id',0,'intval');
        if(0 == $id){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Emp = D('Emp');
        if(!($empRecord = $Emp->find($id))){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if(IS_POST){
            $password1 = I('password1');
            $password2 = I('password2');
            $empTools = new EmpTools();
            if(false === $empTools->pwdReset($id,$password1,$password2)){
                $this->error($empTools->getError());
            }
//            // 输入检查
//            if("" == $password1){
//                $this->error(L('TEMP_EMP_EMP_NEW_PWD').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            if("" == $password2){
//                $this->error(L('TEMP_EMP_EMP_CONFIRM_NEW_PWD').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            if($password1 != $password2){
//                $this->error(L('EMP_ERROR_NOT_SAME_PWD'));
//            }
//            $data = array(
//                'id' => $id,
//                'password' => md5($password1),
//            );
//            if($Emp->create($data)){
//                $returnCode = $Emp->save();
//                if($returnCode){
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
//                }elseif($returnCode === 0){
//                    $this->error(L('EMP_ERROR_SAME_PWD_OLD_NEW'));
//                }else{
//                    $this->error($Emp->getError());
//                }
//            }else{
//                $this->error($Emp->getError());
//            }
        }else{
            $this->assign('emp',$empRecord);
            //渲染输出
            $this->display('empPwdReset');
        }
    }
    /*================================================================================================*/
    /*  部门信息管理
    /*      功能清单：
    /*          1-deptList    ： 部门列表浏览
    /*          2-deptAdd     ： 部门信息添加
    /*          3-deptDelete  ： 部门信息删除
    /*          4-DeptUpdate  ： 部门信息更新
    /*          5-DeptSort    ： 部门信息排序
    /*================================================================================================*/
    //浏览部门列表
    public function deptList(){
        $Dept = M('Dept');
        // $map = array();//添加按自身所在，暂时没有用到
        // $list = $Dept->where($map)->order('sort ASC')->select();
        $list = $Dept->order('sort ASC')->select();
        //转换LIST中的属性栏位为说明 
        $propertyArray = array(
            'property'=>L('TEMP_EMP_DEPT_PROPERTY_TEXT'),
        );
        status_to_string($list,$propertyArray);
        $list = levelTree($list);
        // $list = get_deptTree();
        $this->assign('list',$list);// 赋值数据集
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->display('deptList'); // 输出模板
    }

    //添加部门信息
    public function deptAdd(){
        if(IS_POST){
            $name = I('name');
            $property = I('property');
            $sort = I('sort',0,'intval');
            $remark = I('remark');
            $pid = I('pid',0,'intval');
            if('' == $name){
                $this->error(L('TEMP_EMP_DEPT_NAME').','.L('SYSTEM_ERROR_MUST_INPUT'));
            }
            $Dept = M('Dept');
            if(0!=$pid && !($pDept = $Dept->find($pid))){
                // $this->error('指定的父级部门不存在！');
                $this->error(L('EMP_ERROR_FATHER_DEPT_NOT_EXIST'));
            }else {
                if(is_array($Dept->where(array('name'=>$name))->find())){
                    // $this->error('已经存在同名部门！');
                    $this->error(L('EMP_ERROR_DEPT_NAME_EXIST'));
                }else{
                    $data = array(
                        'name' => $name,
                        // 'area' => $area,
                        'property' => $property,
                        'sort' => $sort,
                        'pid' => $pid,
                        // 'divid' => $divid,
                        'remark' => $remark,
                    );
                    if ($Dept->create($data)) {
                        $Dept->add();
                        delete_deptTree();//删除旧的缓存记录
                        LogTools::activeLog($data);
                        $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                    } else {
                        $this->error($Dept->getError());
                    }
                }
            }
        }else{
            $pid = I('pid',0,'intval');
            $this->assign('pid',$pid);
            $this->display('deptAdd');
        }
    }

    //删除部门信息
    public function deptDelete(){
        $id = I('id',0,'intval');
        if(0 == $id){
            // $this->error('请指定要删除的部门！');
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Dept = M('Dept');
        if(!is_array($result = $Dept->find($id))){
            // $this->error('指定部门不存在，不可删除！');
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if($Dept->where(array('pid'=>$id))->select()){
            // $this->error('存在下级部门，不可删除！');
            $this->error(L('EMP_ERROR_HAVE_CHILD_DEPT'));
        }
        $Emp = M('Emp');
        if($Emp->where(array('dept_id'=>$id))->find()){
            // $this->error('存在关联员工，不可删除！');
            $this->error(L('EMP_ERROR_HAVE_EMP'));
        }
        if($Dept->where(array('id'=>$id))->delete()){
            delete_deptTree();//删除旧的缓存记录
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $this->error($Dept->getError());
        }
    }

    //部门信息修改
    public function deptUpdate(){
        if(IS_POST){
            $data['id'] = I('id',0,'intval');
            $data['name'] = I('name');
            // $data['area'] = I('area');
            $data['property'] = I('property');
            $data['sort'] = I('sort',0,'intval');
            $data['remark'] = I('remark');
            // 输入检查
            if('' == $data['name']){
                $this->error(L('TEMP_EMP_DEPT_NAME').','.L('SYSTEM_ERROR_MUST_INPUT'));
            }
            $Dept = M('Dept');
            $oDept = $Dept->find($data['id']);
            if(is_array($oDept)){
                if($oDept['name'] != $data['name']){
                    if(is_array($Dept->where(array('name'=>$data['name']))->find())){
                        $this->error(L('EMP_ERROR_DEPT_NAME_EXIST'));
                    }
                }
            }
            if($Dept->create($data)){
                $returnCode = $Dept->save();
                if(1 === $returnCode){
                    delete_deptTree();//删除旧的缓存记录
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }elseif(0 === $returnCode) {
                    $this->error(L('SYSTEM_MESSAGE_NOT_CHANGE'));
                }else{
                    $this->error(L('SYSTEM_MESSAGE_ERROR'));
                }
            }else{
                $this->error($Dept->getError());
            }
        }else{
            $id = I('id',0,'intval');
            if(0 == $id){
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            }
            $Dept = M('Dept');
            if($deptRecord = $Dept->find($id)){
                $this->assign('dept',$deptRecord);
                $this->display('deptUpdate');
            }else{
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
        }
    }

    //部门排序
    public function deptSort(){
        $ids = I('ids',0,'intval');
        $sort = I('sort',0,'intval');
        $Dept = M('Dept');
        $i = 0;
        while(null != $ids[$i]){
            $where = array(
                'id' => $ids[$i],
            );
            $Dept->where($where)->setField('sort',$sort[$i]);
            $i++;
        }
        delete_deptTree();//删除旧的缓存记录
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }

    /*================================================================================================*/
    /*  职位信息管理
    /*      功能清单：
    /*          1-roleList          ： 角色列表浏览
    /*          2-roleAdd           ： 角色信息添加
    /*          3-roleDelete        ： 角色信息删除
    /*          4-roleUpdate        ： 角色信息更新
    /*          5-roleSort          ： 角色信息排序
    /*          6-access            ： 角色权限维护
    /*================================================================================================*/
    //浏览职位列表
    public function roleList(){
        $Role = M('Role');
        $map = array();//添加按自身所在
        $list = $Role->where($map)->order('sort ASC')->select();
        //转换LIST中的类型栏位转为说明 
        $statusArray = array(
            'type'=>C('ROLE_TYPE'),
        );
        status_to_string($list,$statusArray);
        $list = levelTree($list);
        $this->assign('list',$list);// 赋值数据集
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->display('roleList'); // 输出模板
    }

    //添加职位信息
    public function roleAdd(){
        if(IS_POST){
            $name = I('name');
            $sort = I('sort',0,'intval');
            $remark = I('remark');
            $pid = I('pid',0,'intval');
            // 输入检查
            if("" == $name || "" == $remark){
                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
            }
            $Role = D('Role');
            if(0!=$pid && !($pRole = $Role->where(array('id'=>$pid))->find())){
                $this->error(L('EMP_ERROR_FATHER_ROLE_NOT_EXIST'));
            }else {
                if(is_array($Role->where(array('name'=>$name))->find())){
                    $this->error(L('EMP_ERROR_ROLE_NAME_EXIST'));
                }
                $data = array(
                    'name' => $name,
                    'sort' => $sort,
                    'pid' => $pid,
                    'remark' => $remark,
                    'status' => 1,
                );
                if ($Role->create($data)) {
                    $roleId = $Role->add();
                    $data['id'] = $roleId;
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                } else {
                    $this->error($Role->getError());
                }
            }
        }else{
            $pid = I('pid',0,'intval');
            $this->assign('pid',$pid);
            $this->display('roleAdd');
        }
    }

    //删除职位信息
    public function roleDelete(){
        $id = I('id',0,'intval');
        if(0 == $id){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Role = M('Role');
        if($result = $Role->where(array('pid'=>$id))->find()){
            $this->error(L('EMP_ERROR_HAVE_CHILD_ROLE'));
        }
        $Role_usergroup = M('Role_usergroup');
        if($Role_usergroup->where(array('role_id'=>$id))->find()){
            $this->error(L('EMP_ERROR_ASSOCIATED_USER_GROUP'));
        }
        if($Role->where(array('id'=>$id))->delete()){
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $this->error($Role->getError());
        }
    }

    //职位信息修改
    public function roleUpdate(){
        if(IS_POST){
            $data['id'] = I('id',0,'intval');
            $data['name'] = I('name');
            $data['sort'] = I('sort',0,'intval');
            $data['remark'] = I('remark');
            // 输入检查
            if(0 == $data['id']){
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            }
            if("" == $data['name'] || "" == $data['remark']){
                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
            }
            $Role = D('Role');
            $oRole = $Role->find($data['id']);
            if(is_array($oRole)){
                if($oRole['name'] != $data['name']){
                    if(is_array($Role->where(array('name'=>$data['name']))->find())){
                        $this->error(L('EMP_ERROR_ROLE_NAME_EXIST'));
                    }
                }
            }else{
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            if($Role->create($data)){
                $returnCode = $Role->save();
                if(1 === $returnCode){
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }elseif(0 === $returnCode) {
                    $this->error(L('SYSTEM_MESSAGE_NOT_CHANGE'));
                }else{
                    $this->error(L('SYSTEM_MESSAGE_ERROR'));
                }
            }else{
                $this->error($Role->getError());
            }
        }else{
            $id = I('id',0,'intval');
            if(0 == $id){
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            }
            $Role = M('Role');
            if($roleRecord = $Role->where(array('id'=>$id))->find()){
                $this->assign('role',$roleRecord);
                $this->display('roleUpdate');
            }else{
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
        }
    }

    //职位排序
    public function roleSort(){
        $ids = I('ids',0,'intval');
        $sort = I('sort',0,'intval');
        $Role = D('Role');
        $i = 0;
        while(null != $ids[$i]){
            $where = array(
                'id' => $ids[$i],
            );
            $Role->where($where)->setField('sort',$sort[$i]);
            $i++;
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }
    
    // 权限配置
    public function access(){
        $roleid = I('roleid',0,'intval');
        if(0 == $roleid){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        if(IS_POST){
            $accessNode = I('accessNode',0,'intval');
            $accessTools = new AccessTools();
            if(false === $accessTools->access($roleid,$accessNode)){
                $this->error($accessTools->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
            }

//            $nodeIdArr = array();
//            foreach ($nodeResult as $key => $value) {
//                $nodeIdArr[] = $value['id'];
//            }
//            $accessNode = I('accessNode',0,'intval');
//            $data = array();
//            foreach ($accessNode as $key => $value) {
//                if(0 == $value){
//                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR')."1001");
//                }elseif(!in_array($value, $nodeIdArr)){
//                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR')."1002");
//                }
//                $data[] = array(
//                    'role_id' => $roleid,
//                    'node_id' => $value,
//                    'level' => $Node->where(array('id'=>$value))->getField('level'),
//                );
//            }
//            $Access = M('Access');
//            // 启动事务
//            $Access->startTrans();
//            // 删除原有访问权限记录
//            if(false === $Access->where(array('role_id'=>$roleid))->delete()){
//                $Access->rollback();
//                $this->error($Access->getError());
//            }
//            // 添加新访问权限记录
//            if(!empty($data)){
//                if(!$Access->addAll($data)){
//                    $this->error($Access->getError());
//                }
//            }
//            $Access->commit();
//            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $Role = M('Role');
            $roleResult = $Role->find($roleid);
            if(!is_array($roleResult)){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            $Node = M('Node');
            $field = array('id','title_en','title_zh','pid','level');
            $nodeResult = $Node->field($field)->order('sort')->select();
            foreach ($nodeResult as $key => $value) {
                $nodeResult[$key]['title'] = 'en-us' == LANG_SET ? $value['title_en'] : $value['title_zh'];
            }
            // 读取原有权限
            $Access = M('Access');
            $accessResult = $Access->where(array('role_id'=>$roleid))->getField('node_id',true);
            // 循环给点节添加当前已存在的访问权限
            foreach ($nodeResult as $key => $value) {
                if(in_array($value['id'], $accessResult)){
                    $nodeResult[$key]['access'] = 1;
                }else{
                    $nodeResult[$key]['access'] = 0;
                }
            }
            $nodeTree = tree($nodeResult);
            $this->assign('roleid',$roleid);
            $this->assign('result',$nodeTree);
            $this->display('access');
        }
    }
    /*================================================================================================*/
    /*  分组信息管理
    /*      功能清单：
    /*          1-groupList    ： 分组列表浏览
    /*          2-groupAdd     ： 分组信息添加
    /*          3-groupDelete  ： 分组信息删除
    /*          4-groupUpdate  ： 分组信息更新
    /*          5-groupSort    ： 分组信息排序
    /*================================================================================================*/
    //分组列表浏览
    public function groupList(){
        $Usergroup = M('Usergroup');
        $map = array();
        $list = $Usergroup->where($map)->order('sort ASC')->select();
        //取得当前用户的归属用户组
        //$Usergroup_user = M('Usergroup_user');
        //$userGroupIdArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
        //取得当前用户归属用户的所有子组
        //$groupIdArr = array();
        //foreach ($userGroupIdArr as $value){
        //    $groupIdArr[] = $value;
        //    foreach (getChildTree($list,$value) as $child){
        //        $groupIdArr[] = $child['id'];
        //    }
        //}
        //数组去重
        //$groupIdArr = array_unique($groupIdArr);
        //foreach ($list as $key => $item) {
        //    if(in_array($item['id'],$groupIdArr)){
        //        $list[$key]['have'] = '1';
        //    }
        //}
        //转换LIST中的类型栏位转为说明 
        // $statusArray = array(
        //     'type'=>C('ROLE_TYPE'),
        // );
        // status_to_string($list,$statusArray);
        $list = levelTree($list);
        $this->assign('list',$list);// 赋值数据集
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->display('groupList'); // 输出模板
    }

    //分组信息添加
    public function groupAdd(){
        if(IS_POST){
            $data = array(
                'name' => I('name'),
                'sort' => I('sort',0,'intval'),
                'remark' => I('remark'),
                'roleid' => I('roleid',0,'intval'),
                'pid' => I('pid',0,'intval'),
            );
            $userGroupTools = new UserGroupTools();
            if(false === $userGroupTools->add($data)){
                $this->error($userGroupTools->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
//            $name = I('name');
//            $sort = I('sort',0,'intval');
//            $remark = I('remark');
//            $roleid = I('roleid',0,'intval');
//            if("" == $name || "" == $remark){
//                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            $Usergroup = D('Usergroup');
//            if(0 != $pid && $Usergroup->where(array('id'=>$pid))->count() < 1){
//                $this->error(L('EMP_ERROR_USERGROUP_NAME_EXIST'));
//            }
//            if(is_array($Usergroup->where(array('name'=>$name))->find())){
//                $this->error(L('EMP_ERROR_USERGROUP_NAME_EXIST'));
//            }
//            $data = array(
//                'pid' => $pid,
//                'name' => $name,
//                'sort' => $sort,
//                'remark' => $remark,
//            );
//            //启动事务管理
//            $Usergroup->startTrans();
//            $groupID = $Usergroup->add($data);
//            if (!$groupID) {
//                $Usergroup->rollback();
//                $this->error($Usergroup->getError());
//            }
//            //角色处理
//            //检查角色是否重复
//            if (count($roleid) != count(array_unique($roleid))) {
//                $this->error(L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_RECORD_REPEAT'));
//            }
//            $roleIdArr = array();
//            foreach ($roleResult as $value) {
//                $roleIdArr[] = $value['id'];
//            }
//            $groupRoleData = array();
//            foreach ($roleid as $value) {
//                if(0 == $value){
//                    $Usergroup->rollback();
//                    $this->error(L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT'));
//                }elseif(!in_array($value,$roleIdArr)){
//                    $Usergroup->rollback();
//                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'.'1001'));
//                }
//                $groupRoleData[] = array(
//                    'role_id'     => $value,
//                    'usergroup_id' => $groupID,
//                );
//            }
//            if(empty($groupRoleData)){
//                $Usergroup->rollback();
//                $this->error(L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            // 角色写入处理
//            $Role_usergroup = M('Role_usergroup');
//            if(!$Role_usergroup->addAll($groupRoleData)){
//                $Usergroup->rollback();
//                $this->error($Role_usergroup->getError());
//            }
//            $Usergroup->commit();
            //$this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }else{
            //$pid = I('pid',0,'intval');
            //取得角色信息
            $Role = M('Role');
            $roleResult = $Role->order('sort')->field('id,name')->select();
            $this->assign('roleResult',$roleResult);
            $this->assign('pid',I('pid',0,'intval'));
            $this->display('groupAdd');
        }
    }

    //分组信息删除
    public function groupDelete(){
        $id = I('id',0,'intval');
        $userGroupTools = new UserGroupTools();
        if(false === $userGroupTools->delete($id)){
            $this->error($userGroupTools->getError());
        }else{
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }
//        if(0 == $id){
//            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
//        }
//        $Usergroup = M('Usergroup');
//        $Usergroup->startTrans();
//        //删除用户组与用户的关联关系
//        $Usergroup_user = M('Usergroup_user');
//        if(false === $Usergroup_user->where(array('usergroup_id'=>$id))->delete()){
//            $Usergroup->rollback();
//            $this->error($Usergroup_user->getError());
//        }
//        //删除用户组与角色的关联关系
//        $Role_usergroup = M('Role_usergroup');
//        if(false === $Role_usergroup->where(array('usergroup_id'=>$id))->delete()){
//            $Usergroup->rollback();
//            $this->error($Role_usergroup->getError());
//        }
//        //清空邮件模板中与该用户组的关联
//        $Mailtpl = M('Mailtpl');
//        if(false === $Mailtpl->where(array('cc_user_group'=>$id))->setField('cc_user_group',0)){
//            $Usergroup->rollback();
//            $this->error($Mailtpl->getError());
//        }
//        //删除用户组
//        if($Usergroup->where(array('id'=>$id))->delete()){
//            $Usergroup->commit();
//            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
//        }else{
//            $Usergroup->rollback();
//            $this->error($Usergroup->getError());
//        }
    }

    //分组信息更新
    public function groupUpdate(){
        if(IS_POST){
            $data = array(
                'id' => I('id',0,'intval'),
                'name' => I('name'),
                'sort' => I('sort',0,'intval'),
                'remark' => I('remark'),
                'roleid' => I('roleid',0,'intval'),
            );
            $userGroupTools = new UserGroupTools();
            if(false === $userGroupTools->update($data)){
                $this->error($userGroupTools->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
//            $data['id'] = I('id',0,'intval');
//            $data['name'] = I('name');
//            $data['sort'] = I('sort',0,'intval');
//            $data['remark'] = I('remark');
//            // 输入检查
//            if(0 == $data['id']){
//                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
//            }
//            if("" == $data['name'] || "" == $data['remark']){
//                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            //检查名称是否重复
//            if($groupRecord['name'] != $data['name']){
//                if(is_array($Usergroup->where(array('name'=>$data['name']))->find())){
//                    $this->error(L('EMP_ERROR_GROUP_NAME_EXIST'));
//                }
//            }
//            // 角色处理
//            $roleid = I('roleid',0,'intval');
//            //检查角色是否重复
//            if (count($roleid) != count(array_unique($roleid))) {
//                $this->error(L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_RECORD_REPEAT'));
//            }
//            $roleIdArr = array();
//            foreach ($roleResult as $value) {
//                $roleIdArr[] = $value['id'];
//            }
//            $rolegroupData = array();
//            foreach ($roleid as $value) {
//                if(0 == $value){
//                    $this->error(L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT'));
//                }elseif(!in_array($value,$roleIdArr)){
//                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'.'1001'));
//                }
//                $rolegroupData[] = array(
//                    'role_id' => $value,
//                    'usergroup_id' => $id,
//                );
//            }
//            if(empty($rolegroupData)){
//                $this->error(L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT'));
//            }
//            //启动事务
//            $Usergroup->startTrans();
//            if(false ===$returnCode = $Usergroup->save($data)){
//                $Usergroup->rollback();
//                $this->error($Usergroup->getError());
//            }
//            // 用户组角色写入处理
//            $Role_usergroup = M('Role_usergroup');
//            if(false === $Role_usergroup->where(array('usergroup_id'=>$id))->delete()){
//                $Usergroup->rollback();
//                $this->error($Role_usergroup->getError());
//            }
//            if(!$Role_usergroup->addAll($rolegroupData)){
//                $Usergroup->rollback();
//                $this->error($Role_usergroup->getError());
//            }
//            $Usergroup->commit();
//            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }else{
            $id = I('id',0,'intval');
            if(0 == $id){
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            }
            $Usergroup = M('Usergroup');
            if(!is_array($groupRecord = $Usergroup->where(array('id'=>$id))->find())){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            //取得角色信息
            $Role = M('Role');
            $roleResult = $Role->order('sort')->field('id,name')->select();
            $role_usergroup = M('Role_usergroup');
            $roleGroupArr = $role_usergroup->where(array('usergroup_id'=>$id))->getField('role_id',true);
            if(empty($roleGroupArr)){
                $roleGroupArr[] = array();
            }
            $this->assign('roleGroupArr',$roleGroupArr);
            $this->assign('roleResult',$roleResult);
            $this->assign('result',$groupRecord);
            $this->display('groupUpdate');
        }
    }

    //分组信息排序
    public function groupSort(){
        $ids = I('ids',0,'intval');
        $sort = I('sort',0,'intval');
        $Usergroup = D('Usergroup');
        $i = 0;
        while(null != $ids[$i]){
            $where = array(
                'id' => $ids[$i],
            );
            $Usergroup->where($where)->setField('sort',$sort[$i]);
            $i++;
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }

    /*================================================================================================*/
    /*  用户维护复核
    /*      功能清单：
    /*          1-empAppoveList      ： 用户信息待复核队列
    /*          2-empAppove          ： 用户信息复核
    /*          3-empReject          ： 用户信息拒绝
    /*================================================================================================*/
    //客户信息待复核队列
    public function empAppoveList(){
        //取得当前用户所属组及其下属组的所有用户
        //$empTools = new EmpTools();
        //$userIDArr = $empTools->getGroupUser();
        //$userIDStr = implode($userIDArr,',');
        //if("" == $userIDStr) {
            //如果没有相应的同组用户及下属组用户,则不做查询
        //    $list = "";
        //}else{
            //取得有权复核的所有记录
            $map = array(
                'type' => 'EMP',
                'maker' => array('NEQ', UID),
            );
            $list = $this->lists('AppoveView', $map);
            //转换LIST中的操作类型栏位转换为说明
            $funcArray = array(
                'func' => L('COMMON_ACTION_TEXT'),
            );
            status_to_string($list, $funcArray);
        //}
        $Emp = M('Emp');
        foreach($list as $key => $value){
            if(empty($value['reference'])){
                $tempResult = json_decode($value['content'],true);
                $list[$key]['emp_name'] = $tempResult['name'];
            }else {
                $list[$key]['emp_name'] = $Emp->where(array('id' => $value['reference']))->getField('name');
            }
        }
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('list',$list);
        $this->display('empAppoveList');
    }

    //用户信息复核
    public function empAppove(){
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        $flag = I('flag');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        if('' != $flag) {
            $empTools = new EmpTools();
            if(false === $empTools->appove($date,$seq)){
                $this->error($empTools->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            //===========================取得复核记录中的信息==========================
            //取得部门缓存列表
            $deptTree = get_deptTree();
            $this->assign('deptTree',$deptTree);
            //取得用户组全集、定义用户组与用户的关联模型
            $Usergroup = M('Usergroup');
            $groupResult = $Usergroup->getField('id,name',true);
            $Usergroup_user = M('Usergroup_user');
            $empUsergroupStr = "";
            $appoveUsergroupStr = "" ;
            //取得待复核记录
            $Appove = M('Appove');
            if(!is_array($appoveRecord = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }else{
                $appoveResult = json_decode($appoveRecord['content'],true);
            }
            foreach($appoveResult['groupid'] as $value){
                $appoveUsergroupStr .= '['.$groupResult[$value].']';
            }
            if('A' == $appoveRecord['func']) {
                $this->assign('empResult', $appoveResult);
                $this->assign('empUsergroupStr', $appoveUsergroupStr);
            }
            $this->assign('appoveResult', $appoveResult);
            $this->assign('appoveUsergroupStr', $appoveUsergroupStr);
            //========================================================================
            //===========================取得原用户信息===============================
            if('A' != $appoveRecord['func']) {
                $Emp = D('Emp');
                if(!is_array($empResult = $Emp->find($appoveRecord['reference']))){
                    $this->error(L('SYSTEM_ERROR_SYSTEM_ERROR'));
                }
                //取得当前用户的归属用户组
                $userGroupIdArr = $Usergroup_user->where(array('user_id'=>$empResult['id']))->getField('usergroup_id',true);
                //取得当前用户归属用户名称
                foreach($userGroupIdArr as $value){
                    $empUsergroupStr .= '['.$groupResult[$value].']';
                }
                $this->assign('empUsergroupStr', $empUsergroupStr);
                $this->assign('empResult',$empResult);
            }
            //=====================================================================
            $this->assign('func',$appoveRecord['func']);
            $this->assign('date',$date);
            $this->assign('seq',$seq);
            $this->display('empAppove');
        }
    }

    //用户信息拒绝
    public function empReject(){
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Appove = M('Appove');
        if(!is_array($result = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if(false === $Appove->where(array('date'=>$date,'seq'=>$seq))->delete()){
            $this->error($Appove->getError());
        }else{
            //登记历史
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }

    /*================================================================================================*/
    /*  权限维护复核
    /*      功能清单：
    /*          1-accessAppoveList      ： 角色复核队列
    /*          2-accessAppove          ： 角色信息复核
    /*          3-accessReject          ： 角色信息拒绝
    /*================================================================================================*/
    //角色信息待复核队列
    public function accessAppoveList(){
        //取得有权复核的所有记录
        $map = array(
            'type' => 'ACCESS',
            'maker' => array('NEQ',UID),
        );
        $list = $this->lists('AppoveView', $map);
        //转换LIST中的操作类型栏位转换为说明
        $funcArray = array(
            'func' => L('COMMON_ACTION_TEXT'),
        );
        status_to_string($list, $funcArray);
        $Role = M('Role');
        foreach($list as $key => $value){
            $list[$key]['role_name'] = $Role->where(array('id'=>$value['reference']))->getField('name');
        }
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('list',$list);
        $this->display('accessAppoveList');
    }

    //用户信息复核
    public function accessAppove(){
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        $flag = I('flag');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        if('' != $flag) {
            $accessTools = new AccessTools();
            if(false === $accessTools->appove($date,$seq)){
                $this->error($accessTools->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            $Appove = M('Appove');
            if(!is_array($appoveResult = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            //===========================取得复核记录中的信息==========================
            $Role = M('Role');
            if(!is_array($roleResult = $Role->find($appoveResult['reference']))){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            $Node = M('Node');
            $field = array('id','title_en','title_zh','pid','level');
            $nodeResult = $Node->field($field)->order('sort')->select();
            foreach ($nodeResult as $key => $value) {
                $nodeResult[$key]['title'] = 'en-us' == LANG_SET ? $value['title_en'] : $value['title_zh'];
            }
            // 读取原有权限
            $Access = M('Access');
            $accessResult = $Access->where(array('role_id'=>$appoveResult['reference']))->getField('node_id',true);
            // 循环给点节添加修改后的权限
            $newAccessResult = json_decode($appoveResult['content'],true);
            foreach($nodeResult as $key => $value){
                $nodeResult[$key]['access'] = 0;
                foreach ($newAccessResult as $newAccessKey => $newAccessValue){
                    if($value['id'] == $newAccessValue['node_id']){
                        $nodeResult[$key]['access'] = 1;
                    }
                }
            }
            // 循环给点节添加当前已存在的访问权限
            foreach ($nodeResult as $key => $value) {
                if(in_array($value['id'], $accessResult)){
                    $nodeResult[$key]['old_access'] = 1;
                }else{
                    $nodeResult[$key]['old_access'] = 0;
                }
            }
            $nodeTree = tree($nodeResult);
            $this->assign('result',$nodeTree);
            //=====================================================================
            $this->assign('date',$date);
            $this->assign('seq',$seq);
            $this->display('accessAppove');
        }
    }

    //用户信息拒绝
    public function accessReject(){
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Appove = M('Appove');
        if(!is_array($result = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if(false === $Appove->where(array('date'=>$date,'seq'=>$seq))->delete()){
            $this->error($Appove->getError());
        }else{
            //登记历史
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }

    /*================================================================================================*/
    /*  权限维护复核
    /*      功能清单：
    /*          1-groupAppoveList      ： 用户组复核队列
    /*          2-groupAppove          ： 用户组信息复核
    /*          3-groupReject          ： 用户组信息拒绝
    /*================================================================================================*/
    //角色信息待复核队列
    public function groupAppoveList(){
        //取得有权复核的所有记录
        $map = array(
            'type' => 'GROUP',
            'maker' => array('NEQ',UID),
        );
        $list = $this->lists('AppoveView', $map);
        //转换LIST中的操作类型栏位转换为说明
        $funcArray = array(
            'func' => L('COMMON_ACTION_TEXT'),
        );
        status_to_string($list, $funcArray);
        $Usergroup = M('Usergroup');
        foreach($list as $key => $value){
            if(empty($value['reference'])){
                $tempResult = json_decode($value['content'],true);
                $list[$key]['group_name'] = $tempResult['name'];
            }else {
                $list[$key]['group_name'] = $Usergroup->where(array('id'=>$value['reference']))->getField('name');
            }
        }
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('list',$list);
        $this->display('groupAppoveList');
    }

    //用户信息复核
    public function groupAppove(){
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        $flag = I('flag');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        if('' != $flag) {
            $userGroupTools = new UserGroupTools();
            if(false === $userGroupTools->appove($date,$seq)){
                $this->error($userGroupTools->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            //===========================取得复核记录中的信息==========================
            //取得用户组全集、定义用户组与用户的关联模型
            $Role = M('Role');
            $roleResult = $Role->getField('id,name',true);
            $Role_usergroup = M('Role_usergroup');
            $groupRoleStr = "";
            $appoveRoleStr = "" ;
            //取得待复核记录
            $Appove = M('Appove');
            if(!is_array($appoveRecord = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())){
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }else{
                $appoveResult = json_decode($appoveRecord['content'],true);
            }
            foreach($appoveResult['roleid'] as $value){
                $appoveRoleStr .= '['.$roleResult[$value].']';
            }
            if('A' == $appoveRecord['func']) {
                $this->assign('groupResult', $appoveResult);
                $this->assign('groupRoleStr', $appoveRoleStr);
            }
            $this->assign('appoveResult', $appoveResult);
            $this->assign('appoveRoleStr', $appoveRoleStr);
            //========================================================================
            //===========================取得原用户信息===============================
            if('A' != $appoveRecord['func']) {
                $Usergroup = D('Usergroup');
                if(!is_array($groupResult = $Usergroup->find($appoveRecord['reference']))){
                    $this->error(L('SYSTEM_ERROR_SYSTEM_ERROR'));
                }
                //取得当前用户组的角色
                $groupRoleIdArr = $Role_usergroup->where(array('usergroup_id'=>$groupResult['id']))->getField('role_id',true);
                //取得当前用户归的角色名称
                foreach($groupRoleIdArr as $value){
                    $groupRoleStr .= '['.$roleResult[$value].']';
                }
                $this->assign('groupRoleStr', $groupRoleStr);
                $this->assign('groupResult',$groupResult);
            }
            //=====================================================================
            $this->assign('func',$appoveRecord['func']);
            $this->assign('date',$date);
            $this->assign('seq',$seq);
            $this->display('groupAppove');
        }
    }

    //用户信息拒绝
    public function groupReject(){
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Appove = M('Appove');
        if(!is_array($result = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if(false === $Appove->where(array('date'=>$date,'seq'=>$seq))->delete()){
            $this->error($Appove->getError());
        }else{
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }
}