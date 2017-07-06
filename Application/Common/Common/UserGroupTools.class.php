<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/15
 * Time: 18:46
 */

namespace Common\Common;


class UserGroupTools
{
	const APPOVE_YES = 'Y';
	const APPOVE_NO  = 'N';
	const APPOVE_TYPE = 'GROUP';
	private $_errorMessage;
	private $_UsergroupModel;
	private $_appoveModel;
	private $_data;
	private $_appoveFlag;

	public function __construct()
	{
		$this->_UsergroupModel = D('Usergroup');
		$this->_appoveModel = D('Appove');
		$this->_appoveFlag = C('ACCESS_APPOVE');
	}

	/**
	 * 用户组添加
	 */
	public function add($data){
		$this->_data = $data;
		// 启动事务处理
		$this->_UsergroupModel->startTrans();
		if(false === $this->addRecord()){
			$this->_UsergroupModel->rollback();
			return false;
		}
		if(self::APPOVE_YES === $this->_appoveFlag) {
			$this->_UsergroupModel->rollback();
			if (false === $this->addAppove('A')) {
				$this->_UsergroupModel->rollback();
				return false;
			}
		}
		$this->_UsergroupModel->commit();
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
		if ($this->_UsergroupModel->create($this->_data)) {
			$groupId = $this->_UsergroupModel->add();
			if(!$groupId){
				$this->_errorMessage = L('SYSTEM_MESSAGE_ERROR');
				return false;
			}
		} else {
			$this->_errorMessage = $this->_UsergroupModel->getError();
			return false;
		}
		//用户组角色处理
		$groupRoleData = array();
		foreach ($this->_data['roleid'] as $value) {
			$groupRoleData[] = array(
				'role_id'     => $value,
				'usergroup_id' => $groupId,
			);
		}
		if(empty($groupRoleData)){
			$this->_errorMessage = L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT');
			return false;
		}
		// 分组写入处理
		$Role_usergroup = M('Role_usergroup');
		if(!$Role_usergroup->addAll($groupRoleData)){
			$this->_errorMessage = $Role_usergroup->getError();
			return false;
		}
		return true;
	}

