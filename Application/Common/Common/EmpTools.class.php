<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/15
 * Time: 18:46
 */

namespace Common\Common;


class EmpTools
{
    const APPOVE_YES = 'Y';
    const APPOVE_NO  = 'N';
    const APPOVE_TYPE = 'EMP';
    const INCLUDE_SELF = 'Y';
    private $_errorMessage;
    private $_EmpModel;
    private $_appoveModel;
    private $_data;
    private $_appoveFlag;

    public function __construct()
    {
        $this->_EmpModel = D('Emp');
        $this->_appoveModel = D('Appove');
        $this->_appoveFlag = C('ACCESS_APPOVE');
    }

    /**
     * 用户添加
     */
    public function add($data){
        $this->_data = $data;
        // 启动事务处理
        $this->_EmpModel->startTrans();
        if(false === $this->addRecord()){
            $this->_EmpModel->rollback();
            return false;
        }
        if(self::APPOVE_YES === $this->_appoveFlag) {
            $this->_EmpModel->rollback();
            if (false === $this->addAppove('A')) {
                $this->_EmpModel->rollback();
                return false;
            }
        }
        $this->_EmpModel->commit();
        //写log记录
        LogTools::activeLog($this->_data);
        return true;
    }

    /**
     * 内部类,记录添加,此类与复核共用
     */
    private function addRecord(){
        if(false === $this->validation('A')){
            return false;
        }
        if ($this->_EmpModel->create($this->_data)) {
            $empId = $this->_EmpModel->add();
            if(!$empId){
                $this->_errorMessage = L('SYSTEM_MESSAGE_ERROR');
                return false;
            }
        } else {
            $this->_errorMessage = $this->_EmpModel->getError();
            return false;
        }
        //用户组处理
        $usergroupData = array();
        foreach ($this->_data['groupid'] as $key => $value) {
            $usergroupData[] = array(
                'usergroup_id' => $value,
                'user_id' => $empId,
            );
        }
        if(empty($usergroupData)){
            $this->_errorMessage = L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        // 分组写入处理
        $Usergroup_user = M('Usergroup_user');
        if(!$Usergroup_user->addAll($usergroupData)){
            $this->_errorMessage = $Usergroup_user->getError();
            return false;
        }
        return true;
    }

    /**
     * 用户修改
     */
    public function update($data){
        $this->_data = $data;
        //检查当前客户号待复核记录是否已经存在
        if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$this->_data['id']))->count() > 0){
            $this->_errorMessage = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
            return false;
        }
        // 启动事务处理
        $this->_EmpModel->startTrans();
        if(false === $this->updateRecord()){
            $this->_EmpModel->rollback();
            return false;
        }
        if(self::APPOVE_YES === $this->_appoveFlag) {
            $this->_EmpModel->rollback();
            if (false === $this->addAppove('M')) {
                $this->_EmpModel->rollback();
                return false;
            }
        }
        $this->_EmpModel->commit();
        //写log记录
        LogTools::activeLog($this->_data);
        return true;
    }

    /**
     *  内部类,记录修改,此类与复核共用
     */
    private function updateRecord(){
        if(false === $this->validation('M')){
            return false;
        }
        if(0 == $this->_data['id']){
            $this->_errorMessage = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        // 分组处理
        $usergroupData = array();
        foreach ($this->_data['groupid'] as $key => $value) {
            $usergroupData[] = array(
                'usergroup_id' => $value,
                'user_id' => $this->_data['id'],
            );
        }
        if(empty($usergroupData)){
            $this->_errorMessage = L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }

        if($this->_EmpModel->create($this->_data)){
            $returnCode = $this->_EmpModel->save() ;
            if($returnCode !== 0 && $returnCode !== 1 ){
                $this->_errorMessage = $this->_EmpModel->getError();
                return false;
            }
        }else{
            $this->_errorMessage = $this->_EmpModel->getError();
            return false;
        }

        // 用户分组写入处理
        $Usergroup_user = M('Usergroup_user');
        if(false === $Usergroup_user->where(array('user_id'=>$this->_data['id']))->delete()){
            $this->_errorMessage = $Usergroup_user->getError();
            return false;
        }
        if(!$Usergroup_user->addAll($usergroupData)){
            $this->_errorMessage = $Usergroup_user->getError();
            return false;
        }
        return true;
    }

    /*
     * 记录删除
     */
    public function delete($id){
        if(empty($id)){
            $this->_errorMessage = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        if(UID == $id){
            $this->_errorMessage = L('EMP_ERROR_NOT_DEL_SELF');
            return false;
        }
        //取得记录数据
        if(!is_array($this->_data = $this->_EmpModel->find($id))){
            $this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        // 启动事务处理
        $this->_EmpModel->startTrans();
        if(false === $this->deleteRecord()){
            $this->_EmpModel->rollback();
            return false;
        }
        if(self::APPOVE_YES === $this->_appoveFlag) {
            $this->_EmpModel->rollback();
            if (false === $this->addAppove('D')) {
                $this->_EmpModel->rollback();
                return false;
            }
        }
        $this->_EmpModel->commit();
        //写log记录
        LogTools::activeLog($this->_data);
        return true;
    }

    /**
     *  内部类,记录删除,此类与复核共用
     */
    private function deleteRecord(){
        if(0 == $this->_data['id']){
            $this->_errorMessage = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        //取得记录数据,为保证复核与录入的时间差中用户分录系统,这里需要再取一次记录数据
        if(!is_array($result = $this->_EmpModel->find($this->_data['id']))){
            $this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
//        elseif($result['login_count'] > 0){
//            $this->_errorMessage = L('EMP_ERROR_USER_HAS_LOGIN_TRACES');
//            return false;
//        }
//        if($this->_EmpModel->where(array('id'=>$this->_data['id']))->relation(true)->delete()){
//            //删除对应分组信息
//            $Usergroup_user = D('Usergroup_user');
//            if(false === $Usergroup_user->where(array('user_id'=>$this->_data['id']))->delete()){
//                $this->_errorMessage = $Usergroup_user->getError();
//                return false;
//            }
        $updateData = array(
            'id' => $this->_data['id'],
            'status' => 'D',
            'email' => '',
        );
        if(!$this->_EmpModel->save($updateData)){
            $this->_errorMessage = $this->_EmpModel->getError();
            return false;
        }
        return true;
    }

    /**
     * 数据验证
     */
    private function validation($func){
        // 输入检查
        if("" == $this->_data['name']){
            $this->_errorMessage = L('TEMP_EMP_EMP_NAME').L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        if("" == $this->_data['email'] && "" == $this->_data['mobile']){
            $this->_errorMessage = L('EMP_ERROR_EMAIL_PHONE_INPUT');
            return false;
        }
        if(0 == $this->_data['dept_id']){
            $this->_errorMessage = L('TEMP_EMP_DEPT').L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        // 检查部门号存不存在
        $Dept = M('Dept');
        if(!($Dept->find($this->_data['dept_id']))){
            $this->_errorMessage = L('EMP_ERROR_DEPT_NOT_EXIST');
            return false;
        }
        $where = array(
            'email'=>$this->_data['email']
        );
        if('M' == $func){
            $where['id'] = array('NEQ',$this->_data['id']);
        }
        if('' != $this->_data['email'] && $this->_EmpModel->where($where)->count() > 0){
            $this->_errorMessage = L('TEMP_EMP_EMP_EMAIL').L('SYSTEM_ERROR_RECORD_EXIST');
            return false;
        }
        $where = array(
            'mobile'=>$this->_data['mobile']
        );
        if('M' == $func){
            $where['id'] = array('NEQ',$this->_data['id']);
        }
        if('' != $this->_data['mobile'] && $this->_EmpModel->where($where)->count() > 0){
            $this->_errorMessage = L('TEMP_EMP_EMP_PHONE').L('SYSTEM_ERROR_RECORD_EXIST');
            return false;
        }
        //检查记录是否重复
        if (count($this->_data['groupid']) != count(array_unique($this->_data['groupid']))) {
            $this->_errorMessage = L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_RECORD_REPEAT');
            return false;
        }
        $groupIdArr = array();
        $Usergroup = M('Usergroup');
        $groupResult = $Usergroup->field(array('id','pid','name'))->order('sort')->select();
        foreach ($groupResult as $key => $value) {
            $groupIdArr[] = $value['id'];
        }
        foreach ($this->_data['groupid'] as $key => $value) {
            if(0 == $value){
                $this->_errorMessage = L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT');
                return false;
            }elseif(!in_array($value,$groupIdArr)){
                $this->_errorMessage = L('SYSTEM_MESSAGE_SCREEN_ERR'.'1001');
                return false;
            }
        }
        if(empty($usergroupData)){
            $this->_errorMessage = L('TEMP_EMP_USERGROUP').L('SYSTEM_ERROR_MUST_INPUT');
            return;
        }
    }

    /**
     * 密码重重置
     */
    public function pwdReset($id,$pwd1,$pwd2){
        //检查当前是否存在相应记录
        if(!is_array($result = $this->_EmpModel->find($id))){
            $this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        //检查当前客户号待复核记录是否已经存在
        if($this->_appoveModel->where(array('reference'=>$id))->count() > 0){
            $this->_errorMessage = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
            return false;
        }
        // 输入检查
        if(empty($pwd1)){
            $this->_errorMessage = L('TEMP_EMP_EMP_NEW_PWD').L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        if(empty($pwd2)){
            $this->_errorMessage = L('TEMP_EMP_EMP_CONFIRM_NEW_PWD').L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        if($pwd1 != $pwd2){
            $this->_errorMessage = L('EMP_ERROR_NOT_SAME_PWD');
            return false;
        }
        if(false === pwdFormatCheck($pwd1)){
            $this->_errorMessage = L('EMP_ERROR_PWD_FORMAT_ERR');
            return false;
        }
        if($result['password'] == system_md5($pwd1)){
            $this->_errorMessage = L('EMP_ERROR_SAME_PWD_OLD_NEW');
            return false;
        }
        if(self::APPOVE_YES === $this->_appoveFlag) {
            //添加待复核记录
            $today = date_to_int('-',date('Y-m-d'));
            //取得当前日期最大记录顺序号
            $seq = $this->_appoveModel->where(array('date'=>$today))->max('seq');
            $appoveData = array(
                'date'	 	=> $today,
                'seq'   	=> ++$seq,
                'maker' 	=> UID,
                'reference' => $id,
                'type'      => self::APPOVE_TYPE,
                'func'      => 'P',
                'time'      => NOW_TIME,
                'content'   => json_encode(array('pwd1'=>system_md5($pwd1),'pwd2'=>system_md5($pwd2))),
            );
            if(!$this->_appoveModel->add($appoveData)){
                $this->_errorMessage = $this->_appoveModel->getError();
                return false;
            }
        }else{
            $data = array(
                'id' => $id,
                'password' => system_md5($pwd1),
                'pwd_change_date' => 0,
            );
            //if(!$this->_EmpModel->where(array('id'=>$id))->setField('password',system_md5($pwd1))){
            if(!$this->_EmpModel->save($data)){
                $this->_errorMessage = $this->_EmpModel->getError();
                return false;
            }
        }
        //写log记录
        LogTools::activeLog("User id is : ".$id);
        return true;
    }

    /**
     * 解除用户登录限制
     * @param $id  用户id
     */
    public function release($id){
        if(empty($id)){
            //$this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            $this->_errorMessage = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        $result = $this->_EmpModel->find($id);
        if(!is_array($result)){
            //$this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            $this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;

        }
        $this->_data = array(
            'id' => $id,
            'login_switch' => 1,
            'pwd_err_count' => 0,
        );
        $this->_EmpModel->startTrans();
        if($this->_EmpModel->save($this->_data)){
            if(self::APPOVE_YES === $this->_appoveFlag){
                $this->_EmpModel->rollback();
                if(false === $this->addAppove('R')){
                    return false;
                }
            }
        }else{
            $this->_errorMessage = $this->_EmpModel->getError();
            return false;
        }
        $this->_EmpModel->commit();
        return true;
    }
    /**
     * 复核记录添加
     * @param $func A-新加,M-修改,P-密码重置
     * @return bool
     */
    private function addAppove($func){
        //添加待复核记录
        $today = date_to_int('-',date('Y-m-d'));
        //取得当前日期最大记录顺序号
        $seq = $this->_appoveModel->where(array('date'=>$today))->max('seq');
        $appoveData = array(
            'date'	 	=> $today,
            'seq'   	=> ++$seq,
            'maker' 	=> UID,
            'type'      => 'EMP',
            'func'      => $func,
            'time'      => NOW_TIME,
            'content'   => json_encode($this->_data),
        );
        if('A' != $func){
            $appoveData['reference'] = $this->_data['id'];
        }
        if(!$this->_appoveModel->add($appoveData)){
            $this->_errorMessage = $this->_appoveModel->getError();
            return false;
        }
        return true;
    }

    /**
     * @param $date  录入记录日期
     * @param $seq   录入记录顺序号
     * @return bool
     */
    public function appove($date,$seq){
        if(!is_array($appResult = $this->_appoveModel->where(array('date'=>$date,'seq'=>$seq))->find())){
            $this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
            return false;
        }
        $this->_EmpModel->startTrans();
        switch ($appResult['func']){
            case 'A':
                $this->_data = json_decode($appResult['content'],true);
                if(false === $this->addRecord()) {
                    $this->_EmpModel->rollback();
                    return false;
                }
                break;
            case 'M':
                $this->_data = json_decode($appResult['content'],true);
                if(false === $this->updateRecord()) {
                    $this->_EmpModel->rollback();
                    return false;
                }
                break;
            case 'P':
                $pwdArr = json_decode($appResult['content'],true);
                //if(!$this->_EmpModel->where(array('id'=>$appResult['reference']))->setField('password',$pwdArr['pwd1'])){
                $data = array(
                    'id' => $appResult['reference'],
                    'password' => $pwdArr['pwd1'],
                    'pwd_change_date' => 0,
                );
                if(!$this->_EmpModel->save($data)){
                    $this->_EmpModel->rollback();
                    $this->_errorMessage = $this->_EmpModel->getError();
                    return false;
                }
                break;
            case 'R':
                $this->_data = json_decode($appResult['content'],true);
                if(!$this->_EmpModel->save($this->_data)) {
                    $this->_EmpModel->rollback();
                    $this->_errorMessage = $this->_EmpModel->getError();
                    return false;
                }
                break;
            case 'D':
                $this->_data = json_decode($appResult['content'],true);
                if(!$this->deleteRecord()) {
                    $this->_EmpModel->rollback();
                    return false;
                }
                break;
            default :
                $this->_errorMessage = L('SYSTEM_ERROR_SYSTEM_ERROR');   //调用错误
                return false;
                break;
        }
        if(!$this->_appoveModel->where(array('date'=>$date,'seq'=>$seq))->delete()){
            $this->_EmpModel->rollback();
            $this->_errorMessage = $this->_appoveModel->getError();
            return false;
        }
        $this->_EmpModel->commit();
        //写log记录
        LogTools::activeLog($appResult);
        return true;
    }

    /**
     * 取得同组及其下属组的所有用户ID
     */
    public function getGroupUser($includeSelf=''){
        //取得当前用户的分组
        $Usergroup = M('Usergroup');
        $UsergroupTotArr = $Usergroup->field('id,pid')->select();
        $Usergroup_user = M('Usergroup_user');
        $usergroupArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
        $childUserGroupArr = array();
        foreach ($usergroupArr as $value){
            $tempArr = getChildTree($UsergroupTotArr,$value);
            $ids = array_column($tempArr, 'id');
            $childUserGroupArr = array_merge($childUserGroupArr,$ids);
            $childUserGroupArr[] = $value;
        }
        //去除重复项
        $childUserGroupArr = array_unique($childUserGroupArr);
        $childUserGroupStr = implode($childUserGroupArr,',');
        //取得当前部门下的所有用户
        $userIDArr = $Usergroup_user->where(array('usergroup_id'=>array('IN',$childUserGroupStr)))->getField('user_id',true);
        $userIDArr = array_unique($userIDArr);
        if(self::INCLUDE_SELF != $includeSelf) {
            //删除当前用户ID,不允许自己复核自己生成的待复核记录
            $key = array_search(UID, $userIDArr);
            if ($key !== false) {
                array_splice($userIDArr, $key, 1);
            }
        }
        return $userIDArr;
    }

    /**
     * 取得错误信息
     */
    public function getError(){
        return $this->_errorMessage;
    }
}