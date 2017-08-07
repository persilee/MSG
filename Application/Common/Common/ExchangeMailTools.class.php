<?php
/**
 * Created by PhpStorm.
 * User: per
 * Date: 17/6/22
 * Time: 16:48
 */

namespace Common\Common;


class ExchangeMailTools
{
    const MAILTPL_TYPE_EX_RATE              = 'EXCHANGE';
    const MAILTPL_TYPE_SYSERR               = 'SYSERROR';
    const MAILTPL_TYPE_EX_RATE_LESS_ZERO    = 'EXZERO';
    private $_mailTools = null;
    private $_systemEmail = null;
    private $_exRateDate = "";       //汇率上传日期
    private $_exRateTime = "";       //汇率上传时间
    private $_exRateArr = null;      //汇率的所有利率数组
    /**
     * //汇率小于0的数组,格式为:array[客户号]{客户名称,兑换货币-目标货币-系统值-优惠值}
     *  $this->_exRateLessZero[ci_no][name]
     *  $this->_exRateLessZero[ci_no]['ex_rate'][] = array(
     *              'exchange_ccy'     => XXX,
     *              'target_ccy'       => XXX,
     *              'ex_rate'          => XXX,
     *              'spread'           => XXX,
     *  );
     *
     */
    private $_exRateLessZero = array();
    /**
     * @var string 错误信息
     * @access protected
     */
    protected $_errorMsg = "";

    public function __construct()
    {
        //取得当前系统中所有的汇率配置
        $this->_exRateDate = date_to_int('-',date('Y-m-d'));
        $ExRate = M('exrate');
        if(is_array($exRateTplArr = $ExRate->where(array('date'=>$this->_exRateDate))->field(array('exchange_ccy,target_ccy,ex_rate,time'))->select())) {
            $this->_exRateArr = array();
            //转换数组格式为:data[exchange_ccy][target_ccy] => ex_rate
            foreach ($exRateTplArr as $exRateRecord) {
                $this->_exRateArr[$exRateRecord['exchange_ccy']][$exRateRecord['target_ccy']] = floatval($exRateRecord['ex_rate']);
                $this->_exRateTime = $exRateRecord['time'];
            }
        }
        $this->_systemEmail = explode("|",C('SYSTEM_MAIL'));
    }

