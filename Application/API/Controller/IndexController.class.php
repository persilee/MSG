<?php
namespace API\Controller;
use Think\Controller;
use Common\Common\ConfigTools;
use Common\Common\EmpTools;
use Common\Common\MailTools;
use Common\Common\MailTplTools;

class IndexController extends Controller {
    protected $_validation = false;
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
      //获取数据
      $access_key = I('access_key');
      $send_type = I('send_type');
      $send_channel = I('send_channel');
      $sender = I('sender');
      $receiver = I('receiver');
      $cc_receiver = I('cc_receiver');
      $bcc_receiver = I('bcc_receiver');
      $Tpl_id = I('Tpl_id');
      $send_date = I('send_date');
      $send_time = I('send_time');
      $param = I('param');
      $sms_content = I('sms_content');
      /***************************验证**************************************/
      //验证access_key是否正确（规则：$send_type + $receiver + 当前时间（yyyy-mm-dd）的md5码）
      if (!empty($access_key) && $access_key == md5($send_type . $receiver . date('Y-m-d'))) {
        $this->_validation = true;
      }else{
        $this->returnData('40002',L('API_CODE_40002'),'');
        return;
      }
      //验证send_type
      if ($send_type == 'mail' || $send_type == 'sms') {
        $this->_validation = true;
      }else{
        $this->returnData('40003',L('API_CODE_40003'),'');
        return;
      }
      //验证send_channel
      if ($send_channel == 'esb' || $send_channel == 'terminal' || empty($send_channel)) {
        $this->_validation = true;
      }else{
        $this->returnData('40004',L('API_CODE_40004'),'');
        return;
      }
      //验证receiver
      if (empty($access_key)) {
        $this->returnData('40005',L('API_CODE_40005'),'');
        return;
      }elseif (false === $this->verEmail($receiver)) {
        $this->returnData('40006',L('API_CODE_40006'),'');
        return;
      }else{
        $receiver = explode(';',$receiver);
        $this->_validation = true;
      }
      //验证cc_receiver
      if (!empty($cc_receiver) && false === $this->verEmail($cc_receiver)) {
        $this->returnData('40007',L('API_CODE_40007'),'');
        return;
      }else{
        $cc_receiver = explode(';',$cc_receiver);
        $this->_validation = true;
      }
      //验证bcc_receiver
      if (!empty($bcc_receiver) && false === $this->verEmail($bcc_receiver)) {
        $this->returnData('40008',L('API_CODE_40008'),'');
        return;
      }else{
        $bcc_receiver = explode(';',$bcc_receiver);
        $this->_validation = true;
      }
      //验证Tpl_id
      if ($send_type == 'mail') {
        if (empty($Tpl_id)) {
          $this->returnData('40009',L('API_CODE_40009'),'');
          return;
        }else{
          $Mailtpl = M('Mailtpl');
          if (!$Mailtpl->where(array('id'=>$Tpl_id))->getField('id')) {
            $this->returnData('40009',L('API_CODE_40010'),'');
            return;
          }
        }
      }elseif ($send_type == 'sms') {
        $Smstpl = M('Smstpl');
        if (!$Smstpl->where(array('id'=>$Tpl_id))->getField('id')) {
          $this->returnData('40010',L('API_CODE_40010'),'');
          return;
        }
      }else{
        $this->_validation = true;
      }
      //验证send_date
      if (!empty($send_date)) {
        $is_date = strtotime($send_date)?strtotime($send_date):false;
        if ($is_date) {
          $this->_validation = true;
        }else{
          $this->returnData('40011',L('API_CODE_40011'),'');
          return;
        }
        //验证send_time
        $is_time = strtotime($send_time)?strtotime($send_time):false;
        if (empty($send_time)) {
          $this->returnData('40012',L('API_CODE_40012'),'');
          return;
        }elseif ($is_time) {
          $this->_validation = true;
        }else{
          $this->returnData('40013',L('API_CODE_40013'),'');
          return;
        }
      }
      //验证param
      if ($send_type == 'mail') {
        switch ($Tpl_id) {
          case '6':
            $tplParam = array(
              'name_title'            => '',
              'name'                  => '',
              'related_staffs'        => '',
              'rate_date'             => '',
              'rate_time'             => '',
              'rate'                  => '',
              'receiver'              => '',
            );
            break;
          case '14':
            $tplParam = array(
              'name_title'            => '',
              'name'                  => '',
              'related_staffs'        => '',
              'ex_rate_date'          => '',
              'ex_rate_time'          => '',
              'ex_rate'               => '',
              'receiver'              => '',
            );
            break;

          default:
            $tplParam = array();
            break;
        }
      }elseif ($send_type == 'sms') {
        switch ($Tpl_id) {
          case '1':
            $tplParam = array();
            break;

          default:
            $tplParam = array();
            break;
        }
      }
      if (count(array_diff_key($tplParam,$param)) > 0) {
        $this->returnData('40015',L('API_CODE_40015'),'');
        return;
      }else{
        $this->_validation = true;
      }
      //验证sms_content
      if ($send_type == 'sms' && empty($Tpl_id) && empty($sms_content)) {
        $this->returnData('40014',L('API_CODE_40014'),'');
        return;
      }else{
        $this->_validation = true;
      }
      /************************验证end*****************************************/
      //邮件信息发送
      if ($send_type == 'mail') {
        $mailTools = new MailTools();
        if('esb' == $send_channel){
          $messgeSendRoute = MailTools::MAIL_SEND_ROUTE_ESB;
        }else{
          $messgeSendRoute = MailTools::MAIL_SEND_ROUTE_TERMINAL;
        }
        //把参数数组转换为表格
        foreach ($param as $key => $value) {
          if (is_array($value)) {
            $param[$key] = $this->getTableStr($value);
          }
        }
        //判断是即时发送还是延迟发送
        if (!empty($send_date)) {
          $sDate = strtotime($send_date . ' ' . $send_time);
          $nDate = strtotime(date('Y-m-d H:i:s'));
          if ($sDate < $nDate) {
            if(false === $mailTools->prepareMail($Tpl_id,$param)){
              $this->returnData('40099',L('API_CODE_40099'),$mailTools->getError());
            }elseif(false === $mailTools->sendMail($sender,$receiver,$messgeSendRoute)){
              $this->returnData('40099',L('API_CODE_40099'),$mailTools->getError());
            }else{
              $this->returnData('0',L('API_CODE_0'),'');
            }
          }else{
            $flag = 'TRUE';
            while($flag == 'TRUE') {
              $nDate = strtotime(date('Y-m-d H:i:s'));
              if ($sDate < $nDate) {
                if(false === $mailTools->prepareMail($Tpl_id,$param)){
                  $this->returnData('40099',L('API_CODE_40099'),$mailTools->getError());
                }elseif(false === $mailTools->sendMail($sender,$receiver,$messgeSendRoute)){
                  $this->returnData('40099',L('API_CODE_40099'),$mailTools->getError());
                }else{
                  $this->returnData('0',L('API_CODE_0'),'');
                }
                $flag = 'FALSE';
              }else{
                sleep(15);
              }
            }
          }
        }

      }
    }
    public function getTableStr($data){
      foreach ($data as $key => $value) {
        foreach ($value as $vk => $vv) {
          $trArr[$vk] = $vk;
        }
      }
      $trArr = array_unique($trArr);
      if (is_array($data)) {
        $tableStr = "<table style='border-collapse:collapse;border:none;' width='100%'><thead><tr style='border: solid #ccc 1px;'><td style='border: solid #ccc 1px;'></td>";
        foreach ($trArr as $key => $value) {
          $tableThStr .= "<th style='border: solid #ccc 1px;'>" . $value . "</th>";
        }
        $tableThStr .= "</tr></thead><tbody>";
        foreach ($data as $key => $value) {
          $tableTbStr .= "<tr><td style='border: solid #ccc 1px;'>" . $key . "</td>";
          foreach ($trArr as $tr) {
            if (isset($data[$key][$tr])) {
              $tableTbStr .= "<td align='center' style='border: solid #ccc 1px;'>" . $data[$key][$tr] . "%</td>";
            }else{
              $tableTbStr .= "<td style='border: solid #ccc 1px;'></td>";
            }
          }
          $tableTbStr .= "</tr>";
        }
        $tableStr .= $tableThStr . $tableTbStr . "</tbody></table>";
        return $tableStr;
      }
      return $data;
    }
    public function verEmail($receiver){
      $receiver = explode(';',$receiver);
      $bool = true;
      foreach ($receiver as $key => $value) {
        if ($bool) {
          $bool = filter_var($value,FILTER_VALIDATE_EMAIL);
        }else{
          return $bool;
        }
      }
      return $bool;
    }
    public function returnData($code,$message,$data){
      $returnData = array(
        'code'    =>    $code,
        'message' =>    $message,
        'data'    =>    $data
      );
      $this->ajaxReturn($returnData);
    }
}
