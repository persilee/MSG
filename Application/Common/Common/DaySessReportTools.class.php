<?php
/**
 * Created by PhpStorm.
 * User: Per
 * Date: 17/6/23
 * Time: 18:36
 */

namespace Common\Common;
use Common\Common\ReportTools;
Vendor('PHPExcel.PHPExcel');
Vendor('PHPExcel.PHPExcel.IOFactory');

class DaySessReportTools
{
    private $filename = "";
    private $fileSimpleName = "";
    private $fileURL = "";
    private $fileDate = "";
    private $sheelName = "DaySuccessReport";
    private $excelName = "DaySuccessReport";
    private $extendName = '.xlsx';

    // 返回信息
    private $returnflg = true;
    private $returnMsg = "";

    //定义EXCEL栏位与UPLOAD数据的Mapping规则
    private $excelUploadMapping = array(
        1 => 'tenor',
    );

    // 生成利率Excel文件
    public function daySessExcelGen($code="",$date,$fileName="",$quarter="",$dir="")
    {
        if (false == $this->returnflg) {
            return false;
        }
        // 生成文件
        $objPHPExcel = new \PHPExcel();
        $objWrite = \PHPExcel_IOFactory::createWriter($objPHPExcel, C('EXCEL_SAVE_TYPE'));
        if("" == $dir) {
          if (is_array($date) && $fileName != "") {
            $dir = $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . '/Downloads/' . $fileName;
            $this->excelName = $fileName;
          }else{
            $dir = $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . '/Downloads/' . $this->excelName;
          }
        }
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        if ($this->fileDate == "") {
          if ($quarter == "") {
            if (is_array($date) && $fileName != "") {
              $this->fileDate = date('Y-m' , strtotime('-1 month'));
            }else{
              $this->fileDate = date('Y-m-d' , strtotime('-1 day'));
            }
          }else{
            if (strlen($quarter)>1) {
              $this->fileDate = $quarter;
            }else{
              $this->fileDate = date('Y') . '_0' . $quarter;
            }
          }
        }

        $this->fileURL = __ROOT__ . '/' . $this->excelName . '_' . UID . '_' . $this->fileDate . $this->extendName;
        $this->fileSimpleName = $this->excelName . '_' . UID .'_' . $this->fileDate . $this->extendName;
        $this->filename = $dir .'/'. $this->fileSimpleName;
        $objWrite->save($this->filename);
        $this->returnflg = $this->daySessExcelData("",$date,$quarter);

        //记录生成报表
        $reportTools = new ReportTools();
        $this->returnflg = $reportTools->saveReportList($code,$this->fileSimpleName);
        return $this->returnflg;
    }

