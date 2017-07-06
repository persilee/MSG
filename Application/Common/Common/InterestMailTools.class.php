<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/4
 * Time: 16:48
 */

namespace Common\Common;
use Common\Common\ChromePhp;

class InterestMailTools
{
    const MAILTPL_TYPE_INST_RATE            = 'INTEREST';
    const MAILTPL_TYPE_SYSERR               = 'SYSERROR';
    const MAILTPL_TYPE_INST_RATE_LESS_ZERO  = 'INSTZERO';
    private $_mailTools = null;
    private $_systemEmail = null;
    private $_rateDate = "";       //利率上传日期
    private $_rateTime = "";       //利率上传时间
    private $_rateArr = null;      //上传的所有利率数组
    /**
     * //利率小于0的数组,格式为:array[客户号]{客户名称,存期-货币-系统值-优惠值}
     *  $this->_rateLessZero[ci_no][name]
     *    $this->_rateLessZero[ci_no]['rate'][] = array(
     *                'tenor'     => XXX,
     *                'ccy'       => XXX,
     *                'rate'      => XXX,
     *                'spread'    => XXX,
     *    );
     *
     */
    private $_rateLessZero = array();
    /**
     * @var string 错误信息
     * @access protected
     */
    protected $_errorMsg = "";

    public function __construct()
    {
        //取得当前系统中所有的利率配置
        $this->_rateDate = date_to_int('-',date('Y-m-d'));
        $Rate = M('Rate');
        if(is_array($rateTplArr = $Rate->where(array('date'=>$this->_rateDate))->field(array('tenor,ccy,rate,time'))->select())) {
            $this->_rateArr = array();
            //转换数组格式为:data[tenor][ccy] => rete
            foreach ($rateTplArr as $rateRecord) {
                $this->_rateArr[$rateRecord['tenor']][$rateRecord['ccy']] = floatval($rateRecord['rate']);
                $this->_rateTime = $rateRecord['time'];
            }
        }
        $this->_systemEmail = explode("|",C('SYSTEM_MAIL'));
    }

