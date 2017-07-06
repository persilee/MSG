<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2016/6/1
 * Time: 16:43
 * 功能包含：客户维护、客户地址维护、客户账户维护、客户定单维护
 * 修改历史：
 *       日期           修改人             修改功能
 *     2016/06/01      林海宾            XXXXXXXX
 */

namespace Admin\Controller;

use Common\Common\ConfigTools;
use Common\Common\ClientTools;
use Common\Common\CurrencyTool;
use Common\Common\EmpTools;
use Common\Common\InterestMailTools;
use Common\Common\ExchangeMailTools;
use Common\Common\LogTools;
use Common\Common\MailTplTools;
use Common\Common\RateTools;
use Common\Common\ExRateTools;
use Common\Common\FileToZip;
use Common\Common\RegexTools;
use Common\Common\MarketTools;
use Common\Common\MarketMailTools;
use Common\Common\ChromePhp;

Vendor('PHPExcel.PHPExcel');
Vendor('PHPExcel.PHPExcel.IOFactory');

class ClientController extends AdminController
{

    /*================================================================================================*/
    /*  客户维护
    /*      功能清单：
    /*          1-clientList        ： 客户信息列表
    /*          2-clientAdd         ： 客户信息添加
    /*          3-clientUpdate      ： 客户信息修改
    /*          4-clientDelete      ： 客户信息删除
    /*          5-clientInquery     ： 客户信息查询
    /*          6-rateFloatUpdate   ： 客户优惠利率维护
    /*          7-exRateFloatUpdate ： 客户优惠汇率维护
    /*          8-mailSend          ： 客户利率邮件发送
    /*================================================================================================*/
    // 客户信息列表
    public function clientList()
    {
        $ci_no = I('ci_no', 0, 'intval');
        $name = I('name');
        //取得当前用户的分组
        $empTools = new EmpTools();
        $userIDStr = $empTools->getGroupUser(EmpTools::INCLUDE_SELF);
//        $Usergroup = M('Usergroup');
//        $UsergroupTotArr = $Usergroup->field('id,pid')->select();
//        $Usergroup_user = M('Usergroup_user');
//        $usergroupArr = $Usergroup_user->where(array('user_id'=>UID))->getField('usergroup_id',true);
//        $childUserGroupArr = array();
//        foreach ($usergroupArr as $value){
//            $tempArr = getChildTree($UsergroupTotArr,$value);
//            $ids = array_column($tempArr, 'id');
//            $childUserGroupArr = array_merge($childUserGroupArr,$ids);
//            $childUserGroupArr[] = $value;
//        }
//        //去除重复项
//        $childUserGroupArr = array_unique($childUserGroupArr);
//        $childUserGroupStr = implode($childUserGroupArr,',');
//        //取得当前分组下的所有用户
//        $userIDArr = $Usergroup_user->where(array('usergroup_id'=>array('IN',$childUserGroupStr)))->getField('user_id',true);
//        $userIDArr = array_unique($userIDArr);
//        $userIDStr = implode($userIDArr,',');
        $map = array(
            'create_emp' => array('IN',$userIDStr),
        );
        if (0 != $ci_no) {
            $map['ci_no'] = $ci_no;
        }
        if ("" != $name) {
            $map['name'] = array('like','%'.$name.'%');
        }
        $field = array('ci_no','type','id_type','id_code','name');
        $list = $this->lists('Client', $map, '', '', $field);
        //转换LIST中的客户类型type栏位为说明
//        $typeArray = array(
//            'type'=>L('TEMP_CLIENT_TYPE_TEXT'),
//        );
//        status_to_string($list,$typeArray);
        //转换LIST中的客户证件类型id_type栏位为说明
//        $idtypeArray = array(
//            'id_type'=>C('CLIENT_ID_TYPE'),
//        );
//        status_to_string($list,$idtypeArray);
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->assign('list', $list);
        $this->assign('name', $name);
        if (0 != $ci_no) {
            $this->assign('ci_no', $ci_no);
        }
        $this->display('clientList');
    }

