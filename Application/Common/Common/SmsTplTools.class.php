<?php
/**
 * Created by PhpStorm.
 * User: per
 * Date: 17/6/12
 * Time: 11:59
 */

namespace Common\Common;

class SmsTplTools {

    private $_returnMsg = "";  //错误信息
    private $_data = "";
    private $_smstplModel = null;

    public function __construct()
    {
        $this->_smstplModel = M('Smstpl');
    }
    // 添加短信模板信息
    public function add($data){
        $this->_data = $data;
        if(!$this->recordProc('A')){
            return false;
        }
        if($this->_smstplModel->add($this->_data)){
            LogTools::activeLog($this->_data);
            return true;
        }else{
            $this->_returnMsg = $this->_smstplModel->getError();
            return false;
        }
    }

    // 修改短信模板信息
    public function update($data){
        $this->_data = $data;
        if($this->_smstplModel->where(array('id'=>$this->_data['id']))->count() < 1){
            $this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        if(!$this->recordProc('M')){
            return false;
        }
        $returnCode = $this->_smstplModel->save($this->_data);
        if(0 === $returnCode){
            $this->_returnMsg  = L('SYSTEM_MESSAGE_NOT_CHANGE');
            return false;
        }elseif(!$returnCode){
            $this->_returnMsg = $this->_smstplModel->getError();
            return false;
        }
        LogTools::activeLog($this->_data);
        return true;
    }

    // 删除客户记录
    public function delete($id){
        if(empty($id)){
            $this->_returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        // 检查记录是否存在
        if(!is_array($this->_data = $this->_smstplModel->find($id))){
            $this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        $this->_smstplModel->startTrans();
        if(!$this->_smstplModel->delete($id)){
            $this->_smstplModel->rollback();
            $this->_returnMsg = $this->_smstplModel->getError();
            return false;
        }
        $this->_smstplModel->commit();
        LogTools::activeLog($this->_data);
        return true;
    }

    //取得记录信息
    public function getRecord($id){
        if(empty($id)){
            $this->_returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        // 检查记录是否存在
        if(!is_array($result = $this->_smstplModel->find($id))){
            $this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        return $result;
    }

    // 输入检查
    private function recordProc($func){
        if(!is_array($this->_data['temp_file'])){
            $this->_returnMsg = L('SMS_ERROR_TEMPLATE_MUST_INPUT_ONE');
            return false;
        }
        if("" == $this->_data['name']){
            $this->_returnMsg = L('TEMP_SMS_TEMPLATE_NAME').','.L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        //检查名称是否重复
        $where = array(
            'name' => $this->_data['name'],
        );
        if('M' == $func){
            $where['id'] = array('NEQ',$this->_data['id']);
        }
        if($this->_smstplModel->where($where)->count() > 0){
            $this->_returnMsg = L('SMS_ERROR_TEMPLATE_NAME_EXIST');
            return false;
        }
        //指定需要填写模板时,模板信息不可为空
        if($this->_data['temp_file']['en'] && "" == $this->_data['en_content']){
            $this->_returnMsg = L('SMS_ERROR_TEMPLATE_DESIGN').':'.L('TEMP_SMS_TEMPLATE_CONTENT_EN');
            return false;
        }
        if($this->_data['temp_file']['zh_s'] && "" == $this->_data['zh_s_content']){
            $this->_returnMsg = L('SMS_ERROR_TEMPLATE_DESIGN').':'.L('TEMP_SMS_TEMPLATE_CONTENT_ZH_S');
            return false;
        }
        if($this->_data['temp_file']['zh_t'] && "" == $this->_data['zh_t_content']){
            $this->_returnMsg = L('SMS_ERROR_TEMPLATE_DESIGN').':'.L('TEMP_SMS_TEMPLATE_CONTENT_ZH_T');
            return false;
        }
        //如果没有指定需要填写的模板,则清空信息
        if(!$this->_data['temp_file']['en']){
            $this->_data['en_content'] = "";
        }
        if(!$this->_data['temp_file']['zh_s']){
            $this->_data['zh_s_content'] = "";
        }
        if(!$this->_data['temp_file']['zh_t']){
            $this->_data['zh_t_content'] = "";
        }
        return true;
    }

    //取得相应TYPE下的邮件模板
    public function getMailtplArr($type){
        return $this->_mailtplModel->where(array('type'=>$type))->getField('id,name',true);
    }
    // 取得验证错误信息
    public function getError(){
        return $this->_returnMsg;
    }

}