	/**
	 * 用户组修改
	 */
	public function update($data){
		$this->_data = $data;
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$this->_data['id']))->count() > 0){
			$this->_errorMessage = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}
		// 启动事务处理
		$this->_UsergroupModel->startTrans();
		if(false === $this->updateRecord()){
			$this->_UsergroupModel->rollback();
			return false;
		}
		if(self::APPOVE_YES === $this->_appoveFlag) {
			$this->_UsergroupModel->rollback();
			if (false === $this->addAppove('M')) {
				$this->_UsergroupModel->rollback();
				return false;
			}
		}
		$this->_UsergroupModel->commit();
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
		// 分组角色处理
		$rolegroupData = array();
		foreach ($this->_data['roleid'] as $value) {
			$rolegroupData[] = array(
				'role_id' => $value,
				'usergroup_id' => $this->_data['id'],
			);
		}
		if(empty($rolegroupData)){
			$this->_errorMessage = L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT');
			return false;
		}

		if($this->_UsergroupModel->create($this->_data)){
			$returnCode = $this->_UsergroupModel->save() ;
			if($returnCode !== 0 && $returnCode !== 1 ){
				$this->_errorMessage = $this->_UsergroupModel->getError();
				return false;
			}
		}else{
			$this->_errorMessage = $this->_UsergroupModel->getError();
			return false;
		}

		// 用户组角色写入处理
		$Role_usergroup = M('Role_usergroup');
		if(false === $Role_usergroup->where(array('usergroup_id'=>$this->_data['id']))->delete()){
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $Role_usergroup->getError();
			return false;
		}
		if(!$Role_usergroup->addAll($rolegroupData)){
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $Role_usergroup->getError();
			return false;
		}
		return true;
	}

	/**
	 * 数据验证
	 */
	private function validation($func){
		if("" == $this->_data['name'] || "" == $this->_data['remark']){
			$this->_errorMessage = L('SYSTEM_ERROR_MUST_INPUT');
			return false;
		}
		//检查名称是否重复
		$where = array(
			'name' => $this->_data['name'],
		);
		if('M' == $func){
			$where['id'] = array('NEQ',$this->_data['id']);
		}
		if(is_array($this->_UsergroupModel->where($where)->find())){
			$this->_errorMessage = L('EMP_ERROR_GROUP_NAME_EXIST');
			return false;
		}
		//检查角色是否输入及重复
		if(empty($this->_data['roleid'])){
			$this->_errorMessage = L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT');
			return false;
		}
		if (count($this->_data['roleid']) != count(array_unique($this->_data['roleid']))) {
			$this->_errorMessage = L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_RECORD_REPEAT');
			return false;
		}
		$Role = M('Role');
		$roleIdArr = $Role->getField('id',true);
		foreach ($this->_data['roleid'] as $value) {
			if(0 == $value){
				$this->_errorMessage = L('TEMP_EMP_ROLE_NAME').L('SYSTEM_ERROR_MUST_INPUT');
				return false;
			}elseif(!in_array($value,$roleIdArr)){
				$this->_errorMessage = L('SYSTEM_MESSAGE_SCREEN_ERR'.'1001');
				return false;
			}
		}
		return true;
	}

	/**
	 * 用户组删除
	 */
	public function delete($id){
		$id = intval($id);
		if(0 == $id){
			$this->_errorMessage = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
			return false;
		}
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$id))->count() > 0){
			$this->_errorMessage = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}
		//检查用户组是否存在
		if(!is_array($groupResult = $this->_UsergroupModel->find($id))){
			$this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
			return false;
		}
		// 启动事务处理
		$this->_UsergroupModel->startTrans();
		$Usergroup_user = M('Usergroup_user');
		//删除用户组与用户的关联关系
		if(false === $Usergroup_user->where(array('usergroup_id'=>$id))->delete()){
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $Usergroup_user->getError();
		}
		//删除用户组与角色的关联关系
		$Role_usergroup = M('Role_usergroup');
		if(false === $Role_usergroup->where(array('usergroup_id'=>$id))->delete()){
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $Role_usergroup->getError();
			return false;
		}
		//清空邮件模板中与该用户组的关联
		$Mailtpl = M('Mailtpl');
		if(false === $Mailtpl->where(array('cc_user_group'=>$id))->setField('cc_user_group',0)){
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $Mailtpl->getError();
			return false;
		}
		//删除用户组
		if($this->_UsergroupModel->delete($id)){
			$this->_UsergroupModel->commit();
			LogTools::activeLog($groupResult);
		}else{
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $this->_UsergroupModel->getError();
			return false;
		}
		return true;
	}

	/**
	 * 复核记录添加
	 * @param $func A-新加,M-修改
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
			'type'      => self::APPOVE_TYPE,
			'func'      => $func,
			'time'      => NOW_TIME,
			'content'   => json_encode($this->_data),
		);
		if('M' == $func){
			$appoveData['reference'] = $this->_data['id'];
		}
		if(!$this->_appoveModel->add($appoveData)){
			$this->_errorMessage = $this->_appoveModel->getError();
			return false;
		}
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
		$this->_UsergroupModel->startTrans();
		switch ($appResult['func']){
			case 'A':
				$this->_data = json_decode($appResult['content'],true);
				if(false === $this->addRecord()) {
					$this->_UsergroupModel->rollback();
					return false;
				}
				break;
			case 'M':
				$this->_data = json_decode($appResult['content'],true);
				if(false === $this->updateRecord()) {
					$this->_UsergroupModel->rollback();
					return false;
				}
				break;
			default :
				$this->_errorMessage = L('SYSTEM_ERROR_SYSTEM_ERROR');   //调用错误
				return false;
				break;
		}
		if(!$this->_appoveModel->where(array('date'=>$date,'seq'=>$seq))->delete()){
			$this->_UsergroupModel->rollback();
			$this->_errorMessage = $this->_appoveModel->getError();
			return false;
		}
		LogTools::activeLog($appResult);
		$this->_UsergroupModel->commit();
		return true;
	}

	/**
	 * 取得错误信息
	 */
	public function getError(){
		return $this->_errorMessage;
	}
}