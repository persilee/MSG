<?php
namespace Common\Common;
use Common\Common\EsbSystem\EsbEmail;

Vendor('PHPMailer.PHPMailerAutoload');
/**
 * Created by Sublime.
 * User: Hipr
 * Date: 2015/10/25
 * Time: 10:19
 */

class MailTools{
    const MAIL_SEND_ROUTE_ESB = 'ESB';
    const MAIL_SEND_ROUTE_TERMINAL = "TERMINAL";
    const ESB_SYSTEM_SWITCH_ON = "ON";
    protected $_mailTitle = "";
    protected $_mailFile = "";
    protected $_mailContent = "";
    protected $_PHPMailer = null;
    protected $_cc_receiver = null;
    protected $_mailtplModel = null;
    /**
    * @var string 错误信息
    * @access protected
    */
    protected $_errorMsg = "";

    public function __construct()
    {
        $this->_mailtplModel = M('Mailtpl');
    }

    /**
    * 取得邮箱配置、根据业务类型取得模板文件、根据参数内容替换模板文件进行邮件发送
    * @access public
    * @param string       $mail_id     邮件模板ID
    * @param array       $content     邮件业务内容(键值对,键为邮件体参数,值为需要替换的内容)
    * @param array        $languageArr 语言优先级,数组(en/zh_s/zh_t)
    * @return boolean
    */
    public function prepareMail($mail_id,$content="",$languageArr=''){
        // 根据邮件类型取得邮件配置
        if(is_array($mailTplArr = $this->_mailtplModel->field('title_en,title_zh_s,title_zh_t,en_content,zh_s_content,zh_t_content,cc_user_group')->where(array('id'=>$mail_id))->find())){
            // 取得邮件模板头信息
            //$mailHead = file_get_contents($_SERVER['DOCUMENT_ROOT'].__ROOT__.'/EmailTpl/mailHead.html');
            $mailHead = file_get_contents('./EmailTpl/mailHead.html');
            if(!is_array($languageArr)){
                $languageArr = array('en','zh_s','zh_t');
            }
            // 根据语言顺序，取得邮件模板内容
            foreach ($languageArr as $value){
                if('en' == $value && "" != $mailTplArr['title_en']){
                    $this->_mailTitle = $mailTplArr['title_en'];
                    $this->_mailContent = json_decode($mailTplArr['en_content'],true);
                    break;
                }
                if('zh_s' == $value && "" != $mailTplArr['title_zh_s']){
                    $this->_mailTitle = $mailTplArr['title_zh_s'];
                    $this->_mailContent = json_decode($mailTplArr['zh_s_content'],true);
                    break;
                }
                if('zh_t' == $value && "" != $mailTplArr['title_zh_t']){
                    $this->_mailTitle = $mailTplArr['title_zh_t'];
                    $this->_mailContent = json_decode($mailTplArr['zh_t_content'],true);
                    break;
                }
            }
            if(false === $this->_mailContent){
                $this->_errorMsg = L("SYSTEM_ERROR_SYSTEM_ERROR");
                return false;
            }
            // 替换业务内容
            if(is_array($content)){
                foreach ($content as $key => $value) {
                    $this->_mailTitle = str_replace('[{'.$key.'}]',$value,$this->_mailTitle);
                    $this->_mailContent = str_replace('[{'.$key.'}]',$value,$this->_mailContent);
                }
            }
            //Html转义
            $this->_mailContent = htmlspecialchars_decode($this->_mailContent);
            //组合邮件
            $this->_mailFile = str_replace('[{content}]',$this->_mailContent,$mailHead);
            //填写文件头
            $this->_mailFile = str_replace('[{title}]',$this->_mailTitle,$this->_mailFile);
            //取得邮件中抄送的用户组邮件
            $Usergroup_user = M('Usergroup_user');
            if($mailTplArr['cc_user_group'] && is_array($cc_user_ids = $Usergroup_user->where(array('usergroup_id'=>$mailTplArr['cc_user_group']))->getField('user_id',true))){
                $cc_user_ids_str = implode(',',$cc_user_ids);
                $Emp = M('Emp');
                $cc_user_mail = $Emp->where(array('id'=>array('IN',$cc_user_ids_str)))->getField('email',true);
                $this->_cc_receiver = array();
                foreach ($cc_user_mail as $user_mail){
                    if(!empty($user_mail)){
                        $this->_cc_receiver[] = $user_mail.C('MAIL_SUFFIX');
                    }
                }
            }else{
                $this->_cc_receiver = null;
            }
        }else{
            $this->_errorMsg = L("MAIL_ERROR_MAILTPL_NOT_EXIST");
            return false;
        }
        return true;
    }

