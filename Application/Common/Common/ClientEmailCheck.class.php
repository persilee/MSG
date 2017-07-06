<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/30
 * Time: 15:32
 */

namespace Common\Common;


class ClientEmailCheck
{
    private $_errorMessage = "";
    private $_clientMailArr = null;
    private $_clientModel = null;

    public function __construct()
    {
        $this->_clientModel = D('Client');
    }

    //客户地址与客户ID映射关系检查
    public function emailCheck($ci_no,$email)
    {

        if(empty($ci_no) || empty($email)){
            $this->_errorMessage = L('SYSTEM_ERROR_SYSTEM_ERROR');
            return false;
        }
        $clientEmail = $this->_clientModel->where(array('ci_no'=>$ci_no))->getField('email');
        if($clientEmail){
            $this->_clientMailArr = explode(';',$clientEmail);
            if(is_array($email)){
                foreach($email as $value){
                    if(!in_array($value,$this->_clientMailArr)){
                        $this->_errorMessage = L('CLIENT_EMAIL_NOT_BELONG_CLIENT');
                        return false;
                    }
                }
            }else{
                $this->_errorMessage = L('CLIENT_EMAIL_NOT_BELONG_CLIENT');
                return false;
            }
        }else{
            $this->_errorMessage = L('CLIENT_EMAIL_NOT_BELONG_CLIENT');
            return false;
        }
        return true;
    }

    //取得错误信息
    public function getError(){
        return $this->_errorMessage;
    }
}