    // 客户信息添加
    public function clientAdd()
    {
        if (IS_POST) {
            $data = array(
//                'ci_no'   => I('ci_no'),
                'type'    => I('type'),
                'id_type' => I('id_type'),
                'id_code' => I('id_code'),
                'name'    => I('name'),
                'ot_name' => I('ot_name'),
                'tpl_priority_lang' => I('tpl_priority_lang'),
                'auto_email_flag' => I('auto_email_flag', 0, 'intval'),
                'mail_plan_time' => I('mail_plan_time'),
                'auto_email_ex_rate_flag' => I('auto_email_ex_rate_flag', 0, 'intval'),
                'mail_plan_ex_rate_time' => I('mail_plan_ex_rate_time'),
                'email' => I('email'),
                'bcc_email' => I('bcc_email'),
                'auto_phone_flag' => I('auto_phone_flag', 0, 'intval'),
                'phone' => I('phone'),
                'remark'  => I('remark'),
                'cust_group' => I('cust_group'),
                'credit_rate' => I('credit_rate', 0, 'intval'),
//                'sex' => I('sex'),
                'education' => I('education'),
                'area' => I('area'),
                'inst_rate_mailtpl' => I('inst_rate_mailtpl', 0, 'intval'),
                'ex_rate_mailtpl' => I('ex_rate_mailtpl', 0, 'intval'),
                'market_mailtpl' => I('market_mailtpl', 0, 'intval'),
                'negative_rate' => I('negative_rate', 0, 'intval'),
            );
            $clientTools = new ClientTools();
            if (false === $clientTools->add($data)) {
                $this->error($clientTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            //取得邮件模板
            $mailtplTools = new MailTplTools();
            $instRateMailtplArr = $mailtplTools->getMailtplArr('INTEREST');
            $exRateMailtplArr = $mailtplTools->getMailtplArr('EXCHANGE');
            $marketMailtplArr = $mailtplTools->getMailtplArr('MARKET');
            $this->assign('instRateMailtplArr', $instRateMailtplArr);
            $this->assign('exRateMailtplArr', $exRateMailtplArr);
            $this->assign('marketMailtplArr', $marketMailtplArr);
            $this->display('clientAdd');
        }
    }

    // 客户信息修改
    public function clientUpdate()
    {
        if (IS_POST) {
            $data = array(
                'ci_no'   => I('ci_no'),
                'type'    => I('type'),
                'id_type' => I('id_type'),
                'id_code' => I('id_code'),
                'name'    => I('name'),
                'ot_name' => I('ot_name'),
                'tpl_priority_lang' => I('tpl_priority_lang'),
                'auto_email_flag' => I('auto_email_flag', 0, 'intval'),
                'mail_plan_time' => I('mail_plan_time'),
                'auto_email_ex_rate_flag' => I('auto_email_ex_rate_flag', 0, 'intval'),
                'mail_plan_ex_rate_time' => I('mail_plan_ex_rate_time'),
                'email' => I('email'),
                'bcc_email' => I('bcc_email'),
                'auto_phone_flag' => I('auto_phone_flag', 0, 'intval'),
                'phone' => I('phone'),
                'remark'  => I('remark'),
                'cust_group' => I('cust_group'),
                'credit_rate' => I('credit_rate', 0, 'intval'),
//                'sex' => I('sex'),
                'education' => I('education'),
                'area' => I('area'),
                'inst_rate_mailtpl' => I('inst_rate_mailtpl', 0, 'intval'),
                'ex_rate_mailtpl' => I('ex_rate_mailtpl', 0, 'intval'),
                'market_mailtpl' => I('market_mailtpl', 0, 'intval'),
                'negative_rate' => I('negative_rate', 0, 'intval'),
            );
            $clientTools = new ClientTools();
            if (false === $clientTools->update($data)) {
                $this->error($clientTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $ci_no = I('ci_no');
            if ("" == $ci_no) {
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            }
            $Client = D('Client');
            // 检查客户是否存在
            if (!is_array($result = $Client->find($ci_no))) {
                $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
                return false;
            }
            //取得客户的利率优惠配置信息,并组合输出数据
            $rate_float_arr = json_decode($result['rate_float'], true);
            $rateTools = new RateTools();
            $rateArr = $rateTools->getList();
            foreach ($rate_float_arr as $tenor_key => $tenor_item) {
                foreach ($tenor_item as $ccy_key => $ccy_item) {
                    if (isset($rateArr[$tenor_key][$ccy_key])) {
                        $rateArr[$tenor_key][$ccy_key] = $rate_float_arr[$tenor_key][$ccy_key];
                    }
                }
            }
            //取得客户的汇率优惠配置信息,并组合输出数据
            $exRate_float_arr = json_decode($result['ex_rate_float'], true);
            $exRateTools = new ExRateTools();
            $exRateArr = $exRateTools->getList();
            foreach ($exRate_float_arr as $exchange_ccy_key => $exchange_ccy_item) {
                foreach ($exchange_ccy_item as $target_ccy_key => $target_ccy_item) {
                    if (isset($exRateArr[$exchange_ccy_key][$target_ccy_key])) {
                        $exRateArr[$exchange_ccy_key][$target_ccy_key] = $exRate_float_arr[$exchange_ccy_key][$target_ccy_key];
                    }
                }
            }
            //取得邮件模板
            $mailtplTools = new MailTplTools();
            $instRateMailtplArr = $mailtplTools->getMailtplArr('INTEREST');
            $exRateMailtplArr = $mailtplTools->getMailtplArr('EXCHANGE');
            $marketMailtplArr = $mailtplTools->getMailtplArr('MARKET');
            $this->assign('instRateMailtplArr', $instRateMailtplArr);
            $this->assign('exRateMailtplArr', $exRateMailtplArr);
            $this->assign('marketMailtplArr', $marketMailtplArr);
            // 记录当前列表页的cookie
            Cookie('__forward1__', $_SERVER ['REQUEST_URI']);
            $this->assign('result', $result);
            $this->assign('rateArr', $rateArr);
            $this->assign('exRateArr', $exRateArr);
            $this->display('clientUpdate');
        }
    }

    // 客户信息删除
    public function clientDelete()
    {
        $ci_no = I('ci_no');
        if ("" == $ci_no) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $clientTools = new ClientTools();
        if (false === $clientTools->delete($ci_no)) {
            $this->error($clientTools->getError());
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
    }

    // 客户信息查询
    public function clientInquery()
    {
        $ci_no = I('ci_no');
        if ("" == $ci_no) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Client = D('Client');
        // 检查客户是否存在
        if (!is_array($result = $Client->find($ci_no))) {
            $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
            return false;
        }
        //取得客户的利率优惠配置信息,并组合输出数据
        $rate_float_arr = json_decode($result['rate_float'], true);
        $rateTools = new RateTools();
        $rateArr = $rateTools->getList();
        foreach ($rate_float_arr as $tenor_key => $tenor_item) {
            foreach ($tenor_item as $ccy_key => $ccy_item) {
                if (isset($rateArr[$tenor_key][$ccy_key])) {
                    $rateArr[$tenor_key][$ccy_key] = $rate_float_arr[$tenor_key][$ccy_key];
                }
            }
        }

        //取得客户的汇率优惠配置信息,并组合输出数据
        $exRate_float_arr = json_decode($result['ex_rate_float'], true);
        $exRateTools = new ExRateTools();
        $exRateArr = $exRateTools->getList();
        foreach ($exRate_float_arr as $exchange_ccy_key => $exchange_ccy_item) {
            foreach ($exchange_ccy_item as $target_ccy_key => $target_ccy_item) {
                if (isset($exRateArr[$exchange_ccy_key][$target_ccy_key])) {
                    $exRateArr[$exchange_ccy_key][$target_ccy_key] = $exRate_float_arr[$exchange_ccy_key][$target_ccy_key];
                }
            }
        }

        //取得邮件模板
        $mailtplTools = new MailTplTools();
        $instRateMailtplArr = $mailtplTools->getMailtplArr('INTEREST');
        $exRateMailtplArr = $mailtplTools->getMailtplArr('EXCHANGE');
        $marketMailtplArr = $mailtplTools->getMailtplArr('MARKET');
        $this->assign('instRateMailtplArr', $instRateMailtplArr);
        $this->assign('exRateMailtplArr', $exRateMailtplArr);
        $this->assign('marketMailtplArr', $marketMailtplArr);
        $this->assign('result', $result);
        $this->assign('rateArr', $rateArr);
        $this->assign('exRateArr', $exRateArr);
        $this->display('clientInquery');
    }

    //客户优惠利率维护
    public function rateFloatUpdate()
    {
        $ci_no = I('ci_no');
        $rateFloatArr = I('rateArr');
        $clientTools = new ClientTools();
        if (false === $clientTools->rateFloatUpdate($ci_no, $rateFloatArr)) {
            $this->error($clientTools->getError());
        } else {
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
//        $Client = M('Client');
//        if(!is_array($clientResult = $Client->find($ci_no))){
//            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
//        }
//        foreach ($rateFloatArr as $tenor => $ccyArr){
//            foreach ($ccyArr as $ccy => $value){
//                if('' == $value){
//                    $rateFloatArr[$tenor][$ccy] = 0;
//                }else{
//                    if(!RegexTools::amountCheck($value,8,3,true)){
//                        $this->error(RegexTools::getError()." Tenor = ".$tenor." ,Currency = ".$ccy);
//                    }
//                }
//            }
//        }
//        $rateFloatStr = json_encode($rateFloatArr);
//        if($returnCode = $Client->where(array('ci_no'=>$ci_no))->setField('rate_float',$rateFloatStr)){
//            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
//        }elseif(false ===  $returnCode){
//            $this->error($Client->getError());
//        }else{
//            $this->error(L('SYSTEM_MESSAGE_NOT_CHANGE'));
//        }
    }

    //客户优惠汇率维护
    public function exRateFloatUpdate()
    {
        $ci_no = I('ci_no');
        $exRateFloatArr = I('exRateArr');
        $clientTools = new ClientTools();
        if (false === $clientTools->exRateFloatUpdate($ci_no, $exRateFloatArr)) {
            $this->error($clientTools->getError());
        } else {
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }

    //客户邮件发送
    public function mailSend()
    {
        $ci_no = I('ci_no', 0, 'intval');
        if (0 == $ci_no) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        // 检查客户是否存在
        $Client = M('Client');
        if (!is_array($result = $Client->find($ci_no))) {
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if (IS_POST) {
            $mailtpl_id = I('mailtpl_id', 0, 'intval');
            $mail_type = I('mail_type', 0, 'intval');
            if (0 == $mailtpl_id) {
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
            }
            if (0 == $mail_type) {
                //检查当天的利率是否上传
              $date = date_to_int('-', date('Y-m-d'));
                $Rate = M('Rate');
                if ($Rate->where(array('date'=>$date))->count() < 1) {
                    $this->error(L('CLIENT_ERROR_INST_RATE_NOT_UP'));
                }
                $interestMailTools = new InterestMailTools();
                if (false === $interestMailTools->clientInterestMail($mailtpl_id, $result)) {
                    $this->error($interestMailTools->getError());
                } else {
                    LogTools::activeLog($result);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }
            }
            if (1 == $mail_type) {
                // ChromePhp::log($mail_type);
              //检查当天的汇率是否上传
              $date = date_to_int('-', date('Y-m-d'));
                $ExRate = M('exrate');
                if ($ExRate->where(array('date'=>$date))->count() < 1) {
                    $this->error(L('CLIENT_ERROR_EX_RATE_NOT_UP'));
                }
                $exchangeMailTools = new ExchangeMailTools();
                if (false === $exchangeMailTools->clientExchangeMail($mailtpl_id, $result)) {
                    $this->error($exchangeMailTools->getError());
                } else {
                    LogTools::activeLog($result);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }
            }
        } else {
            //取得所有模板
            $Mailtpl = M('Mailtpl');
            $instRateMailtplArr = $Mailtpl->where(array('type'=>'INTEREST'))->getField('id,name', true);
            $exRateMailtplArr = $Mailtpl->where(array('type'=>'EXCHANGE'))->getField('id,name', true);
            $this->assign('instRateMailtplArr', $instRateMailtplArr);
            $this->assign('exRateMailtplArr', $exRateMailtplArr);
            $this->assign('result', $result);
            $this->display('mailSend');
        }
    }

    //当天已发利率邮件列表
    public function rateMailSentCiList()
    {
        $empTools = new EmpTools();
        $userIDArr = $empTools->getGroupUser(EmpTools::INCLUDE_SELF);
        if ($userIDArr) {
            $map = array(
                'inst_send_time' => array('GT',date_to_int('-', date('Y-m-d'))),
                'create_emp' => array('IN',$userIDArr),
            );
            $field = array('ci_no','type','id_type','id_code','name','inst_send_time');
            $list = $this->lists('Client', $map, '', '', $field);
        } else {
            $list = null;
        }
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->assign('list', $list);
        $this->display('rateMailSentCiList');
    }
    //当天已发汇率邮件列表
    public function exRateMailSentCiList()
    {
        $empTools = new EmpTools();
        $userIDArr = $empTools->getGroupUser(EmpTools::INCLUDE_SELF);
        if ($userIDArr) {
            $map = array(
                'ex_rate_send_time' => array('GT',date_to_int('-', date('Y-m-d'))),
                'create_emp' => array('IN',$userIDArr),
            );
            $field = array('ci_no','type','id_type','id_code','name','ex_rate_send_time');
            $list = $this->lists('Client', $map, '', '', $field);
        } else {
            $list = null;
        }
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->assign('list', $list);
        $this->display('exRateMailSentCiList');
    }
    //重发利率邮件
    public function rateMailResend()
    {
        $ci_no = I('ci_no');
        if ("" == $ci_no) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        } else {
            $Client = M('Client');
            if ($Client->where(array('ci_no'=>array('IN',$ci_no)))->setField('inst_send_time', 0)) {
                LogTools::activeLog($ci_no);
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($Client->getError());
            }
        }
    }
    //重发汇率邮件
    public function exRateMailResend()
    {
        $ci_no = I('ci_no');
        if ("" == $ci_no) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        } else {
            $Client = M('Client');
            if ($Client->where(array('ci_no'=>array('IN',$ci_no)))->setField('ex_rate_send_time', 0)) {
                LogTools::activeLog($ci_no);
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($Client->getError());
            }
        }
    }
    /*================================================================================================*/
    /*  客户维护复核
    /*      功能清单：
    /*          1-clientAppoveList      ： 客户信息待复核队列
    /*          2-clientAppove          ： 客户信息复核
    /*          3-clientReject          ： 客户信息拒绝
    /*================================================================================================*/
    //客户信息待复核队列
    public function clientAppoveList()
    {
        $ci_no = I('ci_no');
        //取得当前用户所属组及其下属组的所有用户
        $empTools = new EmpTools();
        $userIDArr = $empTools->getGroupUser();
        $userIDStr = implode($userIDArr, ',');
        if ("" == $userIDStr) {
            //如果没有相应的同组用户及下属组用户,则不做查询
            $list = "";
        } else {
            //取得有权复核的所有记录
            $map = array(
                'type' => 'CI',
                'maker' => array('IN', $userIDStr),
            );
            if ("" != $ci_no) {
                $map['reference'] = array('LIKE', '%' . $ci_no . '%');
            }
            $list = $this->lists('AppoveView', $map);
            //转换LIST中的操作类型栏位转换为说明
            $funcArray = array(
                'func' => L('COMMON_ACTION_TEXT'),
            );
            status_to_string($list, $funcArray);
            $Client = M('Client');
            foreach ($list as $key => $value) {
                if (empty($value['reference'])) {
                    $tempResult = json_decode($value['content'], true);
                    $list[$key]['company_name'] = $tempResult['name'];
                } else {
                    $list[$key]['company_name'] = $Client->where(array('ci_no'=>$value['reference']))->getField('name');
                }
            }
        }
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->assign('list', $list);
        $this->assign('ci_no', $ci_no);
        $this->display('clientAppoveList');
    }

    //客户信息复核
    public function clientAppove()
    {
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        $flag = I('flag');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        if ('' != $flag) {
            $clientTools = new ClientTools();
            if (false === $clientTools->appove($date, $seq)) {
                $this->error($clientTools->getError());
            } else {
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        } else {
            //===========================取得复核记录中的信息==========================
            $Appove = M('Appove');
            if (!is_array($appoveRecord = $Appove->where(array('date'=>$date,'seq'=>$seq))->find())) {
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            } else {
                $appoveResult = json_decode($appoveRecord['content'], true);
            }
            if ('A' == $appoveRecord['func']) {
                $appoveResult['ci_no'] = L('TEMP_CLIENT_GEN_AFTER_APPOVE');
                $this->assign('clientResult', $appoveResult);
                $this->assign('appoveResult', $appoveResult);
            }
            if ('M' == $appoveRecord['func']) {
                //取得客户的利率优惠配置信息,并组合输出数据
                $rate_float_arr = json_decode($appoveResult['rate_float'], true);
                $rateTools = new RateTools();
                $rateArr = $rateTools->getList();
                foreach ($rate_float_arr as $tenor_key => $tenor_item) {
                    foreach ($tenor_item as $ccy_key => $ccy_item) {
                        if (isset($rateArr[$tenor_key][$ccy_key])) {
                            $rateArr[$tenor_key][$ccy_key]['value'] = $rate_float_arr[$tenor_key][$ccy_key]['value'];
                            $rateArr[$tenor_key][$ccy_key]['is_rate'] = $rate_float_arr[$tenor_key][$ccy_key]['is_rate'];
                        }
                    }
                }
                //取得客户的汇率优惠配置信息,并组合输出数据
                $exRate_float_arr = json_decode($appoveResult['ex_rate_float'], true);
                $exRateTools = new ExRateTools();
                $exRateArr = $exRateTools->getList();
                foreach ($exRate_float_arr as $exchange_key => $exchange_item) {
                    foreach ($exchange_item as $target_key => $target_item) {
                        if (isset($exRateArr[$exchange_key][$target_key])) {
                            $exRateArr[$exchange_key][$target_key]['value'] = $exRate_float_arr[$exchange_key][$target_key]['value'];
                            $exRateArr[$exchange_key][$target_key]['is_exRate'] = $exRate_float_arr[$exchange_key][$target_key]['is_exRate'];
                        }
                    }
                }
                $this->assign('appoveResult', $appoveResult);
                $this->assign('appoveRateArr', $rateArr);
                $this->assign('appoveExRateArr', $exRateArr);
            }
            //========================================================================
            //===========================取得原客户信息==========================
            if ('A' != $appoveRecord['func']) {
                $Client = D('Client');
                if (!is_array($clientResult = $Client->find($appoveRecord['reference']))) {
                    $this->error(L('SYSTEM_ERROR_SYSTEM_ERROR'));
                }
                //取得客户的利率优惠配置信息,并组合输出数据
                $rate_float_arr = json_decode($clientResult['rate_float'], true);
                if (empty($rateTools)) {
                    $rateTools = new RateTools();
                }
                $rateArr = $rateTools->getList();
                foreach ($rate_float_arr as $tenor_key => $tenor_item) {
                    foreach ($tenor_item as $ccy_key => $ccy_item) {
                        if (isset($rateArr[$tenor_key][$ccy_key])) {
                            $rateArr[$tenor_key][$ccy_key]['value'] = $rate_float_arr[$tenor_key][$ccy_key]['value'];
                            $rateArr[$tenor_key][$ccy_key]['is_rate'] = $rate_float_arr[$tenor_key][$ccy_key]['is_rate'];
                        }
                    }
                }
                //取得客户的汇率优惠配置信息,并组合输出数据
                $exRate_float_arr = json_decode($clientResult['ex_rate_float'], true);
                if (empty($exRateTools)) {
                    $exRateTools = new ExRateTools();
                }
                $exRateArr = $exRateTools->getList();
                foreach ($exRate_float_arr as $exchange_key => $exchange_item) {
                    foreach ($exchange_item as $target_key => $target_item) {
                        if (isset($exRateArr[$exchange_key][$target_key])) {
                            $exRateArr[$exchange_key][$target_key]['value'] = $exRate_float_arr[$exchange_key][$target_key]['value'];
                            $exRateArr[$exchange_key][$target_key]['is_exRate'] = $exRate_float_arr[$exchange_key][$target_key]['is_exRate'];
                        }
                    }
                }
                $this->assign('clientResult', $clientResult);
                $this->assign('rateArr', $rateArr);
                $this->assign('exRateArr', $exRateArr);
                if ('D' == $appoveResult['func']) {
                    $this->assign('appoveResult', $clientResult);
                    $this->assign('appoveRateArr', $rateArr);
                }
            }
            //=====================================================================
            //取得利率邮件模板
            $mailtplTools = new MailTplTools();
            $instRateMailtplArr = $mailtplTools->getMailtplArr('INTEREST');
            $exRateMailtplArr = $mailtplTools->getMailtplArr('EXCHANGE');
            $marketMailtplArr = $mailtplTools->getMailtplArr('MARKET');
            $this->assign('instRateMailtplArr', $instRateMailtplArr);
            $this->assign('exRateMailtplArr', $exRateMailtplArr);
            $this->assign('marketMailtplArr', $marketMailtplArr);
            $this->assign('func', $appoveRecord['func']);
            $this->assign('date', $date);
            $this->assign('seq', $seq);
            $this->display('clientAppove');
        }
    }

    //客户信息拒绝
    public function clientReject()
    {
        $date = I('date', 0, 'intval');
        $seq = I('seq', 0, 'intval');
        if (0 == $date || 0 == $seq) {
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR'));
        }
        $Appove = M('Appove');
        if (!is_array($Appove->where(array('date'=>$date,'seq'=>$seq))->find())) {
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if (false === $Appove->where(array('date'=>$date,'seq'=>$seq))->delete()) {
            $this->error($Appove->getError());
        } else {
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }
    /*================================================================================================*/
    /*  汇率信息维护
    /*      功能清单：
    /*          1-exRateList             :  汇率列表
    /*          2-exRateAdd              :  汇率添加
    /*          3-exRateUpdate           :  汇率修改
    /*          4-exRateDelete           :  汇率删除
    /*          5-exRateDownload         :  汇率下载
    /*          6-exRateBatchDownload    :  汇率批量下载
    /*          7-exRateUpload           :  汇率上传
    /*================================================================================================*/
    public function exRateList()
    {
        $date = I('date');
        if ("" == $date) {
            $date = date('Y-m-d');
        }
        $exRateTools = new ExRateTools();
        $list = $exRateTools->getExDateList($date);
        $this->assign('list', $list);
        $this->assign('date', $date);
      // 记录当前列表页的cookie
      Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->display('exRateList');
    }
    //汇率添加
    public function exRateAdd()
    {
        $date = I('date');
        $exchangeCcy = I('exchangeCcy');
        $targetCcy = I('targetCcy');
        if (IS_POST) {
            $exRate = I('exRate');
            $exRateTools = new ExRateTools();
            if ($exRateTools->addExRate($date, $exchangeCcy, $targetCcy, $exRate)) {
                LogTools::activeLog(array('date'=>$date,'exchange_ccy'=>$exchangeCcy,'target_ccy'=>$targetCcy));
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($exRateTools->getError());
            }
        } else {
            $map = array(
            'date' => date_to_int('-', $date),
            'target_ccy' => $targetCcy,
            'exchange_ccy' => $exchangeCcy,
        );
            $ExRate = M('exrate');
            if (is_array($result = $ExRate->where($map)->find())) {
                $this->error(L('SYSTEM_ERROR_RECORD_EXIST'));
            }
            $map['date'] = $date;
            $this->assign('result', $map);
        //取得贷币数组
        $currencyTool = new CurrencyTool();
            $ccyArr = $currencyTool->getCcyArr(1);
            $this->assign('ccyArr', $ccyArr);
            $this->display('exRateAdd');
        }
    }

    //汇率修改
    public function exRateUpdate()
    {
        if (IS_POST) {
            $dateInt = I('dateInt', 0, intval);
            $seq     = I('seq', 0, intval);
            $exRate  = I('exRate');
            $exRateTools = new ExRateTools();
            if ($exRateTools->updateExRate($dateInt, $seq, $exRate)) {
                LogTools::activeLog(array('date'=>$dateInt,'seq'=>$seq,'ex_rate'=>$exRate));
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($rateTools->getError());
            }
        } else {
            $date = I('date');
            $exchangeCcy = I('exchangeCcy');
            $targetCcy = I('targetCcy');
            $map = array(
                'date' => date_to_int('-', $date),
                'exchange_ccy' => $exchangeCcy,
                'target_ccy' => $targetCcy,
            );
            $ExRate = M('exrate');
            if (!is_array($result = $ExRate->where($map)->find())) {
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            $this->assign('date', $date);
            $this->assign('result', $result);
            //取得贷币数组
            $currencyTool = new CurrencyTool();
            $ccyArr = $currencyTool->getCcyArr(1);
            $this->assign('ccyArr', $ccyArr);
            $this->display('exRateUpdate');
        }
    }

    //汇率删除
    public function exRateDelete()
    {
        $date = I('date');
        $exchangeCcy = I('exchangeCcy');
        $targetCcy = I('targetCcy');
        $map = array(
            'date' => date_to_int('-', $date),
            'exchange_ccy' => $exchangeCcy,
            'target_ccy' => $targetCcy,
        );
        $ExRate = M('exrate');
        if ($ExRate->where($map)->delete()) {
            LogTools::activeLog($map);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $this->error($Rate->getError());
        }
    }

    //汇率下载
    public function exRateDownload()
    {
        $date = I('date');
        $exRateTools = new ExRateTools();
        if (!$exRateTools->exRateExcelGen($date)) {
            LogTools::activeLog($date);
            $this->error($exRateTools->getError());
        }
        $exRateTools->excelDownload();
        exit;
    }

    //汇率批量下载
    public function exRateBatchDownload()
    {
        if (IS_POST) {
            $start_date = I('start_date');
            $end_date = I('end_date');
            // 输入检查
            if ("" == $start_date || "" == $end_date) {
                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
            }
            if (!date_check('-', $start_date)) {
                $this->error(L('TEMP_CLIENT_START_DATE').' , '.L('SYSTEM_ERROR_DATE_FORMAT'));
            } else {
                $start_date_int = date_to_int('-', $start_date);
            }
            if (!date_check('-', $end_date)) {
                $this->error(L('TEMP_CLIENT_END_DATE').' , '.L('SYSTEM_ERROR_DATE_FORMAT'));
            } else {
                $end_date_int = date_to_int('-', $end_date);
            }
            if ($end_date_int < $start_date_int) {
                $this->error(L('CLIENT_ERROR_END_LESS_START'));
            }
            // 生成临时文件路径,如果路径重复
            $route = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Downloads/'.is_login().'_'.date('Y-m-d').'_'.rand().'/';
            if (!mkdir($route)) {
                $this->error(L("SYSTEM_ERROR_SYSTEM_ERROR")." 1001");
            }
            //循环生成利率文件
            $exRateTools = new ExRateTools();
            for ($date=$start_date_int;$date <= $end_date_int;$date += 24*3600) {
                $exRateTools->exRateExcelGen(day_format($date), $route);
            }
            //打包生成的文件夹并下载
            $FileToZip = new FileToZip($route, $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Downloads/');
            if (false === $FileToZip->tozip()) {
                $this->error(L("SYSTEM_ERROR_SYSTEM_ERROR")." 1005");
            } else {
                $FileToZip->downloadZip();
            }
            // 删除文件夹及下所有文件
            deleteDir($route);
            LogTools::activeLog(array('start_date'=>$start_date,'end_date'=>$end_date));
            exit;
        } else {
            $this->display('exRateBatchDownload');
        }
    }

    // 汇率上传
    public function exRateUpload()
    {
        $date = I('date');
        if (IS_POST) {
            $return  = array('status' => 1, 'info' => L('SYSTEM_ACTION_UPLOAD_SUCCESS'), 'data' => '');
            // 读取上传文件并写表
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     2000000 ;// 设置附件上传大小
            $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类型
            $upload->rootPath  =     $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/rate/'; // 设置附件上传目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $upload->autoSub   =     false;// 上传文件
            $info   =   $upload->uploadOne($_FILES['temp_file']);
            if (!$info) {// 上传错误提示错误信息
                $return['status'] = 0;
                $return['info']   = $upload->getError();
            } else {// 上传成功 获取上传文件信息
                $filename = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/rate/'.$info['savename'];
                $fileType = \PHPExcel_IOFactory::identify($filename);
                $objReader = \PHPExcel_IOFactory::createReader($fileType);
                $objReader->setLoadSheetsOnly(true);
                $objPHPExcel = $objReader->load($filename);
                $exRateTools = new ExRateTools();
                $ExRate = M('exrate');
                $targetCcyArr = array();
                $returnArr = array();
                //==============取得参数设置的利差===============//
                $warning_spread = C('INT_RATE_WARNING_SPREAD');
                //=============================================//
                foreach ($objPHPExcel->getWorksheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        foreach ($row->getCellIterator() as $key => $cell) {
                            if ($row->getRowIndex() == 1) {
                                $targetCcyArr[] = $cell->getValue();
                            } elseif ($key == 0) {
                                $exchangeCcyValue = $cell->getValue();
                            } else {
                                //判断是否是同种货币，如果是同种货币提示，且强制设置为0
                                if ($targetCcyArr[$key] == $exchangeCcyValue) {
                                    if (($cell->getValue() * 100) != 0) {
                                        $exRateValue = sprintf("%.8f", 0);
                                        $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('CLIENT_ERROR_EXRATE_NOT_REPEAT'));
                                    }
                                } else {
                                    $exRateValue = $cell->getValue() * 100;
                                    $exRateValue = sprintf("%.8f", $exRateValue);
                                    $map = array(
                                      'date'   => date_to_int('-', $date),
                                      'exchange_ccy'  => $exchangeCcyValue,
                                      'target_ccy'    => $targetCcyArr[$key],
                                  );
                                }
                                if (is_array($result = $ExRate->where($map)->find())) {
                                    $returnCode = $exRateTools->updateExRate($result['date'], $result['seq'], $exRateValue);
                                } else {
                                    $returnCode = $exRateTools->addExRate($date, $exchangeCcyValue, $targetCcyArr[$key], $exRateValue);
                                }
                                if (false === $returnCode) {
                                    $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],$exRateTools->getError());
                                } else {
                                    //如果汇率没有指定,则给出提示信息
                                    if (empty($exRateValue)) {
                                        $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('CLIENT_ERROR_EXRATE_NOT_INPUT'));
                                    }
                                    //如果利差大于参数设置,则出提示信息
                                    $lastDateExRate = $ExRate->where(array('date'=>array('LT',date_to_int('-', $date)),'exchange_ccy'=>$exchangeCcyValue,'target_ccy'=>$targetCcyArr[$key]))->order('date DESC')->getField('ex_rate');
                                    if ($lastDateExRate === null) {
                                        $lastDateExRate = 0;
                                    }
                                    if (array_key_exists($targetCcyArr[$key], $warning_spread)) {
                                        $spreadWarningExRate = $warning_spread[$targetCcyArr[$key]];
                                    } elseif (array_key_exists('ALL', $warning_spread)) {
                                        $spreadWarningExRate = $warning_spread['ALL'];
                                    } else {
                                        $spreadWarningExRate = false;
                                    }
                                    if ((false !== $spreadWarningExRate) && (abs($lastDateExRate - $exRateValue) > $spreadWarningExRate)) {
                                        $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('CLIENT_ERROR_SPREAD_TOO_MUCH'));
                                    }
                                }
                            }
                        }
                    }
                }
                unlink($filename);
                if (!empty($returnArr)) {
                    $return['info'] = L('SYSTEM_MESSAGE_HAVE_ERROR');
                    $return['data'] = $returnArr;
                }
            }
            LogTools::activeLog(array('date'=>$date));
            $this->assign('return', $return);
        } else {
            if (empty($date)) {
                $this->assign('date', date('Y-m-d'));
            }
            // 记录当前列表页的cookie
            Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        }
        $this->display('exRateUpload');
    }

    /*================================================================================================*/
    /*  利率信息维护
    /*      功能清单：
    /*          1-rateList          ： 利率列表
    /*          2-rateAdd           ： 利率添加
    /*          3-rateUpdate        ： 利率修改
    /*          4-rateDelete        ： 利率删除
    /*          5-rateUpload        ： 利率上传
    /*          6-rateDownload      ： 利率下载
    /*          7-rateBatchDownload ： 利率批量下载
    /*================================================================================================*/
    //利率列表
    public function rateList()
    {
        $date = I('date');
        if ("" == $date) {
            $date = date('Y-m-d');
        }
        $rateTools = new RateTools();
        $list = $rateTools->getDateList($date);
        $this->assign('list', $list);
        $this->assign('date', $date);
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->display('rateList');
    }

    //利率添加
    public function rateAdd()
    {
        $date = I('date');
        $tenor = I('tenor');
        $ccy = I('ccy');
        if (IS_POST) {
            $rate = I('rate');
            $rateTools = new RateTools();
            if ($rateTools->addRate($date, $tenor, $ccy, $rate)) {
                LogTools::activeLog(array('date'=>$date,'tenor'=>$tenor,'ccy'=>$ccy));
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($rateTools->getError());
            }
        } else {
            $map = array(
                'date' => date_to_int('-', $date),
                'tenor' => $tenor,
                'ccy' => $ccy,
            );
            $Rate = M('Rate');
            if (is_array($result = $Rate->where($map)->find())) {
                $this->error(L('SYSTEM_ERROR_RECORD_EXIST'));
            }
            $map['date'] = $date;
            $this->assign('result', $map);
            //取得贷币数组
            $currencyTool = new CurrencyTool();
            $ccyArr = $currencyTool->getCcyArr(1);
            $this->assign('ccyArr', $ccyArr);
            $this->display('rateAdd');
        }
    }

    //利率修改
    public function rateUpdate()
    {
        if (IS_POST) {
            $dateInt = I('dateInt', 0, intval);
            $seq     = I('seq', 0, intval);
            $rate    = I('rate');
            $rateTools = new RateTools();
            if ($rateTools->updateRate($dateInt, $seq, $rate)) {
                LogTools::activeLog(array('date'=>$dateInt,'seq'=>$seq,'rate'=>$rate));
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($rateTools->getError());
            }
        } else {
            $date = I('date');
            $tenor = I('tenor');
            $ccy = I('ccy');
            $map = array(
                'date' => date_to_int('-', $date),
                'tenor' => $tenor,
                'ccy' => $ccy,
            );
            $Rate = M('Rate');
            if (!is_array($result = $Rate->where($map)->find())) {
                $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
            }
            $this->assign('date', $date);
            $this->assign('result', $result);
            //取得贷币数组
            $currencyTool = new CurrencyTool();
            $ccyArr = $currencyTool->getCcyArr(1);
            $this->assign('ccyArr', $ccyArr);
            $this->display('rateUpdate');
        }
    }

    //利率删除
    public function rateDelete()
    {
        $date = I('date');
        $tenor = I('tenor');
        $ccy = I('ccy');
        $map = array(
            'date' => date_to_int('-', $date),
            'tenor' => $tenor,
            'ccy' => $ccy,
        );
        $Rate = M('Rate');
        if ($Rate->where($map)->delete()) {
            LogTools::activeLog($map);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $this->error($Rate->getError());
        }
    }

    //利率下载
    public function rateDownload()
    {
        $date = I('date');
        $rateTools = new RateTools();
        if (!$rateTools->rateExcelGen($date)) {
            LogTools::activeLog($date);
            $this->error($rateTools->getError());
        }
        $rateTools->excelDownload();
        exit;
    }

    //利率批量下载
    public function rateBatchDownload()
    {
        if (IS_POST) {
            $start_date = I('start_date');
            $end_date = I('end_date');
            // 输入检查
            if ("" == $start_date || "" == $end_date) {
                $this->error(L('SYSTEM_ERROR_MUST_INPUT'));
            }
            if (!date_check('-', $start_date)) {
                $this->error(L('TEMP_CLIENT_START_DATE').' , '.L('SYSTEM_ERROR_DATE_FORMAT'));
            } else {
                $start_date_int = date_to_int('-', $start_date);
            }
            if (!date_check('-', $end_date)) {
                $this->error(L('TEMP_CLIENT_END_DATE').' , '.L('SYSTEM_ERROR_DATE_FORMAT'));
            } else {
                $end_date_int = date_to_int('-', $end_date);
            }
            if ($end_date_int < $start_date_int) {
                $this->error(L('CLIENT_ERROR_END_LESS_START'));
            }
            // 生成临时文件路径,如果路径重复
            $route = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Downloads/'.is_login().'_'.date('Y-m-d').'_'.rand().'/';
            if (!mkdir($route)) {
                $this->error(L("SYSTEM_ERROR_SYSTEM_ERROR")." 1001");
            }
            //循环生成利率文件
            $rateTools = new RateTools();
            for ($date=$start_date_int;$date <= $end_date_int;$date += 24*3600) {
                $rateTools->rateExcelGen(day_format($date), $route);
            }
            //打包生成的文件夹并下载
            $FileToZip = new FileToZip($route, $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Downloads/');
            if (false === $FileToZip->tozip()) {
                $this->error(L("SYSTEM_ERROR_SYSTEM_ERROR")." 1005");
            } else {
                $FileToZip->downloadZip();
            }
            // 删除文件夹及下所有文件
            deleteDir($route);
            LogTools::activeLog(array('start_date'=>$start_date,'end_date'=>$end_date));
            exit;
        } else {
            $this->display('rateBatchDownload');
        }
    }

    // 利率上传
    public function rateUpload()
    {
        $date = I('date');
        if (IS_POST) {
            $return  = array('status' => 1, 'info' => L('SYSTEM_ACTION_UPLOAD_SUCCESS'), 'data' => '');
            // 读取上传文件并写表
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     2000000 ;// 设置附件上传大小
            $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类型
            $upload->rootPath  =     $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/rate/'; // 设置附件上传目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $upload->autoSub   =     false;// 上传文件
            $info   =   $upload->uploadOne($_FILES['temp_file']);
            if (!$info) {// 上传错误提示错误信息
                $return['status'] = 0;
                $return['info']   = $upload->getError();
            } else {// 上传成功 获取上传文件信息
                $filename = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/rate/'.$info['savename'];
                $fileType = \PHPExcel_IOFactory::identify($filename);
                $objReader = \PHPExcel_IOFactory::createReader($fileType);
                $objReader->setLoadSheetsOnly(true);
                $objPHPExcel = $objReader->load($filename);
                $rateTools = new RateTools();
                $Rate = M('Rate');
                $ccyArr = array();
                $returnArr = array();
                //==============取得参数设置的利差===============//
                $warning_spread = C('INT_RATE_WARNING_SPREAD');
                //=============================================//
                foreach ($objPHPExcel->getWorksheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        foreach ($row->getCellIterator() as $key => $cell) {
                            if ($row->getRowIndex() == 1) {
                                $ccyArr[] = $cell->getValue();
                            } elseif ($key == 0) {
                                $tenorValue = $cell->getValue();
                            } else {
                                $rateValue = $cell->getValue() * 100;
                                $rateValue = sprintf("%.8f", $rateValue);
                                $map = array(
                                    'date'   => date_to_int('-', $date),
                                    'tenor'  => $tenorValue,
                                    'ccy'    => $ccyArr[$key],
                                );
                                if (is_array($result = $Rate->where($map)->find())) {
                                    $returnCode = $rateTools->updateRate($result['date'], $result['seq'], $rateValue);
                                } else {
                                    $returnCode = $rateTools->addRate($date, $tenorValue, $ccyArr[$key], $rateValue);
                                }
                                if (false === $returnCode) {
                                    $returnArr[] = array($tenorValue,$ccyArr[$key],$rateTools->getError());
                                } else {
                                    //如果利率没有指定,则给出提示信息
                                    if (empty($rateValue)) {
                                        $returnArr[] = array($tenorValue,$ccyArr[$key],L('CLIENT_ERROR_INTEREST_NOT_INPUT'));
                                    }
                                    //如果利差大于参数设置,则出提示信息
                                    $lastDateRate = $Rate->where(array('date'=>array('LT',date_to_int('-', $date)),'tenor'=>$tenorValue,'ccy'=>$ccyArr[$key]))->order('date DESC')->getField('rate');
                                    if ($lastDateRate === null) {
                                        $lastDateRate = 0;
                                    }
                                    if (array_key_exists($ccyArr[$key], $warning_spread)) {
                                        $spreadWarningRate = $warning_spread[$ccyArr[$key]];
                                    } elseif (array_key_exists('ALL', $warning_spread)) {
                                        $spreadWarningRate = $warning_spread['ALL'];
                                    } else {
                                        $spreadWarningRate = false;
                                    }
                                    if ((false !== $spreadWarningRate) && (abs($lastDateRate - $rateValue) > $spreadWarningRate)) {
                                        $returnArr[] = array($tenorValue,$ccyArr[$key],L('CLIENT_ERROR_SPREAD_TOO_MUCH'));
                                    }
                                }
                            }
                        }
                    }
                }
                unlink($filename);
                if (!empty($returnArr)) {
                    $return['info'] = L('SYSTEM_MESSAGE_HAVE_ERROR');
                    $return['data'] = $returnArr;
                }
            }
            LogTools::activeLog(array('date'=>$date));
            $this->assign('return', $return);
        } else {
            if (empty($date)) {
                $this->assign('date', date('Y-m-d'));
            }
            // 记录当前列表页的cookie
            Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        }
        $this->display('rateUpload');
    }
    /*================================================================================================*/
    /*  利率信息维护
    /*      功能清单：
    /*          1-refuseList          ： 拒收邮件列表
    /*          2-refuseAdd           ： 拒收邮件地址添加
    /*          3-refuseDelete        ： 拒收邮件地址删除
    /*================================================================================================*/
    //拒收邮件列表
    public function refuseList()
    {
        //取得当前用户所有用户组下所有用户
        $empTools = new EmpTools();
        $userIDStr = $empTools->getGroupUser(EmpTools::INCLUDE_SELF);
        $refuseView = D('RefuseView');
        $list = $refuseView->where(array('emp_id'=>array('IN',$userIDStr)))->select();
        $this->assign('list', $list);
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->display();
    }

    //拒收邮件地址添加
    public function refuseAdd()
    {
        if (IS_POST) {
            $mail = I('mail');
            if (empty($mail)) {
                $this->error(L('TEMP_CLIENT_EMAIL').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
            } elseif (!RegexTools::regex('email', $mail)) {
                $this->error(L('TEMP_CLIENT_EMAIL') .' , '. L('SYSTEM_ERROR_FORMAT'));
            }
            $mailrefuseModel = M('mailrefuse');
            if (is_array($mailrefuseModel->find($mail))) {
                $this->error(L('SYSTEM_ERROR_RECORD_EXIST'));
            }
            $data = array(
                'mail' => $mail,
                'time' => NOW_TIME,
                'emp_id' => UID,
            );
            if ($mailrefuseModel->add($data)) {
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            } else {
                $this->error($mailrefuseModel->getError());
            }
        } else {
            $this->display('refuseAdd');
        }
    }

    //拒收邮件地址删除
    public function refuseDelete()
    {
        $mail = I('mail');
        if (empty($mail)) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $mailrefuseModel = M('mailrefuse');
        if (!is_array($mailrefuseModel->find())) {
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if ($mailrefuseModel->where(array('mail'=>$mail))->delete()) {
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $this->error($mailrefuseModel->getError());
        }
    }

    /*================================================================================================*/
    /*  市场资讯维护
    /*      功能清单：
    /*          1-marketList          ： 市场资讯列表
    /*          2-marketAdd           ： 市场资讯添加
    /*          3-marketDelete        ： 市场资讯删除
    /*          4-marketUpdtae        ： 市场资讯修改
    /*          5-marketInquery       ： 市场资讯查询
    /*          6-marketSend          ： 市场资讯邮件发送
    /*================================================================================================*/
    //市场资讯列表
    public function marketList()
    {
        $date = I('date');
        if ("" != $date) {
            $date = date_to_int('-', $date);
        }
        $map = array(
        'date' => $date,
      );
        $list = $this->lists('MarketView', $map);
        $this->assign('list', $list);
      // 记录当前列表页的cookie
      Cookie('__forward__', $_SERVER ['REQUEST_URI']);
        $this->display('marketList');
    }

    //市场资讯添加
    public function marketAdd()
    {
        $date = I('date');
        if ("" == $date) {
            $date = date('Y-m-d');
        }
        if (IS_POST) {
            $data = array(
              'date'           => date_to_int('-', $date),
              'title_en'       => I('title_en'),
              'title_zh_s'     => I('title_zh_s'),
              'title_zh_t'     => I('title_zh_t'),
              'en_content'     => I('en_content'),
              'zh_s_content'   => I('zh_s_content'),
              'zh_t_content'   => I('zh_t_content'),
              'time'           => NOW_TIME,
          );
            $markeTools = new MarketTools();
            if (false === $markeTools->add($data)) {
                $this->error($markeTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $this->display('marketAdd');
        }
    }

    // 市场资讯删除
    public function marketDelete()
    {
        $seq = I('seq');
        if ("" == $seq) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $marketTools = new MarketTools();
        if (false === $marketTools->delete($seq)) {
            $this->error($marketTools->getError());
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
    }

    // 市场资讯修改
    public function marketUpdate()
    {
        $seq = I('seq');
        if ("" == $seq) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        if (IS_POST) {
            $data = array(
              'seq'            => $seq,
              'date'           => date_to_int('-', date('Y-m-d')),
              'title_en'       => I('title_en'),
              'title_zh_s'     => I('title_zh_s'),
              'title_zh_t'     => I('title_zh_t'),
              'en_content'     => I('en_content'),
              'zh_s_content'   => I('zh_s_content'),
              'zh_t_content'   => I('zh_t_content'),
              'time'           => NOW_TIME,
          );
            $marketTools = new MarketTools();
            if (false === $marketTools->update($data)) {
                $this->error($marketTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $Market = D('Market');
            // 检查市场资讯是否存在
            if (!is_array($result = $Market->find($seq))) {
                $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
                return false;
            }
            // 记录当前列表页的cookie
            Cookie('__forward1__', $_SERVER ['REQUEST_URI']);
            $this->assign('result', $result);
            $this->display('marketUpdate');
        }
    }

    // 市场资讯查询
    public function marketInquery()
    {
        $seq = I('seq');
        if ("" == $seq) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }

        $Market = D('Market');
        // 检查市场资讯是否存在
        if (!is_array($result = $Market->find($seq))) {
            $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
            return false;
        }
        // 记录当前列表页的cookie
        Cookie('__forward1__', $_SERVER ['REQUEST_URI']);
        $this->assign('result', $result);
        $this->display('marketInquery');
    }

    //市场资讯邮件发送
    public function marketSend()
    {
        $seq = I('seq');
        if ("" == $seq) {
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        if (IS_POST) {
            $Market = D('Market');
            $data = $Market->find($seq);
            $mailtpl_id = I('mailtpl_id', 0, 'intval');
            $clientArr = I('clientArr');
        $marketMailTools = new MarketMailTools();
            if (false === $marketMailTools->sendMarketMail($data, $mailtpl_id, $clientArr)) {
                $this->error($marketMailTools->getError());
            }
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        } else {
            $Market = D('Market');
          // 检查市场资讯是否存在
          if (!is_array($result = $Market->find($seq))) {
              $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
              return false;
          }
          //取得所有模板
          $Mailtpl = M('Mailtpl');
            $marketMailtplArr = $Mailtpl->where(array('type'=>'MARKET'))->getField('id,name', true);
            $this->assign('marketMailtplArr', $marketMailtplArr);
          //获取客户列表
          $client = D('Client');
            if (!is_array($clientList = $client->select())) {
                $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
              return false;
            }
            $this->assign('clientList', $clientList);
          // 记录当前列表页的cookie
          Cookie('__forward1__', $_SERVER ['REQUEST_URI']);
            $this->assign('result', $result);
            $this->display('marketSend');
        }
    }
}