    /**
     * 批量发送所有汇率通知邮件
     * @param $channel   发送渠道:B - 自动发送,其他即手动发送
     * @return bool
     */
    public function sendExchangeMail($channel="B"){
        //取得所有的汇率模板,并根据模板读取对应的客户列表
        $mailtpl = M('mailtpl');
        //取得汇率小于0的模板文件备用
        $exRateLessZeroMailtplID = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_EX_RATE_LESS_ZERO))->getField('id');
        //取得当前系统中所有汇率邮件模板,以邮件模板搜索关联客户,进行邮件发送处理
        $mailtplArr = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_EX_RATE))->getField('id,cc_user_group',true);
        $Client = M('Client');
        $clientField = array('ci_no','name','ot_name','tpl_priority_lang','email','bcc_email','ex_rate_float');
        $clientMap = array();
        if('B' == $channel){
            $clientMap['auto_email_ex_rate_flag'] = 1;
        }
        foreach ($mailtplArr as $mailtpl_id => $cc_user_group){
            //==================================================
            //清空利率小于0情况的临时数组,用于填充新的数据
            $this->_exRateLessZero = array();
            //==================================================
            //获取客户信息中汇率邮件模板为当前模板的客户记录
            $clientMap['ex_rate_mailtpl'] = $mailtpl_id;
            //获取客户信息中汇率邮件发送时间小于当天的记录
            $clientMap['ex_rate_mailtpl'] = array('LT',date_to_int('-',date('Y-m-d')));
            //获取客户信息中执行时间小于当前时间的记录
            $clientMap['ex_rate_mailtpl'] = array('ELT',date('H:i'));
            $clientArr = $Client->where($clientMap)->field($clientField)->select();
            //如果存在需要发送邮件的客户,且汇率未上传,则发送汇率未上传邮件,并退出方法处理
            if(empty($this->_exRateArr) && !empty($clientArr)){
                $this->exRateNotUploadMail();
                return true;
            }
            //逐一发送客户汇率邮件
            foreach ($clientArr as $clientResult){
                //如果当前客户没有相应的邮件地址,则不对其发送邮件
                if("" != $clientResult['email']) {
                    //对客邮件处理
                    if(false !== $this->clientExchangeMail($mailtpl_id, $clientResult)){
                        //邮件成功发送后,写客户信息中的最后发送时间
                        $Client->where(array('ci_no'=>$clientResult['ci_no']))->setField('ex_rate_send_time',time());
                    }
                }
            }
            //==================================================
            //如果存在客户汇率小于0的情况,则发邮件给汇率模板下的cc_user_group成员
            if(!empty($cc_user_group) && count($this->_exRateLessZero) > 0 && !empty($exRateLessZeroMailtplID)){
                $ex_mail_returnFlag = true;
                //取得当前汇率模板下的抄送用户组下的所有收件用户
                //=========================================================
                $tempLessZeroReceiver = $this->getUsergroupReceiver($cc_user_group);
                //=========================================================
                if($tempLessZeroReceiver){
                    //写表格头
                    $errTableStr = "<table width='100%' border='1'><thead><tr><th>Client No.</th><th>Client Name</th><th>Description</th></tr></thead><tbody>";
                    //写表格体
                    foreach ($this->_exRateLessZero as $key => $value){
                        $errTableStr .= "<tr><td align='center'>".$key."</td>";
                        $errTableStr .= "<td align='center'>".$value['name']."</td>";
                        $errTableStr .= "<td>";
                        foreach ($value['ex_rate'] as $exRateItem){
                            $errTableStr .= "Exchange Currency:".$exRateItem['exchange_ccy']." , Target Currency:".$exRateItem['target_ccy']." , System Exchange rate:".$exRateItem['ex_rate'];
                            if(empty($exRateItem['spread'])){
                                $errTableStr .= ' , Client exchange rate:'.decimalCut($exRateItem['client_ex_rate'],2).'%';
                            }else{
                                $errTableStr .= ' , Spread:'.decimalCut($rateItem['spread'],2).'%';
                            }
                            $errTableStr .= "</br>";
                        }
                        $errTableStr .= "</td></tr>";
                    }
                    $errTableStr .= "</tbody></table>";
                    //组装邮件内容
                    $content = array(
                        'ex_rate_date' => day_format($this->_exRateDate),         //利率上传日期(这个有点多余,因为都是今天)
                        'ex_rate_time' => time_format($this->_exRateTime),        //利率上传时间
                        'content'      => $errTableStr,
                    );
                    $this->_mailTools = new MailTools();
                    $this->_mailTools->prepareMail($exRateLessZeroMailtplID,$content);
                    if(false === $this->_mailTools->sendMail($tempLessZeroReceiver,MailTools::MAIL_SEND_ROUTE_TERMINAL)){
                        //$this->_errorMsg = $this->_mailTools->getError();

                        if (C('IS_SEND_FAIL_MAIL') == 'TRUE') {
                          $mailSendFailReportTools = new MailSendFailReportTools();
                          $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                        }
                        $ex_mail_returnFlag = false;
                        //return false;
                    }
                    //记录邮件LOG
                    MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE,$exRateLessZeroMailtplID,0,$tempLessZeroReceiver,$ex_mail_returnFlag,time(),$this->_mailTools->getMailFile(),$this->_mailTools->getError());
                }
            }
        }
        return true;
    }

    //发送汇率未上传邮件
    private function exRateNotUploadMail(){
        //发送汇率未上传通知邮件
        //===============================================================//
        $mailtpl = M('mailtpl');
        if(is_array($sysErrMailtpl = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_SYSERR))->field('id,cc_user_group')->find())){
            //组装系统错误通知邮件内容
            if(is_array($receiver = explode(';',C('RECEIVER_EMAIL_ADDR_FOR_SYS')))){
                $content = array(
                    'content' => "Exchange rate didn't upload.(Date:".day_format($this->_exRateDate).")",
                );
                $this->_mailTools = new MailTools();
                $this->_mailTools->prepareMail($sysErrMailtpl['id'], $content);
                $ex_mail_returnFlag = true;
                if(false === $this->_mailTools->sendMail($receiver,MailTools::MAIL_SEND_ROUTE_TERMINAL)){
                    $ex_mail_returnFlag = false;
                    //通过ESB发送预警邮件
                    if (C('IS_SEND_FAIL_MAIL') == 'TRUE') {
                      $mailSendFailReportTools = new MailSendFailReportTools();
                      $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                    }
                }
                //记录邮件LOG
                MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE,$sysErrMailtpl['id'],0,$receiver,$ex_mail_returnFlag,time(),$this->_mailTools->getMailFile(),$this->_mailTools->getError());
            }
        }
        return true;
        //===============================================================//
    }

    //发送单个对客汇率邮件
    public function clientExchangeMail($mailtpl_id,$clientResult){
        $clientRateArr = array();    //定义需要发给用户的汇率数组
        //转换首选语言为数组
        $language = explode('-',$clientResult['tpl_priority_lang']);
        //客户信息中保存的汇率信息
        $ex_rate_float_arr = json_decode($clientResult['ex_rate_float'],true);
        //获取汇率参数设置值
        $exRateTools = new ExRateTools();
        $exParameterArr = $exRateTools->getExParameterArr();
        //===定义对客汇率的X轴与Y轴数组===
        $exchangeCcyArr = array();
        $targetCcyArr = array();
        //=============================
        foreach($ex_rate_float_arr as $exchangeCcy_key => $exchangeCcy_item){
            foreach ($exchangeCcy_item as $targetCcy_key => $targetCcy_item){
              if (isset($exParameterArr[$exchangeCcy_key][$targetCcy_key])) {
                if(isset($this->_exRateArr[$exchangeCcy_key][$targetCcy_key])){
                    //计算对客汇率值
                    //如果当前指定的是汇率值,则直接取汇率值为对客汇率
                    if(empty($ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['is_exRate'])) {
                        $exRate = $this->_exRateArr[$exchangeCcy_key][$targetCcy_key] + $ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['value'];
                    }else{
                        $exRate = $ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['value'];
                    }
                    //如果汇率值小于0,则对客汇率值覆盖为0,并写通知文件
                    if($exRate < 0){
                        if(0 == $clientResult['negative_rate']){
                            $exRate = 0.00;
                        }
                        //=====================================
                        //写临时数组,用于发CC_user_group的警告邮件
                        if(empty($ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['is_exRate'])) {
                            $client_ex_rate = $ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['value'];
                        }else{
                            $spread = $ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['value'];
                        }
                        $this->_exRateLessZero[$clientResult['ci_no']]['name'] = $clientResult['name'];
                        $this->_exRateLessZero[$clientResult['ci_no']]['ex_rate'][] = array(
                            'exchange_ccy'         => $exchangeCcy_key,
                            'target_ccy'           => $targetCcy_key,
                            'ex_rate'              => $this->_exRateArr[$exchangeCcy_key][$targetCcy_key],
                            'client_ex_rate'       => $client_ex_rate,
                            'spread'               => $spread,
                        );
                        //=====================================
                    }
                    $clientExRateArr[$exchangeCcy_key][$targetCcy_key] = $exRate;
                    $exchangeCcyArr[] = $exchangeCcy_key;
                    $targetCcyArr[] = $targetCcy_key;
                }elseif(!empty($ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['is_exRate'])){
                    $exRate = $ex_rate_float_arr[$exchangeCcy_key][$targetCcy_key]['value'];
                    //如果汇率值小于0,则对客汇率值覆盖为0,并写通知文件
                    if($exRate < 0){
                        if(0 == $clientResult['negative_rate']){
                            $rate = 0.00;
                        }
                        //=====================================
                        //写临时数组,用于发CC_user_group的警告邮件
                        $this->_exRateLessZero[$clientResult['ci_no']]['name'] = $clientResult['name'];
                        $this->_exRateLessZero[$clientResult['ci_no']]['ex_rate'][] = array(
                            'exchange_ccy'         => $exchangeCcy_key,
                            'target_ccy'           => $targetCcy_key,
                            'ex_rate'              => $this->_exRateArr[$exchangeCcy_key][$targetCcy_key],
                            'client_ex_rate'       => $client_ex_rate."%",
                            'spread'               => '',
                        );
                        //=====================================
                    }
                    $clientExRateArr[$exchangeCcy_key][$targetCcy_key] = $exRate;
                    $exchangeCcyArr[] = $exchangeCcy_key;
                    $targetCcyArr[] = $targetCcy_key;
                }
              }
            }
        }
        //将利率数组转为TABLE格式
        $exchangeCcyArr = array_unique($exchangeCcyArr);
        $targetCcyArr = array_unique($targetCcyArr);
        if(count($exchangeCcyArr) > 0 && count($targetCcyArr) > 0) {
            $exRateTableStr = "<table width='100%' border='1'><thead><tr><td></td>";
            //写表格头
            foreach ($targetCcyArr as $targetCcyValue) {
                $exRateTableStr .= "<th>" . $targetCcyValue . "</th>";
            }
            $exRateTableStr .= "</tr></thead><tbody>";
            //写表格体
            foreach ($exchangeCcyArr as $exchangeCcyValue) {
                $exRateTableStr .= "<tr><td>" . $exchangeCcyValue . "</td>";
                foreach ($targetCcyArr as $targetCcyValue) {
                    if (isset($clientExRateArr[$exchangeCcyValue][$targetCcyValue])) {
                        //如果有设置,则添加数值
                        $exRateTableStr .= "<td align='center'>" . decimalCut($clientExRateArr[$exchangeCcyValue][$targetCcyValue],2) . "%</td>";
                    } else {
                        //如果没有设置,则添加空格
                        $exRateTableStr .= "<td></td>";
                    }
                }
                $exRateTableStr .= "</tr>";
            }
            $exRateTableStr .= "</tbody></table>";
            //组装邮件内容
            $content = array(
                'name_title'            => '',
                'name'                  => $clientResult['name'],               //公司姓名
                'related_staffs'        => $clientResult['ot_name'],             //关联客户名称
                'ex_rate_date'          => day_format($this->_exRateDate),         //汇率上传日期(这个有点多余,因为都是今天)
                'ex_rate_time'          => time_format($this->_exRateTime),        //汇率上传时间
                'ex_rate'               => $exRateTableStr,
                'receiver'              => '',
            );
            $this->_mailTools = new MailTools();
            if(false === $this->_mailTools->prepareMail($mailtpl_id, $content, $language)){
                $this->_errorMsg = $this->_mailTools->getError();
                $returnFlag = false;
            } else {
                //取得客户收邮件地址数组、邮件暗送地址
                $reciverArr = explode(';', $clientResult['email']);
                //获取IT人员收件邮箱地址
                $reciverItArr = explode(';',C('RECEIVER_EMAIL_ADDR_FOR_IT'));

                $returnFlag = true;
                $clientEmailCheck = new ClientEmailCheck();
                if(false === $clientEmailCheck->emailCheck($clientResult['ci_no'],$reciverArr)){
                    $this->_errorMsg = $clientEmailCheck->getError();
                    $returnFlag = false;
                }else{
                    foreach ($reciverArr as $value) {
                        if (false === $this->_mailTools->sendMail($value, MailTools::MAIL_SEND_ROUTE_ESB)) {
                            $this->_errorMsg = $this->_mailTools->getError();
                            $returnFlag = false;
                            //发送报警邮件
                            if (C('IS_SEND_FAIL_MAIL') == 'TRUE') {
                              $mailSendFailReportTools = new MailSendFailReportTools();
                              $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_TERMINAL);
                            }
                        }
                    }
                    foreach ($reciverItArr as $value) {
                        if (false === $this->_mailTools->sendMail($value, MailTools::MAIL_SEND_ROUTE_ESB)) {
                            $this->_errorMsg = $this->_mailTools->getError();
                            $returnFlag = false;
                            //发送报警邮件  暂停
                            if (C('IS_SEND_FAIL_MAIL') == 'TRUE') {
                              $mailSendFailReportTools = new MailSendFailReportTools();
                              $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_TERMINAL);
                            }
                        }
                    }
                }
            }
            //记录邮件LOG
            MaillogTools::mailLog(MaillogTools::EMAIL_OUTSIDE,$mailtpl_id,$clientResult['ci_no'],$clientResult['email'],$returnFlag,time(),$this->_mailTools->getMailFile(),$this->_errorMsg);
            if($returnFlag) {
                //如果对外邮件发送成功,则发送对内邮件
                if ($this->_mailTools->getCcReciver()) {
                    //组装对内邮件内容
                    $content = array(
                        'name_title'         => ' - (' . $clientResult['name'] . ')',
                        'name'               => $clientResult['name'],
                        'related_staffs'     => $clientResult['ot_name'],        //关联客户名称
                        'ex_rate_date'       => day_format($this->_exRateDate),         //利率上传日期(这个有点多余,因为都是今天)
                        'ex_rate_time'       => time_format($this->_exRateTime),        //利率上传时间
                        'ex_rate'            => $exRateTableStr,
                        'receiver'           => 'Receiver : ' . $clientResult['email'],
                    );
                    $this->_mailTools = new MailTools();
                    if ($this->_mailTools->prepareMail($mailtpl_id, $content, $language)) {
                        $ex_mail_returnFlag = true;
                        if (false === $this->_mailTools->sendMail($this->_mailTools->getCcReciver(), MailTools::MAIL_SEND_ROUTE_TERMINAL)) {
                            $ex_mail_returnFlag = false;
                            //发送报警邮件
                            if (C('IS_SEND_FAIL_MAIL') == 'TRUE') {
                              $mailSendFailReportTools = new MailSendFailReportTools();
                              $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                            }
                        }
                    }
                    //记录邮件LOG
                    MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE, $mailtpl_id, $clientResult['ci_no'], $this->_mailTools->getCcReciver(), $ex_mail_returnFlag, time(), $this->_mailTools->getMailFile(), $this->_mailTools->getError());
                }
            }
        }
        return $returnFlag;
    }

    //取得错误信息
    public function getError(){
        return $this->_errorMsg;
    }

    //取当当前用户组下的所有收件人
    private function getUsergroupReceiver($usergropu){
        //取得邮件中抄送的用户组邮件
        $Usergroup_user = M('Usergroup_user');
        if($usergropu && is_array($user_ids = $Usergroup_user->where(array('usergroup_id'=>$usergropu))->getField('user_id',true))){
            $user_ids_str = implode(',',$user_ids);
            $Emp = M('Emp');
            if(is_array($user_mail_arr = $Emp->where(array('id'=>array('IN',$user_ids_str)))->getField('email',true))){
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
