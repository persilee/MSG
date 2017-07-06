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

class DayErrorReportTools
{
    private $filename = "";
    private $fileSimpleName = "";
    private $fileURL = "";
    private $fileDate = "";
    private $sheelName = "DayErrorReport";
    private $excelName = "DayErrorReport";
    private $extendName = '.xlsx';

    // 返回信息
    private $returnflg = true;
    private $returnMsg = "";

    //定义EXCEL栏位与UPLOAD数据的Mapping规则
    private $excelUploadMapping = array(
        1 => 'tenor',
    );

    // 生成利率Excel文件
    public function dayErrorExcelGen($code="",$date,$fileName="",$quarter="",$dir="")
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
        $this->fileURL = __ROOT__ . '/' . $this->excelName . '_' . UID . '_' .$this->fileDate. $this->extendName;
        $this->fileSimpleName = $this->excelName . '_' . UID .'_' . $this->fileDate . $this->extendName;
        $this->filename = $dir .'/'. $this->fileSimpleName;
        $objWrite->save($this->filename);
        $this->returnflg = $this->dayErrorExcelData("",$date);

        //记录生成报表
        $reportTools = new ReportTools();
        $this->returnflg = $reportTools->saveReportList($code,$this->fileSimpleName);
        return $this->returnflg;
    }

    // 在已有EXCEL文件基础上写入失败发送信息数据
    public function dayErrorExcelData($externalFilename = "",$date)
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
        $list = $this->getDateList($date);

        // 对现有文件添加SHEEL，并新增内容
        $objReader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $objPHPExcel = $objReader->load($filename);
        $pHPExcel_Worksheet = new \PHPExcel_Worksheet($objPHPExcel, $this->sheelName);
        $objPHPExcel->addSheet($pHPExcel_Worksheet, 0);
        $objSheel = $objPHPExcel->setActiveSheetIndex(0);
        $objSheel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheel->getDefaultStyle()->getFont()->setSize(10)->setName("微软雅黑");
        // 写文件头
        $objSheel->setCellValue("A1", "发送顺序号");
        $objSheel->getColumnDimension("A")->setWidth(30);
        $objSheel->setCellValue("B1", "接收人");
        $objSheel->setCellValue("C1", "类型（短信/邮件）");
        $objSheel->setCellValue("D1", "模板名称");
        $objSheel->setCellValue("E1", "发送渠道");
        $objSheel->setCellValue("F1", "发送时间");
        $objSheel->setCellValue("G1", "发送人");
        $objSheel->setCellValue("H1", "错误原因");
        $colIndex = 8;

        $objSheel->getStyle("A1:".$this->getCellsIndex($colIndex)."1")->getFont()->setBold(true);
        $objSheel->getStyle("A1:".$this->getCellsIndex($colIndex)."1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(C('EXCEL_TITLE_COLOR'));
        for ($i = 1; $i <= $colIndex; $i++) {
            $objSheel->getColumnDimension($this->getCellsIndex($i))->setWidth(30);
            $objSheel->getStyle($this->getCellsIndex($i) . "1")->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        // 写文件内容
        $rowIndex = 1; //行号
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
                }elseif($colKey == 'seq'){
                  $objSheel->setCellValueExplicit($colName . $rowIndex, $rowIndex - 1);
                }elseif($colKey == 'outside_flag' && $colValue == 1){
                  $objSheel->setCellValueExplicit($colName . $rowIndex, 'EBS系统');
                }elseif($colKey == 'comp_time'){
                  $objSheel->setCellValueExplicit($colName . $rowIndex, date('Y-m-d H:i:s',$colValue));
                }else {
                  $objSheel->setCellValueExplicit($colName . $rowIndex, $colValue);
                }
                $objSheel->getStyle($colName . $rowIndex)->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
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
        $tempname = $this->excelName . '_' . $this->year . '-' . $this->month . $this->extendName;
        return $downloadTools->downloadFile($this->filename, true, $tempname);
    }

    public function getDateList($date){
        if(empty($date)){
            $map = array('date' => date_to_int('-',date("Y-m-d",strtotime("-1 day"))));
        }elseif(is_array($date)){
            $map['date'] = array('between',$date) ;
        }else{
            $map = array('date' => date_to_int('-',$date));
        }
        $map['status'] = 0;
        // $map['outside_flag'] = 1;
        $Maillog = D('Maillog');
        $Smslog = D('Smslog');
        $maillogArr = $Maillog->relation(true)->where($map)->order('seq')->select();
        $smslogArr = $Smslog->relation(true)->where($map)->order('seq')->select();
        $maillogArrTemp = array();
        $smslogArrTemp = array();
        foreach($maillogArr as $key => $value){
          $maillogArrTemp[$key]['seq'] = $value['seq'];
          $maillogArrTemp[$key]['receiver'] = $value['receiver'];
          $maillogArrTemp[$key]['type'] = 'Mail';
          $maillogArrTemp[$key]['mailtpl_name'] = $value['mailtpl_name'];
          $maillogArrTemp[$key]['outside_flag'] = $value['outside_flag'];
          $maillogArrTemp[$key]['comp_time'] = $value['comp_time'];
          $maillogArrTemp[$key]['emp_name'] = $value['emp_name'];
          $maillogArrTemp[$key]['error_msg'] = $value['error_msg'];
        }
        foreach($smslogArr as $key => $value){
          $smslogArrTemp[$key]['seq'] = $value['seq'];
          $smslogArrTemp[$key]['receiver'] = $value['receiver'];
          $smslogArrTemp[$key]['type'] = 'Sms';
          $smslogArrTemp[$key]['mailtpl_name'] = $value['mailtpl_name'];
          $smslogArrTemp[$key]['outside_flag'] = $value['outside_flag'];
          $smslogArrTemp[$key]['comp_time'] = $value['comp_time'];
          $smslogArrTemp[$key]['emp_name'] = $value['emp_name'];
          $smslogArrTemp[$key]['error_msg'] = $value['error_msg'];
        }
        //明细数据
        $list = array_merge($maillogArrTemp,$smslogArrTemp);
        return $list;
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
