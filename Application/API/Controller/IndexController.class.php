<?php
namespace API\Controller;
use Think\Controller;
use Common\Common\ConfigTools;
use Common\Common\EmpTools;
use Common\Common\MailTools;
use Common\Common\MailTplTools;

class IndexController extends Controller {

    public function __construct()
    {
      //载入配置项
      parent::__construct();
      ConfigTools::init();
    }
 /**
  * [外部系统接口]
  * 接口格式如下：（外部系统需要传过来的数据）
  * access_key     ：    访问密钥（如：1sfsdgSDFSDFGHNNSLDK1342N3T4TF：user id1）
  * send_type      ：    发送方式（mail/sms）
  * send_channel   ：    发送渠道（esb/terminal 1、空-由系统自动判断发送渠道，
  *                                           2、esb-强制通过esb系统发送
  *                                           3、terminal-强制通过行内终端配置发送）
  * sender         ：    发送人（系统直接记录接口传入的该栏位内容做为发送人）
  * receiver       ：    接收人( 1、如果上邮件地址，则填写邮箱地址；
  *                             2、如果是短信，则填写手机号；
  *                             3、多收件人之前以英方分号“;”分隔； )
  * cc_receiver    ：    抄送人( 1、多收件人之前以英方分号“;”分隔；
  *                             2、sms方式时不可输入。)
  * Tpl_id         ：    模板id( 1、邮件方式时，指定邮件模板id；
  *                             2、sms方式时，指定短信模板id
  *                             3、邮件方式时必须输入；
  *                             4、sms时可以不做输入，不输入时表示用接口内容直接发送。)
  * send_date      ：    发送日期（  1、不输入时为即时发送；
  *                                2、输入格式：yyyy-mm-dd ）
  * send_time      ：    发送时间（  1、如果指定了发送日期，则发送时间必须指定；
  *                                2、不可单独指定发送时间而不指定发送日期；
  *                                3、如果指定的日期及时间小于当前时间，则即时发送
  *                                4、输入格式为HH:mm（24小时制）
  * Param[]        ：    参数内容（  1、视模板中的具体参数而定;
  *                                2、param是一个数组，里面的每一项分别对应一个模板中的参数；
  *                                3、key为参数名称，主要用于替换模板中的参数时查询参数；
  *                                4、value为参数值，主要用于替换模板中的参数时做为值替换进去。
  * sms_content    ：    自由格式短信内容(如果不指定短信模板id时，直接用该栏位的内容进行发送)
  *
  */
    public function index(){
      $receiver = 'persilee@foxmail.com';
      $mailRoute = 'EBS';
      if(empty($receiver) || empty($mailRoute) ){
        $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
      }
      //取得测试邮件模板
      $Mailtpl = M('Mailtpl');
      $mailtplId = $Mailtpl->where(array('type'=>'TEST'))->getField('id');
      $mailTools = new MailTools();
      if('ESB' == $mailRoute){
        $messgeSendRoute = MailTools::MAIL_SEND_ROUTE_ESB;
      }else{
        $messgeSendRoute = MailTools::MAIL_SEND_ROUTE_TERMINAL;
      }
      if(false === $mailTools->prepareMail($mailtplId,'test')){
        $this->error($mailTools->getError());
      }elseif(false === $mailTools->sendMail($receiver,$messgeSendRoute)){
              $this->error($mailTools->getError());
      }else{
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
      }
      exit;
    }
}
