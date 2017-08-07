<?php
/**
 * Created by PhpStorm.
 * User: per
 * Date: 17/6/18
 * Time: 15:14
 */

namespace Common\Common;

Vendor('PHPExcel.PHPExcel');
Vendor('PHPExcel.PHPExcel.IOFactory');

class AutoExRateUpload extends AutoTaskTools
{
    const MAILTPL_TYPE_RATE_UPLOAD         = 'EXRATEUPL';   //汇率上传通知邮件业务类型

    public function exec()
    {
        $runFlag = 'true';
        while ($runFlag == 'true') {
            $this->echoMsg('H');
            //==============取得上传文件的参数、文件目录、文件名称===============//
            $filename = C('EX_RATE_UPLOAD_FILE');
            $warning_spread = C('EX_RATE_WARNING_SPREAD');
            $exrateArr = C('EX_ARRAY');
            $mail_receiver = C('EX_RATE_UPLOAD_RECEIVER');
            //=============================================//
            if (file_exists($filename)) {
                // continue;
            } elseif (file_exists($filename.".xls")) {
                $filename .= ".xls";
            } else {
                $filename .= ".xlsx";
            }
            if (file_exists($filename) && $this->checkTask('AutoExRateUpload')) {
                //写文件处理开始log
                $this->echoMsg('C', "File upload start ");
                $fileType = \PHPExcel_IOFactory::identify($filename);
                $objReader = \PHPExcel_IOFactory::createReader($fileType);
                $objReader->setLoadSheetsOnly(true);
                $objPHPExcel = $objReader->load($filename);
                $exRateTools = new ExRateTools();
                $ExRate = M('exrate');
                $exRateParameter = $exRateTools->getExParameterArr();
                $targetCcyArr = array();
                $returnArr = array();
                //==============取得参数设置的利差===============//
                $warning_spread = C('EX_RATE_WARNING_SPREAD');
                //=============================================//
                foreach ($objPHPExcel->getWorksheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        foreach ($row->getCellIterator() as $key => $cell) {
                            if ($row->getRowIndex() == 1) {
                                $targetCcyArr[] = $cell->getValue();
                            } elseif ($key == 0) {
                                $exchangeCcyValue = $cell->getValue();
                            } else {
                                //判断是否是同种货币，如果是同种货币提示，且强制设置为same
                              if ($targetCcyArr[$key] == $exchangeCcyValue) {
                                  if ($cell->getValue() == 'false' || $cell->getValue() == '' || $cell->getValue() == null) {
                                      $exRateValue = 'same';
                                  } else {
                                      $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('CLIENT_ERROR_EXRATE_NOT_REPEAT'));
                                  }
                              } else {
                                //判断货币对是否存在
                                  if (isset($exRateParameter[$exchangeCcyValue][$targetCcyArr[$key]])) {
                                    $tempArr = $exRateParameter[$exchangeCcyValue][$targetCcyArr[$key]];
                                    $point = $tempArr['value'];
                                    $exRateValue = $cell->getValue();
                                    if (!empty($exRateValue)) {
                                      //获取保留的小数位数，小数位不够自动补0
                                      $exRateValue = sprintf("%.".$point."f", $exRateValue);
                                    }

                                    $map = array(
                                      'date'          => date_to_int('-',date('Y-m-d'))+0,
                                      'exchange_ccy'  => $exchangeCcyValue,
                                      'target_ccy'    => $targetCcyArr[$key],
                                  );
                                }elseif($cell->getValue() != 'false'){
                                  $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('SYSTEM_ERROR_NOT_SPECIFY_CURRENCY'));
                                }
                              }
                              if ($exRateValue === floatval(0) || (!empty($exRateValue) && $exRateValue  != null && $exRateValue != 'same')) {
                                if (is_array($result = $ExRate->where($map)->find())) {
                                    $returnCode = $exRateTools->updateExRate($result['date'], $result['seq'], $exRateValue);
                                } else {
                                    $returnCode = $exRateTools->addExRate(date('Y-m-d'), $exchangeCcyValue, $targetCcyArr[$key], $exRateValue);
                                }
                              }
                                if (false === $returnCode) {
                                    $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],$exRateTools->getError());
                                    $returnCode = true;
                                } else {
                                    //货币对存在的情况下，如果汇率没有指定,则给出提示信息
                                    if (isset($exRateParameter[$exchangeCcyValue][$targetCcyArr[$key]])) {
                                      if (empty($exRateValue) && $exRateValue != 'same') {
                                          $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('CLIENT_ERROR_EXRATE_NOT_INPUT'));
                                      }
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
                                        $returnArr[] = array($exchangeCcyValue,$targetCcyArr[$key],L('CLIENT_ERROR_EX_SPREAD_TOO_MUCH'));
                                    }
                                }
                            }
                        }
                    }
                }
                unlink($filename);
                //==================发出通知邮件======================//
                //取得汇率上传邮件模板
                $Mailtpl = M('mailtpl');
                $mailtpl_id = $Mailtpl->where(array('type'=>self::MAILTPL_TYPE_RATE_UPLOAD))->getField('id');
                if ($mailtpl_id) {
                    //组织当天上传后的结果信息
                    $Currency = M('currency');
                    $targetCcyArr = $Currency->where(array('status' => 1))->order('sort')->getField('id', true);
                    $exRateArr = $ExRate->where(array('date' => date_to_int('-', date('Y-m-d'))))->select();
                    $clientExRateArr = array();
                    foreach ($exRateArr as $exRateResult) {
                        $clientExRateArr[$exRateResult['exchange_ccy']][$exRateResult['target_ccy']] = $exRateResult['ex_rate'];
                    }
                    //将汇率数组转为TABLE格式
                    if (count($exchangeCcyArr) > 0 && count($targetCcyArr) > 0) {
                        $exRateTableStr = "<table width='100%' border='1'><thead><tr><td></td>";
                        //写表格头
                        foreach ($targetCcyArr as $ccyValue) {
                            $exRateTableStr .= "<th>" . $ccyValue . "</th>";
                        }
                        $exRateTableStr .= "</tr></thead><tbody>";
                        //写表格体
                        foreach ($exchangeCcyArr as $exchangeCcyKey => $exchangeCcyVal) {
                            $exRateTableStr .= "<tr><td>" . $exchangeCcyKey . "</td>";
                            foreach ($targetCcyArr as $ccyValue) {
                                if (isset($clientExRateArr[$exchangeCcyKey][$ccyValue])) {
                                    $exRateArr = explode('.', $clientExRateArr[$exchangeCcyKey][$ccyValue]);
                                    $exRateValue = $exRateArr[0].".".substr($exRateArr[1], 0, 2);
                                    //如果有设置,则添加数值
                                    $exRateTableStr .= "<td align='center'>" . $exRateValue . "%</td>";
                                } else {
                                    //如果没有设置,则添加空格
                                    $exRateTableStr .= "<td></td>";
                                }
                            }
                            $exRateTableStr .= "</tr>";
                        }
                        $exRateTableStr .= "</tbody></table>";
                    } else {
                        $exRateTableStr = "";
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
                        'ex_rate' => $exRateTableStr,
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
            if (date('H:i') > '23:20') {
                $runFlag = 'false';
            } else {
                sleep(1200);
            }
        }
        return true;
    }
}
