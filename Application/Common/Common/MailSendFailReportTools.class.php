<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/9/23
 * Time: 16:48
 */

namespace Common\Common;


class MailSendFailReportTools
{
    const MAILTPL_TYPE_SYSERR               = 'SYSERROR';
    const MAIL_SEND_ROUTE_TERMINAL          = 'TERMINAL';
    const MAIL_SEND_ROUTE_ESB               = 'ESB';

    public function send($route){
        $mailtpl = M('mailtpl');
        if(is_array($sysErrMailtpl = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_SYSERR))->field('id')->find())){
            //组装系统错误通知邮件内容
            if(is_array($receiver = explode(';',C('RECEIVER_EMAIL_ADDR_FOR_SYS')))){
                if($route == self::MAIL_SEND_ROUTE_TERMINAL) {
                    $content = array('content' => "Email send error (ESB)");
                }else{
                    $content = array('content' => "Email send error (TERMINAL)");
                }
                $this->_mailTools = new MailTools();
                $this->_mailTools->prepareMail($sysErrMailtpl[id], $content);
                if($route == self::MAIL_SEND_ROUTE_TERMINAL){
                    return $this->_mailTools->sendMail($receiver,MailTools::MAIL_SEND_ROUTE_TERMINAL);
                }elseif($route == self::MAIL_SEND_ROUTE_ESB){
                    return $this->_mailTools->sendMail($receiver,MailTools::MAIL_SEND_ROUTE_ESB);
                }else{
                    return false;
                }
            }
        }
    }
}