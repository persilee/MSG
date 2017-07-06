<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/7/20
 * Time: 16:59
 */

namespace Common\Common;

class MailTplTools {

    private $_returnMsg = "";  //错误信息
    private $_data = "";
    private $_mailtplModel = null;

    public function __construct()
    {
        $this->_mailtplModel = M('Mailtpl');
    }

    // 添加邮件模板信息
    public function add($data){
        $this->_data = $data;
        if(!$this->recordProc('A')){
            return false;
        }
//        $Mailtpl = M('Mailtpl');
        if($this->_mailtplModel->add($this->_data)){
            LogTools::activeLog($this->_data);
            return true;
        }else{
            $this->_returnMsg = $this->_mailtplModel->getError();
            return false;
        }
    }

    // 修改邮件模板信息
    public function update($data){
        $this->_data = $data;
//        $Mailtpl = M('Mailtpl');
        if($this->_mailtplModel->where(array('id'=>$this->_data['id']))->count() < 1){
            $this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        if(!$this->recordProc('M')){
            return false;
        }
        $returnCode = $this->_mailtplModel->save($this->_data);
        if(0 === $returnCode){
            $this->_returnMsg  = L('SYSTEM_MESSAGE_NOT_CHANGE');
            return false;
        }elseif(!$returnCode){
            $this->_returnMsg = $this->_mailtplModel->getError();
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
//        $Mailtpl = D('Mailtpl');
        // 检查记录是否存在
        if(!is_array($this->_data = $this->_mailtplModel->find($id))){
            $this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        $this->_mailtplModel->startTrans();
        //清空客户表中关联的该模板ID
        $Client = M('Client');
        //清空利率关联模板
        if(false === $Client->where(array('inst_rate_mailtpl'=>$id))->setField('inst_rate_mailtpl',0)){
            $this->_mailtplModel->rollback();
            $this->_returnMsg = $Client->getError();
            return false;
        }
        if(!$this->_mailtplModel->delete($id)){
            $this->_mailtplModel->rollback();
            $this->_returnMsg = $this->_mailtplModel->getError();
            return false;
        }
        $this->_mailtplModel->commit();
        LogTools::activeLog($this->_data);
        return true;
    }

    //取得记录信息
    public function getRecord($id){
        if(empty($id)){
            $this->_returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
//        $Mailtpl = D('Mailtpl');
        // 检查记录是否存在
        if(!is_array($result = $this->_mailtplModel->find($id))){
            $this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }else{
            if("" != $result['en_content']){
                $result['en_content'] = json_decode($result['en_content'],true);
            }
            if("" != $result['zh_s_content']){
                $result['zh_s_content'] = json_decode($result['zh_s_content'],true);
            }
            if("" != $result['zh_t_content']){
                $result['zh_t_content'] = json_decode($result['zh_t_content'],true);
            }
        }
        return $result;
    }

    // 输入检查
    private function recordProc($func){
        //将TYPE转为大写
//        $this->_data['type'] = strtoupper($this->_data['type']);
        //检查TYPE是否在参数设定范围内
        if(empty($this->_data['type'])){
            $this->_returnMsg = L('TEMP_MAIL_TEMPLATE_TYPE').','.L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }elseif(!array_key_exists($this->_data['type'],C('MAIL_TYPE'))){
            $this->_returnMsg = L('TEMP_MAIL_TEMPLATE_TYPE').','.L('SYSTEM_ERROR_BEYOND_OPTION');
            return false;
        }
        //输入检查
//        if(empty($this->_data['id'])){
//            $this->_returnMsg = L('TEMP_MAIL_TEMPLATE_ID').','.L('SYSTEM_ERROR_MUST_INPUT');
//            return false;
//        }elseif(false === RegexTools::regex('',$this->_data['id'])){
//            $this->_returnMsg = L('TEMP_MAIL_TEMPLATE_ID').','.L('SYSTEM_ERROR_FORMAT');
//            return false;
//        }
        if(!is_array($this->_data['temp_file'])){
            $this->_returnMsg = L('MAIL_ERROR_TEMPLATE_MUST_INPUT_ONE');
            return false;
        }
        if("" == $this->_data['name']){
            $this->_returnMsg = L('TEMP_MAIL_TEMPLATE_NAME').','.L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        if("" == $this->_data['type']){
            $this->_returnMsg = L('TEMP_MAIL_TEMPLATE_TYPE').','.L('SYSTEM_ERROR_MUST_INPUT');
            return false;
        }
        if(0 != $this->_data['cc_user_group']){
            $Usergroup = M('Usergroup');
            if(!is_array($Usergroup->find($this->_data['cc_user_group']))){
                $this->_returnMsg = L('TEMP_MAIL_CC_USER_GROUP').','.L('SYSTEM_ERROR_RECORD_NOT_EXIST');
                return false;
            }
        }
//        $Mailtpl = M('Mailtpl');
        //检查名称是否重复
        $where = array(
            'name' => $this->_data['name'],
        );
        if('M' == $func){
            $where['id'] = array('NEQ',$this->_data['id']);
        }
        if($this->_mailtplModel->where($where)->count() > 0){
            $this->_returnMsg = L('MAIL_ERROR_TEMPLATE_NAME_EXIST');
            return false;
        }
        //检查TYPE是否重复
//        $where = array(
//            'type' => $this->_data['type'],
//        );
//        if('M' == $func){
//            $where['id'] = array('NEQ',$this->_data['id']);
//        }
//        if($Mailtpl->where($where)->count() > 0){
//            $this->_returnMsg = L('MAIL_ERROR_TEMPLATE_TYPE_EXIST');
//            return false;
//        }
        //指定需要填写模板时,模板信息不可为空
        if($this->_data['temp_file']['en'] && ("" == $this->_data['title_en'] || "" == $this->_data['en_content'])){
            $this->_returnMsg = L('MAIL_ERROR_TEMPLATE_DESIGN').':'.L('TEMP_MAIL_TEMPLATE_EN');
            return false;
        }
        if($this->_data['temp_file']['zh_s'] && ("" == $this->_data['title_zh_s'] || "" == $this->_data['zh_s_content'])){
            $this->_returnMsg = L('MAIL_ERROR_TEMPLATE_DESIGN').':'.L('TEMP_MAIL_TEMPLATE_ZH_S');
            return false;
        }
        if($this->_data['temp_file']['zh_t'] && ("" == $this->_data['title_zh_t'] || "" == $this->_data['zh_t_content'])){
            $this->_returnMsg = L('MAIL_ERROR_TEMPLATE_DESIGN').':'.L('TEMP_MAIL_TEMPLATE_ZH_T');
            return false;
        }
        //如果没有指定需要填写的模板,则清空信息
        if(!$this->_data['temp_file']['en']){
            $this->_data['title_en'] = "";
            $this->_data['en_content'] = "";
        }
        if(!$this->_data['temp_file']['zh_s']){
            $this->_data['title_zh_s'] = "";
            $this->_data['zh_s_content'] = "";
        }
        if(!$this->_data['temp_file']['zh_t']){
            $this->_data['title_zh_t'] = "";
            $this->_data['zh_t_content'] = "";
        }
        //将模板内容转为JSON格式
        if("" != $this->_data['en_content']){
            $this->_data['en_content'] = json_encode($this->_data['en_content']);
        }
        if("" != $this->_data['zh_s_content']){
            $this->_data['zh_s_content'] = json_encode($this->_data['zh_s_content']);
        }
        if("" != $this->_data['zh_t_content']){
            $this->_data['zh_t_content'] = json_encode($this->_data['zh_t_content']);
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
