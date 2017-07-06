<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/15
 * Time: 18:46
 */

namespace Common\Common;


class AccessTools
{
    const APPOVE_YES  = 'Y';
    const APPOVE_TYPE = 'ACCESS';
    const FUNC_ACCESS = 'C';
    private $_errorMessage;
    private $_AccessModel;
    private $_NodeModel;
    private $_RoleModel;
    private $_appoveModel;
    private $_appoveFlag;
    private $_data;

    public function __construct()
    {
        $this->_AccessModel = D('Access');
        $this->_NodeModel = D('Node');
        $this->_RoleModel = D('Role');
        $this->_appoveModel = D('Appove');
        $this->_appoveFlag = C('ACCESS_APPOVE');
    }

    /**
     * 用户添加
     */
    public function access($roleid,$accessNode){
        if(empty($roleid)){
            $this->_errorMessage = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        if(is_array($this->_RoleModel->find($roleid))){
            //检查当前客户号待复核记录是否已经存在
            if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$roleid))->count() > 0){
                $this->_errorMessage = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
                return false;
            }
        }else{
            $this->_errorMessage = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        $nodeIdArr = $this->_NodeModel->getField('id',true);
        $this->_data = array();
        foreach ($accessNode as $key => $value) {
            if (0 == $value) {
                $this->_errorMessage = L('SYSTEM_MESSAGE_SCREEN_ERR') . "1001";
                return false;
            } elseif (!in_array($value, $nodeIdArr)) {
                $this->_errorMessage = L('SYSTEM_MESSAGE_SCREEN_ERR') . "1002";
                return false;
            }
            $this->_data[] = array(
                'role_id' => $roleid,
                'node_id' => $value,
                'level' => $this->_NodeModel->where(array('id' => $value))->getField('level'),
            );
        }
        // 启动事务
        $this->_AccessModel->startTrans();
        if(false === $this->updateData($roleid)){
            $this->_AccessModel->rollback();
            return false;
        }
        if(self::APPOVE_YES === $this->_appoveFlag) {
            $this->_AccessModel->rollback();
            if (false === $this->addAppove($roleid)) {
                $this->_AccessModel->rollback();
                return false;
            }
        }
        $this->_AccessModel->commit();
        //记录log信息
        LogTools::activeLog($this->_data);
        return true;
    }

    /**
     * 数据更新
     */
    private function updateData($roleid){
        // 删除原有访问权限记录
        if (false === $this->_AccessModel->where(array('role_id' => $roleid))->delete()) {
            $this->_errorMessage = $this->_AccessModel->getError();
            return false;
        }
        // 添加新访问权限记录
        if (count($this->_data) > 0) {
            if ($this->_AccessModel->addAll($this->_data)) {
                return true;
            }else{
                $this->_errorMessage = $this->_AccessModel->getError();
                return false;
            }
        }
    }

    /**
     * 复核记录添加
     * @return bool
     */
    private function addAppove($roleid){
        //添加待复核记录
        $today = date_to_int('-',date('Y-m-d'));
        //取得当前日期最大记录顺序号
        $seq = $this->_appoveModel->where(array('date'=>$today))->max('seq');
        $appoveData = array(
            'date'	 	=> $today,
            'seq'   	=> ++$seq,
            'maker' 	=> UID,
            'type'      => self::APPOVE_TYPE,
            'func'      => self::FUNC_ACCESS,
            'reference' => $roleid,
            'time'      => NOW_TIME,
            'content'   => json_encode($this->_data),
        );
        if($this->_appoveModel->add($appoveData)){
            return true;
        }else{
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
        $this->_AccessModel->startTrans();
        $this->_data = json_decode($appResult['content'],true);
        if(false === $this->updateData($appResult['reference'])) {
            $this->_AccessModel->rollback();
            return false;
        }
        if($this->_appoveModel->where(array('date'=>$date,'seq'=>$seq))->delete()){
            $this->_AccessModel->commit();
            //记录log信息
            LogTools::activeLog($appResult);
            return true;
        }else{
            $this->_AccessModel->rollback();
            $this->_errorMessage = $this->_appoveModel->getError();
            return false;
        }
    }

    /**
     * 取得错误信息
     */
    public function getError(){
        return $this->_errorMessage;
    }
}