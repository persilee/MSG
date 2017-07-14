<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/8
 * Time: 15:14
 */

namespace Common\Common;
Vendor('PHPExcel.PHPExcel');
Vendor('PHPExcel.PHPExcel.IOFactory');

class AutoInterestRateUpload extends AutoTaskTools
{
    const MAILTPL_TYPE_RATE_UPLOAD         = 'INTRATEUPL';   //利率上传通知邮件业务类型

    public function exec(){
        $runFlag = true;
        while ($runFlag) {
            $this->echoMsg('H');
            //==============取得上传文件的参数、文件目录、文件名称===============//
            $filename = C('INT_RATE_UPLOAD_FILE');
            $warning_spread = C('INT_RATE_WARNING_SPREAD');
            $tonerArr = C('TENOR_ARRAY');
            $mail_receiver = C('INT_RATE_UPLOAD_RECEIVER');
            //=============================================//
            if(file_exists($filename)){
                continue;
            }elseif(file_exists($filename.".xls")){
                $filename .= ".xls";
            }else{
                $filename .= ".xlsx";
            }
            if (file_exists($filename) && $this->checkTask('AutoInterestRateUpload')) {
                //写文件处理开始log
                $this->echoMsg('C', "File upload start ");
                $fileType = \PHPExcel_IOFactory::identify($filename);
                $objReader = \PHPExcel_IOFactory::createReader($fileType);
                $objReader->setLoadSheetsOnly(true);
                $objPHPExcel = $objReader->load($filename);
                $rateTools = new RateTools();
                $Rate = M('Rate');
                $ccyArr = array();
                $returnArr = array();
                foreach ($objPHPExcel->getWorksheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        foreach ($row->getCellIterator() as $key => $cell) {
                            if($row->getRowIndex() == 1){
                                $ccyArr[] = $cell->getValue();
                            }elseif($key == 0) {
                                $tenorValue = $cell->getValue();
                            }else{
                                $rateValue = $cell->getValue() * 100;
                                $rateValue = sprintf("%.8f",$rateValue);
                                $map = array(
                                    'date'   => date_to_int('-',date('Y-m-d'))+0,
                                    'tenor'  => $tenorValue,
                                    'ccy'    => $ccyArr[$key],
                                );
                                if(is_array($result = $Rate->where($map)->find())){
                                    $returnCode = $rateTools->updateRate($result['date'], $result['seq'], $rateValue);
                                }else{
                                    $returnCode = $rateTools->addRate(date('Y-m-d'),$tenorValue,$ccyArr[$key],$rateValue);
                                }
                                if(false === $returnCode){
                                    $returnArr[] = array($tenorValue,$ccyArr[$key],$rateTools->getError());
                                }else{
                                    //如果利率没有指定,则给出提示信息
                                    if(empty($rateValue)){
                                        $returnArr[] = array($tenorValue,$ccyArr[$key],L('CLIENT_ERROR_INTEREST_NOT_INPUT'));
                                    }
                                    //如果利差大于参数设置,则出提示信息
                                    $lastDateRate = $Rate->where(array('date'=>array('LT',date_to_int('-',date('Y-m-d'))),'tenor'=>$tenorValue,'ccy'=>$ccyArr[$key]))->order('date DESC')->getField('rate');
                                    if($lastDateRate === null){
                                        $lastDateRate = 0;
                                    }
                                    if(array_key_exists($ccyArr[$key],$warning_spread)){
                                        $spreadWarningRate = $warning_spread[$ccyArr[$key]];
                                    }elseif(array_key_exists('ALL',$warning_spread)){
                                        $spreadWarningRate = $warning_spread['ALL'];
                                    }else{
                                        $spreadWarningRate = false;
                                    }
                                    if((false !== $spreadWarningRate) && (abs($lastDateRate - $rateValue) > $spreadWarningRate)){
                                        $returnArr[] = array($tenorValue,$ccyArr[$key],L('CLIENT_ERROR_SPREAD_TOO_MUCH'));
                                    }
                                }
                            }
                        }
                    }
                }
                //删除上传文件
                unlink($filename);
                //==================发出通知邮件======================//
                //取得利率上传邮件模板
                $Mailtpl = M('mailtpl');
                $mailtpl_id = $Mailtpl->where(array('type'=>self::MAILTPL_TYPE_RATE_UPLOAD))->getField('id');
                if($mailtpl_id) {
                    //组织当天上传后的结果信息
                    $Currency = M('currency');
                    $ccyArr = $Currency->where(array('status' => 1))->order('sort')->getField('id', true);
                    $rateArr = $Rate->where(array('date' => date_to_int('-', date('Y-m-d'))))->select();
                    $clientRateArr = array();
                    foreach ($rateArr as $rateResult) {
                        $clientRateArr[$rateResult['tenor']][$rateResult['ccy']] = $rateResult['rate'];
                    }
                    //将利率数组转为TABLE格式
                    if (count($tonerArr) > 0 && count($ccyArr) > 0) {
                        $rateTableStr = "<table width='100%' border='1'><thead><tr><td></td>";
                        //写表格头
                        foreach ($ccyArr as $ccyValue) {
                            $rateTableStr .= "<th>" . $ccyValue . "</th>";
                        }
                        $rateTableStr .= "</tr></thead><tbody>";
                        //写表格体
                        foreach ($tonerArr as $tonerKey => $tonerVal) {
                            $rateTableStr .= "<tr><td>" . $tonerKey . "</td>";
                            foreach ($ccyArr as $ccyValue) {
                                if (isset($clientRateArr[$tonerKey][$ccyValue])) {
                                    $rateArr = explode('.', $clientRateArr[$tonerKey][$ccyValue]);
                                    $rateValue = $rateArr[0].".".substr($rateArr[1],0,2);
                                    //$decimalDigits = 2 - strlen($rateArr[1]);
                                    //for ($i = 0; $i < $decimalDigits; $i++) {
                                    //    $rateValue .= '0';
                                    //}
                                    //如果有设置,则添加数值
                                    $rateTableStr .= "<td align='center'>" . $rateValue . "%</td>";
                                } else {
                                    //如果没有设置,则添加空格
                                    $rateTableStr .= "<td></td>";
                                }
                            }
                            $rateTableStr .= "</tr>";
                        }
                        $rateTableStr .= "</tbody></table>";
                    } else {
                        $rateTableStr = "";
                    }
                    //组织上传提示信息邮件内容
                    if (!empty($returnArr)) {
                        $returnTableStr = "<table width='100%' border='1'><thead><tr><th>Tenor</th><th>Currency</th><th>Message</th></thead><tbody>";
                        foreach ($returnArr as $returnResult) {
                            $returnTableStr .= "<tr>";
                            $returnTableStr .= "<td>" . $returnResult[0] . "</td>";
                            $returnTableStr .= "<td>" . $returnResult[1] . "</td>";
                            $returnTableStr .= "<td>" . $returnResult[2] . "</td>";
                            $returnTableStr .= "</tr>";
                        }
                        $returnTableStr .= "</tbody></table>";
                    } else {
                        $returnTableStr = "";
                    }
                    //组装邮件内容
                    $content = array(
                        'date' => date('Y-m-d'),
                        'rate' => $rateTableStr,
                        'message' => $returnTableStr,
                    );
                    $this->_mailTools = new MailTools();
                    if (false === $this->_mailTools->prepareMail($mailtpl_id, $content)) {
                        $this->_errorMsg = $this->_mailTools->getError();
                        $returnFlag = false;
                    } else {
                        //取得客户收邮件地址数组、邮件暗送地址
                        $reciverArr = explode(';', $mail_receiver);
                        $returnFlag = true;
                        //foreach ($reciverArr as $value) {
                            if (false === $this->_mailTools->sendMail($reciverArr, MailTools::MAIL_SEND_ROUTE_TERMINAL)) {
                                $this->_errorMsg = $this->_mailTools->getError();
                                $returnFlag = false;
                            }
                        //}
                    }
                    //记录邮件LOG
                    MaillogTools::mailLog(MaillogTools::EMAIL_INSIDE, $mailtpl_id, 0, $mail_receiver, $returnFlag, time(), $this->_mailTools->getMailFile(), $this->_errorMsg);
                }
                //==================通知邮件发出完成===================//
                //写处理结束日志
                $this->echoMsg('C', "File upload complete ");
            }
            //写单次执行完成log
            $this->echoMsg('E');
            if(date('H:i') > '23:20'){
                $runFlag = false;
            }else{
                sleep(1200);
            }
        }
        return true;
    }
}