    /**
     * @param string/array $receiver    收件人Email地址、多人则为Array，单人则为string
     * @param string/array $bcc_receiver    暗送人Email地址、多人则为Array，单人则为string
     * @param string/array $cc_receiver    抄送人Email地址、多人则为Array，单人则为string
     * @param string $sender 发件人
     * @return string
     */
    public function sendMail($receiver,$messageSendRoute,$bcc_receiver="",$cc_receiver="",$sender=""){
        //取得拒收邮件列表
        $mailrefuseModel = M('mailrefuse');
        $refuseArr = $mailrefuseModel->getField('mail',true);
        if(empty($receiver)){
            $this->_errorMsg = L('MAIL_ERROR_MAIL_RECEIVER_NULL');
            return false;
        }elseif(is_string($receiver)){
            if(in_array_case($receiver,$refuseArr)){
                $this->_errorMsg = L('MAIL_ERROR_MAIL_REFUSE');
                return false;
            }
        }else{
            foreach ($receiver as $key => $value){
                if(in_array_case($value,$refuseArr)){
                    unset($receiver[$key]);
                }
            }
            if(empty($receiver)) {
                $this->_errorMsg = L('MAIL_ERROR_MAIL_REFUSE');
                return false;
            }
        }
        //取得邮件发送方式
        $esbSwitch = C('ESB_SWITCH');
        if(SELF::MAIL_SEND_ROUTE_ESB == $messageSendRoute && self::ESB_SYSTEM_SWITCH_ON == $esbSwitch){
            $esbEmail = new EsbEmail();
            if(false === $esbEmail->send($this->_mailContent,$receiver,$bcc_receiver,$cc_receiver)){
                $this->_errorMsg = $esbEmail->getError();
                return false;
            }
        }else {
            // 取得系统邮件发送者配置
            $mailConf = C('SYSTEM_MAIL');
            if (!$mailConf) {
                $this->_errorMsg = L('MAIL_ERROR_NOT_CONFIG');
                return false;
            } else {
                $mailConfArr = explode('|', $mailConf);
            }
            if (empty($this->_PHPMailer)) {
                $this->_PHPMailer = new \PHPMailer();
            }
            try {
                // 服务器设置
                //$this->_PHPMailer->SMTPDebug = 2;                              // 开启Debug
                $this->_PHPMailer->isSMTP();                                       // 使用SMTP
                $this->_PHPMailer->Host = $mailConfArr[4];                         // 服务器地址
                $this->_PHPMailer->Username = $mailConfArr[1];                   // SMTP 用户名（你要使用的邮件发送账号）
                if(empty($mailConfArr[2])){
                    $this->_PHPMailer->SMTPAuth = false;
                }else{
                    $this->_PHPMailer->SMTPAuth = true;
                    $this->_PHPMailer->Password = $mailConfArr[2];                     // SMTP 密码
                }                              // 开启SMTP验证
                //$this->_PHPMailer->SMTPSecure = 'tls';                         // 开启TLS 可选
                $this->_PHPMailer->SMTPSecure = $mailConfArr[5];                   // 开启TLS 可选
                $this->_PHPMailer->Port = $mailConfArr[3];                         // 端口
                if (empty($sender)) {
                    $this->_PHPMailer->setFrom($mailConfArr[1], $mailConfArr[0]);      // 来自
                }else{
                    $this->_PHPMailer->setFrom($mailConfArr[1],$sender);      // 来自
                }
                // 设置收件人
                if (is_array($receiver)) {
                    foreach ($receiver as $value) {
                        $this->_PHPMailer->addAddress($value);
                    }
                } else {
                    $this->_PHPMailer->clearAddresses();
                    $this->_PHPMailer->addAddress($receiver);
                }
                // $mail->addReplyTo('admin@sandboxcn.com', 'SandBoxCn');        // 回复地址
                // $mail->addCC('cc@example.com');   //抄送
                // $mail->addBCC('bcc@example.com');  //暗送
                //添加抄送地址
                if (!empty($cc_receiver)) {
                    //如果方法调用时送入了抄送人,则以送入的抄送人为准
                    if (is_string($cc_receiver)) {
                        $this->_PHPMailer->clearCCs();
                        $this->_PHPMailer->addCC($cc_receiver);     //抄送单人
                    } else {
                        foreach ($cc_receiver as $value) {
                            $this->_PHPMailer->addCC($value);          //抄送多人
                        }
                    }
                }
//                } elseif (is_array($this->_cc_receiver)) {
//                    //如果方法调用时没有送入抄送人,则以类中保存的为准
//                    foreach ($this->_cc_receiver as $cc_email) {
//                        $this->_PHPMailer->addCC($cc_email);
//                    }
//                }
                //添加暗送地址
                if (!empty($bcc_receiver)) {
                    //如果方法调用时送入了抄送人,则以送入的抄送人为准
                    if (is_string($bcc_receiver)) {
                        $this->_PHPMailer->clearBCCs();
                        $this->_PHPMailer->addBCC($bcc_receiver);     //暗送单人
                    } else {
                        foreach ($bcc_receiver as $value) {
                            $this->_PHPMailer->addBCC($value);          //暗送多人
                        }
                    }
                }
                // 附件
                // $mail->addAttachment('/var/tmp/file.tar.gz');                // 添加附件
                // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');            // 可以设定名字
                // 内容
                $this->_PHPMailer->isHTML(true);                                        // 设置邮件格式为HTML
                $this->_PHPMailer->CharSet = "UTF-8";                                   // 设置编码格式
                $this->_PHPMailer->Subject = $this->_mailTitle;
                $this->_PHPMailer->Body = $this->_mailFile;
                //$mail->AltBody = 'xxxxxx';
                if (false === $this->_PHPMailer->send()) {
                    $this->_errorMsg = $this->_PHPMailer->ErrorInfo;
                    return false;
                }
            } catch (Exception $e) {
                $this->_errorMsg = $this->_PHPMailer->ErrorInfo;
                return false;
            }
        }
        return true;
    }

    //取得错误信息
    public function getError(){
        return $this->_errorMsg;
    }

    //取得需要抄送的用户邮件地址
    public function getCcReciver(){
        return $this->_cc_receiver;
    }

    //取得邮件文件
    public function getMailFile(){
        return $this->_mailFile;
    }
}