    /**
     * 批量发送所有利率通知邮件
     * @param $channel   发送渠道:B - 自动发送,其他即手动发送
     * @return bool
     */
    public function sendInterestMail($channel="B"){
        //取得所有的利率模板,并根据模板读取对应的客户列表
        $mailtpl = M('mailtpl');
        //取得利率小于0的模板文件备用
        $rateLessZeroMailtplID = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_INST_RATE_LESS_ZERO))->getField('id');
        //取得当前系统中所有利率邮件模板,以邮件模板搜索关联客户,进行邮件发送处理
        $mailtplArr = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_INST_RATE))->getField('id,cc_user_group',true);

        $Client = M('Client');
        $clientField = array('ci_no','name','ot_name','tpl_priority_lang','email','bcc_email','rate_float','negative_rate');
        $clientMap = array();
        if('B' == $channel){
            $clientMap['auto_email_flag'] = 1;
        }
        foreach ($mailtplArr as $mailtpl_id => $cc_user_group){
            //==================================================
            //清空利率小于0情况的临时数组,用于填充新的数据
            $this->_rateLessZero = array();
            //==================================================
            //获取客户信息中利率邮件模板为当前模板的客户记录
            $clientMap['inst_rate_mailtpl'] = $mailtpl_id;
            //获取客户信息中利率邮件发送时间小于当天的记录
            $clientMap['inst_send_time'] = array('LT',date_to_int('-',date('Y-m-d')));
            //获取客户信息中执行时间小于当前时间的记录
            $clientMap['mail_plan_time'] = array('ELT',date('H:i'));
            $clientArr = $Client->where($clientMap)->field($clientField)->select();
            //如果存在需要发送邮件的客户,且利率未上传,则发送利率未上传邮件,并退出方法处理
            if(empty($this->_rateArr) && !empty($clientArr)){
                $this->instRateNotUploadMail();
                return true;
            }
            //逐一发送客户利率邮件
            foreach ($clientArr as $clientResult){
                //如果当前客户没有相应的邮件地址,则不对其发送邮件
                if("" != $clientResult['email']) {
                    //对客邮件处理
                    if(false !== $this->clientInterestMail($mailtpl_id, $clientResult)){
                        //邮件成功发送后,写客户信息中的最后发送时间
                        $Client->where(array('ci_no'=>$clientResult['ci_no']))->setField('inst_send_time',time());
                    }
                }
            }
            //==================================================
            //如果存在客户利率小于0的情况,则发邮件给利率模板下的cc_user_group成员
            if(!empty($cc_user_group) && count($this->_rateLessZero) > 0 && !empty($rateLessZeroMailtplID)){
                $inside_mail_returnFlag = true;
                //取得当前利率模板下的抄送用户组下的所有收件用户
                //=========================================================
                $tempLessZeroReceiver = $this->getUsergroupReceiver($cc_user_group);
                //=========================================================
                if($tempLessZeroReceiver){
                    //写表格头
                    $errTableStr = "<table width='100%' border='1'><thead><tr><th>Client No.</th><th>Client Name</th><th>Description</th></tr></thead><tbody>";
                    //写表格体
                    foreach ($this->_rateLessZero as $key => $value){
                        $errTableStr .= "<tr><td align='center'>".$key."</td>";
                        $errTableStr .= "<td align='center'>".$value['name']."</td>";
                        $errTableStr .= "<td>";
                        foreach ($value['rate'] as $rateItem){
                            $errTableStr .= "Tenor:".$rateItem['tenor']." , Currency:".$rateItem['ccy']." , System rate:".$rateItem['rate'];
                            if(empty($rateItem['spread'])){
                                $errTableStr .= ' , Client rate:'.decimalCut($rateItem['client_rate'],2).'%';
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
                        'rate_date' => day_format($this->_rateDate),         //利率上传日期(这个有点多余,因为都是今天)
                        'rate_time' => time_format($this->_rateTime),        //利率上传时间
                        'content'      => $errTableStr,
                    );
                    $this->_mailTools = new MailTools();
                    $this->_mailTools->prepareMail($rateLessZeroMailtplID,$content);
                    if(false === $this->_mailTools->sendMail($tempLessZeroReceiver,MailTools::MAIL_SEND_ROUTE_TERMINAL)){
                        //$this->_errorMsg = $this->_mailTools->getError();
                        //发送警告邮件  暂停
                        // $mailSendFailReportTools = new MailSendFailReportTools();
                        // $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                        $inside_mail_returnFlag = false;
                        //return false;
                    }
                    //记录邮件LOG
                    MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE,$rateLessZeroMailtplID,0,$tempLessZeroReceiver,$inside_mail_returnFlag,time(),$this->_mailTools->getMailFile(),$this->_mailTools->getError());
                }
            }
        }
        return true;
    }

    //发送利率未上传邮件
    private function instRateNotUploadMail(){
        //发送利率未上传通知邮件
        //===============================================================//
        $mailtpl = M('mailtpl');
        if(is_array($sysErrMailtpl = $mailtpl->where(array('type'=>self::MAILTPL_TYPE_SYSERR))->field('id,cc_user_group')->find())){
            //组装系统错误通知邮件内容
            if(is_array($receiver = explode(';',C('RECEIVER_EMAIL_ADDR_FOR_SYS')))){
                $content = array(
                    'content' => "Interest rate didn't upload.(Date:".day_format($this->_rateDate).")",
                );
                $this->_mailTools = new MailTools();
                $this->_mailTools->prepareMail($sysErrMailtpl['id'], $content);
                $inside_mail_returnFlag = true;
                dump($receiver);
                if(false === $this->_mailTools->sendMail($receiver,MailTools::MAIL_SEND_ROUTE_TERMINAL)){
                    $inside_mail_returnFlag = false;
                    //通过ESB发送预警邮件  暂停
                    // $mailSendFailReportTools = new MailSendFailReportTools();
                    // $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                }
                //记录邮件LOG
                MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE,$sysErrMailtpl['id'],0,$receiver,$inside_mail_returnFlag,time(),$this->_mailTools->getMailFile(),$this->_mailTools->getError());
            }
        }
        return true;
        //===============================================================//
    }

    //发送单个对客利率邮件
    public function clientInterestMail($mailtpl_id,$clientResult){
        $clientRateArr = array();    //定义需要发给用户的利率数组
        //转换首选语言为数组
        $language = explode('-',$clientResult['tpl_priority_lang']);
        //客户信息中保存的利率信息
        $rate_float_arr = json_decode($clientResult['rate_float'],true);
        //===定义对客利率的X轴与Y轴数组===
        $tonerArr = array();
        $ccyArr = array();
        //=============================
        foreach($rate_float_arr as $tenor_key => $tenor_item){
            foreach ($tenor_item as $ccy_key => $ccy_item){
                if(isset($this->_rateArr[$tenor_key][$ccy_key])){
                    //计算对客利率值
                    //如果当前指定的是利率值,则直接取利率值为对客利率
                    if(empty($rate_float_arr[$tenor_key][$ccy_key]['is_rate'])) {
                        $rate = $this->_rateArr[$tenor_key][$ccy_key] + $rate_float_arr[$tenor_key][$ccy_key]['value'];
                    }else{
                        $rate = $rate_float_arr[$tenor_key][$ccy_key]['value'];
                    }
                    //如果利率值小于0,则对客利率值覆盖为0,并写通知文件
                    if($rate < 0){
                        if(0 == $clientResult['negative_rate']){
                            $rate = 0.00;
                        }
                        //=====================================
                        //写临时数组,用于发CC_user_group的警告邮件
                        if(empty($rate_float_arr[$tenor_key][$ccy_key]['is_rate'])) {
                            $client_rate = $rate_float_arr[$tenor_key][$ccy_key]['value'];
                        }else{
                            $spread = $rate_float_arr[$tenor_key][$ccy_key]['value'];
                        }
                        $this->_rateLessZero[$clientResult['ci_no']]['name'] = $clientResult['name'];
                        $this->_rateLessZero[$clientResult['ci_no']]['rate'][] = array(
                            'tenor'         => $tenor_key,
                            'ccy'           => $ccy_key,
                            'rate'          => $this->_rateArr[$tenor_key][$ccy_key],
                            'client_rate'   => $client_rate,
                            'spread'        => $spread,
                        );
                        //=====================================
                    }
                    $clientRateArr[$tenor_key][$ccy_key] = $rate;
                    $tonerArr[] = $tenor_key;
                    $ccyArr[] = $ccy_key;
                }elseif(!empty($rate_float_arr[$tenor_key][$ccy_key]['is_rate'])){
                    $rate = $rate_float_arr[$tenor_key][$ccy_key]['value'];
                    //如果利率值小于0,则对客利率值覆盖为0,并写通知文件
                    if($rate < 0){
                        if(0 == $clientResult['negative_rate']){
                            $rate = 0.00;
                        }
                        //=====================================
                        //写临时数组,用于发CC_user_group的警告邮件
                        $this->_rateLessZero[$clientResult['ci_no']]['name'] = $clientResult['name'];
                        $this->_rateLessZero[$clientResult['ci_no']]['rate'][] = array(
                            'tenor'         => $tenor_key,
                            'ccy'           => $ccy_key,
                            'rate'          => $this->_rateArr[$tenor_key][$ccy_key],
                            'client_rate'   => $client_rate."%",
                            'spread'        => '',
                        );
                        //=====================================
                    }
                    $clientRateArr[$tenor_key][$ccy_key] = $rate;
                    $tonerArr[] = $tenor_key;
                    $ccyArr[] = $ccy_key;
                }
            }
        }
        //将利率数组转为TABLE格式
        $tonerArr = array_unique($tonerArr);
        $ccyArr = array_unique($ccyArr);
        if(count($tonerArr) > 0 && count($ccyArr) > 0) {
            $rateTableStr = "<table width='100%' border='1'><thead><tr><td></td>";
            //写表格头
            foreach ($ccyArr as $ccyValue) {
                $rateTableStr .= "<th>" . $ccyValue . "</th>";
            }
            $rateTableStr .= "</tr></thead><tbody>";
            //写表格体
            foreach ($tonerArr as $tonerValue) {
                $rateTableStr .= "<tr><td>" . $tonerValue . "</td>";
                foreach ($ccyArr as $ccyValue) {
                    if (isset($clientRateArr[$tonerValue][$ccyValue])) {
                        //如果有设置,则添加数值
                        $rateTableStr .= "<td align='center'>" . decimalCut($clientRateArr[$tonerValue][$ccyValue],2) . "%</td>";
                    } else {
                        //如果没有设置,则添加空格
                        $rateTableStr .= "<td></td>";
                    }
                }
                $rateTableStr .= "</tr>";
            }
            $rateTableStr .= "</tbody></table>";
            //组装邮件内容
            $content = array(
                'name_title'            => '',
                'name'                  => $clientResult['name'],               //公司姓名
                'related_staffs'        => $clientResult['ot_name'],             //关联客户名称
                'rate_date'             => day_format($this->_rateDate),         //利率上传日期(这个有点多余,因为都是今天)
                'rate_time'             => time_format($this->_rateTime),        //利率上传时间
                'rate'                  => $rateTableStr,
                'receiver'               => '',
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
                            //发送报警邮件 暂停
                            // $mailSendFailReportTools = new MailSendFailReportTools();
                            // $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_TERMINAL);
                        }
                    }
                    foreach ($reciverItArr as $value) {
                        if (false === $this->_mailTools->sendMail($value, MailTools::MAIL_SEND_ROUTE_ESB)) {
                            $this->_errorMsg = $this->_mailTools->getError();
                            $returnFlag = false;
                            //发送报警邮件  暂停
                            // $mailSendFailReportTools = new MailSendFailReportTools();
                            // $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_TERMINAL);
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
                        'name_title' => ' - (' . $clientResult['name'] . ')',
                        'name' => $clientResult['name'],
                        'related_staffs' => $clientResult['ot_name'],        //关联客户名称
                        'rate_date' => day_format($this->_rateDate),         //利率上传日期(这个有点多余,因为都是今天)
                        'rate_time' => time_format($this->_rateTime),        //利率上传时间
                        'rate' => $rateTableStr,
                        'receiver' => 'Receiver : ' . $clientResult['email'],
                    );
                    $this->_mailTools = new MailTools();
                    if ($this->_mailTools->prepareMail($mailtpl_id, $content, $language)) {
                        $inside_mail_returnFlag = true;
                        if (false === $this->_mailTools->sendMail($this->_mailTools->getCcReciver(), MailTools::MAIL_SEND_ROUTE_TERMINAL)) {
                            $inside_mail_returnFlag = false;
                            //发送报警邮件 暂停
                            // $mailSendFailReportTools = new MailSendFailReportTools();
                            // $mailSendFailReportTools->send(MailSendFailReportTools::MAIL_SEND_ROUTE_ESB);
                        }
                    }
                    //记录邮件LOG
                    MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE, $mailtpl_id, $clientResult['ci_no'], $this->_mailTools->getCcReciver(), $inside_mail_returnFlag, time(), $this->_mailTools->getMailFile(), $this->_mailTools->getError());
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
