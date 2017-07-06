<?php
/**
 * Created by PhpStorm.
 * User: per
 * Date: 17/6/8
 * Time: 16:48
 */

namespace Common\Common;

use Common\Common\ChromePhp;

class MarketMailTools
{
    const MAILTPL_TYPE_MARKET               = 'MARKET';
    const MAILTPL_TYPE_SYSERR               = 'SYSERROR';
    private $_mailTools = null;
    private $_marketDate = "";       //市场资讯录入日期
    private $_marketTime = "";       //市场资讯录入时间
    /**
     * @var string 错误信息
     * @access protected
     */
    protected $_errorMsg = "";

    public function __construct()
    {
    }

    public function sendMarketMail($market_data, $mailtpl_id, $clientArr)
    {
        //取得所有的利率模板,并根据模板读取对应的客户列表
        $mailtpl = M('mailtpl');
        //取得当前系统中市场资讯邮件模板,以邮件模板搜索关联客户,进行邮件发送处理
        $mailtplArr = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_MARKET))->getField('id,cc_user_group', true);
        $Client = M('Client');
        $clientField = array('ci_no','name','ot_name','tpl_priority_lang','email','bcc_email');
        foreach ($mailtplArr as $mailtpl_id => $cc_user_group) {
            //获取客户信息中利率邮件模板为当前模板的客户记录
            $clientMap['market_mailtpl'] = $mailtpl_id;
            //获取客户信息中利率邮件发送时间小于当天的记录
            // $clientMap['market_send_time'] = array('LT',date_to_int('-', date('Y-m-d')));
            $clientMap['ci_no'] = array('IN',$clientArr);
            $clientArr = $Client->where($clientMap)->field($clientField)->select();
            //逐一发送客户利率邮件
            foreach ($clientArr as $clientResult) {
                //如果当前客户没有相应的邮件地址,则不对其发送邮件

                if ("" != $clientResult['email']) {
                    //对客邮件处理
                    if (false !== $this->clientMarketMail($mailtpl_id, $clientResult, $market_data)) {
                        //邮件成功发送后,写客户信息中的最后发送时间
                        $Client->where(array('ci_no'=>$clientResult['ci_no']))->setField('market_send_time', time());
                    }
                }
            }
        }
        return true;
    }

    //发送单个对客利率邮件
    public function clientMarketMail($mailtpl_id, $clientResult, $market_data)
    {
        //转换首选语言为数组
        $language = explode('-', $clientResult['tpl_priority_lang']);
            //组装邮件内容
            ChromePhp::log($market_data);
        $content = array(
                'name_title'            => '',
                'name'                  => $clientResult['name'],                //公司姓名
                'related_staffs'        => $clientResult['ot_name'],             //关联客户名称
                'market_date'           => day_format($market_data['date']),     //市场资讯录入日期
                'market_time'           => time_format($market_data['time']),    //市场资讯录入时间
                'market_title'          => $market_data['title_en'],
                'market_content'        => $market_data['en_content'],
                'receiver'              => '',
            );
        $this->_mailTools = new MailTools();
        if (false === $this->_mailTools->prepareMail($mailtpl_id, $content, $language)) {
            $this->_errorMsg = $this->_mailTools->getError();
            $returnFlag = false;
        } else {
            //取得客户收邮件地址数组、邮件暗送地址
            $reciverArr = explode(';', $clientResult['email']);
            $returnFlag = true;
            $clientEmailCheck = new ClientEmailCheck();
            if (false === $clientEmailCheck->emailCheck($clientResult['ci_no'], $reciverArr)) {
                $this->_errorMsg = $clientEmailCheck->getError();
                $returnFlag = false;
            } else {
                foreach ($reciverArr as $value) {
                    if (false === $this->_mailTools->sendMail($value, MailTools::MAIL_SEND_ROUTE_ESB)) {
                        $this->_errorMsg = $this->_mailTools->getError();
                        $returnFlag = false;
                            //发送报警邮件
                            $mailSendFailReportTools = new MailSendFailReportTools();
                        $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_TERMINAL);
                    }
                }
            }
        }
            //记录邮件LOG
            MaillogTools::mailLog(MaillogTools::EMAIL_OUTSIDE, $mailtpl_id, $clientResult['ci_no'], $clientResult['email'], $returnFlag, time(), $this->_mailTools->getMailFile(), $this->_errorMsg);
        if ($returnFlag) {
            //如果对外邮件发送成功,则发送对内邮件
                if ($this->_mailTools->getCcReciver()) {
                    //组装对内邮件内容
                    $content = array(
                        'name_title'      => ' - (' . $clientResult['name'] . ')',
                        'name'            => $clientResult['name'],
                        'related_staffs'  => $clientResult['ot_name'],
                        'market_date'     => day_format($market_data['date']),
                        'market_time'     => time_format($market_data['time']),
                        'market_title'    => $market_data['title_en'],
                        'market_content'  => $market_data['en_content'],
                        'receiver'        => 'Receiver : ' . $clientResult['email'],
                    );
                    $this->_mailTools = new MailTools();
                    if ($this->_mailTools->prepareMail($mailtpl_id, $content, $language)) {
                        $inside_mail_returnFlag = true;
                        if (false === $this->_mailTools->sendMail($this->_mailTools->getCcReciver(), MailTools::MAIL_SEND_ROUTE_TERMINAL)) {
                            $inside_mail_returnFlag = false;
                            //发送报警邮件
                            $mailSendFailReportTools = new MailSendFailReportTools();
                            $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                        }
                    }
                    //记录邮件LOG
                    MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE, $mailtpl_id, $clientResult['ci_no'], $this->_mailTools->getCcReciver(), $inside_mail_returnFlag, time(), $this->_mailTools->getMailFile(), $this->_mailTools->getError());
                }
        }

        return $returnFlag;
    }

    //取得错误信息
    public function getError()
    {
        return $this->_errorMsg;
    }

    //取当当前用户组下的所有收件人
    private function getUsergroupReceiver($usergropu)
    {
        //取得邮件中抄送的用户组邮件
        $Usergroup_user = M('Usergroup_user');
        if ($usergropu && is_array($user_ids = $Usergroup_user->where(array('usergroup_id'=>$usergropu))->getField('user_id', true))) {
            $user_ids_str = implode(',', $user_ids);
            $Emp = M('Emp');
            if (is_array($user_mail_arr = $Emp->where(array('id'=>array('IN',$user_ids_str)))->getField('email', true))) {
                $returnReceiver = array();
                foreach ($user_mail_arr as $user_mail) {
                    if (!empty($user_mail)) {
                        $returnReceiver[] = $user_mail . C('MAIL_SUFFIX');
                    }
                }
                return $returnReceiver;
            }
        }
        return null;
    }
}
