<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/14
 * Time: 17:20
 */

namespace Common\Common\EsbSystem;


class EsbEmail
{
    private $_errorMessage;

    public function send($content,$receiver,$bcc_receiver,$cc_receiver){
        $receiverStr = "";
        if(is_string($receiver)){
            $receiverStr = $receiver;
        }elseif(is_array($receiver)){
            $receiverStr = implode(';',$receiver);
        }
        $esbRequest = new EsbRequest();
        if(false === $esbRequest->request(EsbRequest::CHANNEL_EMAIL,$receiverStr,$content)){
            $this->_errorMessage = $esbRequest->getError();
            return false;
        }
        return true;
    }

    public function getError(){
        return $this->_errorMessage;
    }
}