    // 在已有EXCEL文件基础上写入成功发送信息数据
    public function daySessExcelData($externalFilename = "",$date,$quarter)
    {
        if ("" == $externalFilename) {
            $filename = $this->filename;
        } else {
            $filename = $externalFilename;
        }
        if (!file_exists($filename)) {
            $this->returnMsg = L('SYSTEM_ERROR_FILE_NOT_EXIST');
            $this->returnflg = false;
        }
        // 读取数据信息
        $list = $this->getDateList($date,$quarter);

        // 对现有文件添加SHEEL，并新增内容
        $objReader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $objPHPExcel = $objReader->load($filename);
        $pHPExcel_Worksheet = new \PHPExcel_Worksheet($objPHPExcel, $this->sheelName);
        $objPHPExcel->addSheet($pHPExcel_Worksheet, 0);
        $objSheel = $objPHPExcel->setActiveSheetIndex(0);
        $objSheel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheel->getDefaultStyle()->getFont()->setSize(10)->setName("微软雅黑");
        // 写文件头
        $objSheel->setCellValue("A1", "发送信息类型");
        $objSheel->getColumnDimension("A")->setWidth(30);
        $objSheel->setCellValue("B1", "消息发送渠道");
        $objSheel->setCellValue("C1", "发送日期");
        $objSheel->setCellValue("D1", "收件人");
        $objSheel->setCellValue("E1", "客户");
        $colIndex = 5;

        $objSheel->getStyle("A1:".$this->getCellsIndex($colIndex)."1")->getFont()->setBold(true);
        $objSheel->getStyle("A1:".$this->getCellsIndex($colIndex)."1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(C('EXCEL_TITLE_COLOR'));
        for ($i = 1; $i <= $colIndex; $i++) {
            $objSheel->getColumnDimension($this->getCellsIndex($i))->setWidth(30);
            $objSheel->getStyle($this->getCellsIndex($i) . "1")->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        // 写文件内容
        $rowIndex = 1; //行号
        $totalArr = array(
          'total'           =>'发送信息总数',
          'mailTotal'       =>'邮件总数',
          'smsTotal'        =>'短信总数',
          'ebsTotal'        =>'ESB发送总数',
          'ebsMailTotal'    =>'ESB邮件总数',
          'ebsSmsTotal'     =>'ESB短信总数',
          'terTotal'        =>'终端发送总数',
          'terMailTotal'    =>'终端邮件总数',
          'terSmsTotal'     =>'终端短信总数',
          'topThreeTotal'   =>'发送的邮件总数前三位/每小时',
        );
        foreach ($list as $rowKey => $rowValue) {
            $rowIndex++;
            $colIndex = 1;
            $colName = $this->getCellsIndex($colIndex);
            //获取明细数据
            foreach ($rowValue as $colKey => $colValue) {
                // 取得所在列标识
                $colIndex++;
                $colName = $this->getCellsIndex($colIndex - 1);
                if ($colKey == 'outside_flag' && $colValue == 0) {
                  $objSheel->setCellValueExplicit($colName . $rowIndex, '终端配置');
                }elseif($colKey == 'outside_flag' && $colValue == 1){
                  $objSheel->setCellValueExplicit($colName . $rowIndex, 'EBS系统');
                }elseif($colKey == 'comp_time'){
                  $objSheel->setCellValueExplicit($colName . $rowIndex, date('Y-m-d H:i:s',$colValue));
                }else {
                  $objSheel->setCellValueExplicit($colName . $rowIndex, $colValue);
                }
                $objSheel->getStyle($colName . $rowIndex)->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            }
            //获取汇总数据
            foreach ($totalArr as $key => $value) {
              if($rowKey === $key){
                $objSheel->setCellValueExplicit($colName . $rowIndex, $value);
                $objSheel->setCellValueExplicit('B' . $rowIndex, $rowValue);
                $objSheel->mergeCells( 'B'.$rowIndex.':E'.$rowIndex);
                for($i = 1; $i <= 5; $i++){
                  $colNameTemp = $this->getCellsIndex($i);
                  $objSheel->getStyle($colNameTemp . $rowIndex)->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
                }
              }
            }
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, C('EXCEL_SAVE_TYPE'));
        $objWriter->save($filename);
        return true;
    }

    //下载并删除文件
    public function excelDownload()
    {
        if (false == $this->returnflg) {
            return false;
        }
        $downloadTools = new DownloadTools();
        return $downloadTools->downloadFile($this->filename,false, $tempname);
    }

    public function getDateList($date,$quarter){

        if(empty($date)){
            $map = array('date' => date_to_int('-',date("Y-m-d",strtotime("-1 day"))));
        }elseif(is_array($date)){
            $map['date'] = array('between',$date);
        }else{
            $map = array('date' => date_to_int('-',$date));
        }
        $map['status'] = 1;
        // $map['outside_flag'] = 1;
        $Maillog = D('Maillog');
        $Smslog = D('Smslog');
        $maillogArr = $Maillog->relation(true)->where($map)->order('seq')->select();
        $smslogArr = $Smslog->relation(true)->where($map)->order('seq')->select();
        $maillogArrTemp = array();
        $smslogArrTemp = array();
        foreach($maillogArr as $key => $value){
          $maillogArrTemp[$key]['type'] = 'Mail';
          $maillogArrTemp[$key]['outside_flag'] = $value['outside_flag'];
          $maillogArrTemp[$key]['comp_time'] = $value['comp_time'];
          $maillogArrTemp[$key]['receiver'] = $value['receiver'];
          $maillogArrTemp[$key]['ci_name'] = $value['ci_name'];
        }
        foreach($smslogArr as $key => $value){
          $smslogArrTemp[$key]['type'] = 'Sms';
          $smslogArrTemp[$key]['outside_flag'] = $value['outside_flag'];
          $smslogArrTemp[$key]['comp_time'] = $value['comp_time'];
          $smslogArrTemp[$key]['receiver'] = $value['receiver'];
          $smslogArrTemp[$key]['ci_name'] = $value['ci_name'];
        }
        //明细数据
        $list = array_merge($maillogArrTemp,$smslogArrTemp);
        //汇总数据
        $topThreeTotal = array();
        $topThreeTotalList = array_merge($maillogArr,$smslogArr);
        $sTopThreeTotal = '';
        //获取季度发信息总数前三且列出前三24小时的前三，否则取每天24小时的前三
        if ($quarter != "") {
          $monthDays = cal_days_in_month(CAL_GREGORIAN, 1, date('Y'));
          $sTopThreeTotal = $this->getMonthofdays($monthDays,$topThreeTotalList,$topThreeTotal);
        }else{
          //如果是月报表取每月发信息总数前三且列出前三24小时的前三，否则取每天24小时的前三
          if (is_array($date)) {
            $monthDays = cal_days_in_month(CAL_GREGORIAN, date('m',strtotime('-1 month')), date('Y'));
            $sTopThreeTotal = $this->getMonthofdays($monthDays,$topThreeTotalList,$topThreeTotal);
          }else{
            for($i = 0; $i <= 24; $i++){
              foreach ($topThreeTotalList as $key => $value) {
                if (date('H',$value['comp_time']) == $i) {
                  $topThreeTotal[$i]++ ;
                }
              }
            }
            arsort($topThreeTotal);
            $topThreeTotal = array_slice($topThreeTotal,0,3,true);
            foreach ($topThreeTotal as $key => $value) {
              $sTopThreeTotal .= $key.'时:'.$value.' , ';
            }
          }
        }


        $mailTotal = $Maillog->where($map)->count();
        $smsTotal = $Smslog->where($map)->count();
        $total = $mailTotal + $smsTotal;
        $map['outside_flag'] = 1;
        $ebsMailTotal = $Maillog->where($map)->count();
        $ebsSmsTotal = $Smslog->where($map)->count();
        $ebsTotal = $ebsMailTotal + $ebsSmsTotal;
        $map['outside_flag'] = 0;
        $terMailTotal = $Maillog->where($map)->count();
        $terSmsTotal = $Smslog->where($map)->count();
        $terTotal = $terMailTotal + $terSmsTotal;
        $list['total'] = $total;
        $list['mailTotal'] = $mailTotal;
        $list['smsTotal'] = $smsTotal;
        $list['ebsTotal'] = $ebsTotal;
        $list['ebsMailTotal'] = $ebsMailTotal;
        $list['ebsSmsTotal'] = $ebsSmsTotal;
        $list['terTotal'] = $terTotal;
        $list['terMailTotal'] = $terMailTotal;
        $list['terSmsTotal'] = $terSmsTotal;
        $list['topThreeTotal'] = $sTopThreeTotal;
        return $list;
    }
    public function getMonthofdays($monthDays,$topThreeTotalList,$topThreeTotal){
      $temp = 0;
      $tempArr = array();
      //获取每月每天24小时发信息总数的数组
      for ($j=0; $j <= $monthDays ; $j++) {
        foreach ($topThreeTotalList as $key => $value) {
          if (date('d',$value['comp_time']) == $j) {
            for($i = 1; $i <= 24; $i++){
              if (date('H',$value['comp_time']) == $i) {
                $topThreeTotal[$j][$i]++ ;
              }
            }
          }
        }
      }
      //获取每月每天发信息总数数组进行排序，且取前三位
      foreach ($topThreeTotal as $key => $value) {
        foreach ($value as $vk => $vv) {
          $temp += $topThreeTotal[$key][$vk];
        }
        $tempArr[$key] = $temp;
        $temp = 0;
      }
      arsort($tempArr);
      $tempArr = array_slice($tempArr,0,3,true);
      //拼接每月发信息总数前三且列出前三24小时的前三的字符串
      foreach ($tempArr as $tk => $tv) {
        foreach ($topThreeTotal as $key => $value) {
          if ($key == $tk) {
            foreach ($value as $vk => $vv) {
              $sTopThreeTotal .= $vk . '时：' . $vv .' , ';
            }
            $str .= $tk . '日:'. $tv . '（' . $sTopThreeTotal . '）; ';
            $sTopThreeTotal = '';
          }
        }
      }
      return $sTopThreeTotal = $str;
    }
    // 取得验证错误信息
    public function getError()
    {
        return $this->returnMsg;
    }

    // 取得文件名称
    public function getExcelFileName()
    {
        return $this->filename;
    }

    // 取得文件URL
    public function getExcelFileURL()
    {
        return $this->fileURL;
    }

    public function getExcelToArrayMapping($index)
    {
        return $this->excelUploadMapping[$index];
    }

    // 取得单元格所在列的字母
    private function getCellsIndex($index)
    {
        $arr = range('A', 'Z');
        return $arr[$index - 1];
    }
}
