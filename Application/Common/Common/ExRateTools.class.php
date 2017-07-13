<?php
/**
 * Created by PhpStorm.
 * User: per
 * Date: 17/5/24
 * Time: 21:56
 */

namespace Common\Common;
Vendor('PHPExcel.PHPExcel');
Vendor('PHPExcel.PHPExcel.IOFactory');

/**==================================================================================
 * 1. addExRate
 * 2. updateExRate
 * ==================================================================================
 */

class ExRateTools
{
    private $filename = "";
    private $fileSimpleName = "";
    private $fileURL = "";
    private $sheelName = "ExchangeRateFileTemp";
    private $excelName = "ExchangeRateFileTemp";
    private $extendName = '.xlsx';

    // 返回信息
    private $returnflg = true;
//    private $returnCode = 0;
    private $returnMsg = "";
//    private $returnMsg = array(
//        'ERR0002' => '请指定利率信息',
//        'ERR0102' => '利率格式有误',
//        'ERR0103' => '日期格式有误',
//        'ERR0104' => '请指定货币',
//        'ERR0105' => '货币未定义',
//        'ERR0106' => '存期未定义',
//        'ERR0202' => '文件不存在',
//        'ERR0301' => '没有更新信息',
//        'ERR9999' => 'Error',//自由格式，可以自定义错误信息并给本栏位赋值
//    );

    //定义EXCEL栏位与UPLOAD数据的Mapping规则
    private $excelUploadMapping = array(
        1 => 'Convertible',
    );

    /**
     * 新增利率记录
     * @param $date           上传日期:yyyy-mm-dd格式
     * @param $exchangeCcy    兑换货币:string
     * @param $targetCcy      目标货币：string
     * @param $exRate         汇率:11,8
     * @param $remark         备注:string
     * @return bool
     */
    public function addExRate($date,$exchangeCcy,$targetCcy,$exRate,$remark=""){
        //载入配置项
        // ConfigTools::init();
        //上传日期检查
        if (empty($date)) {
            $this->returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }elseif(!date_check('-',$date)){
            $this->returnMsg = L('SYSTEM_ERROR_DATE_FORMAT');
            return false;
        }else{
            $date = date_to_int('-',$date);
        }
        //兑换货币检查
        if(!array_key_exists($exchangeCcy,C('EX_ARRAY'))){
            $this->returnMsg = L('SYSTEM_ERROR_EXCCY_NOT_EXIST');
            return false;
        }
        //目标货币检查
        if(empty($targetCcy)){
            $this->returnMsg = L('SYSTEM_ERROR_NOT_TARCCY_CURRENCY');
            return false;
        }else{
            $currencyTool = new CurrencyTool();
            $ccyArr = $currencyTool->getCcyArr(1);
            if(!array_key_exists($targetCcy,$ccyArr)){
                $this->returnMsg = L('SYSTEM_ERROR_TARCCY_NOT_EXIST');
                return false;
            }
        }
        //汇率输入检查
        if(empty($exRate)){
            $exRate = 0;
//            $this->returnMsg = L('CLIENT_ERROR_INTEREST_NOT_INPUT');
//            return false;
        }elseif(!RegexTools::amountCheck($exRate,8,3,true)){
            $this->returnMsg = L('CLIENT_ERROR_EXRATE_FORMAT_ERR');
            return false;
        }
        //检查当前日期是否已经存在相应兑换货币及目标货币记录
        $ExRate = M('exrate');
        if($ExRate->where(array('date'=>$date,'exchange_ccy'=>$exchangeCcy,'target_ccy'=>$targetCcy))->count() > 0){
            $this->returnMsg = L('SYSTEM_ERROR_RECORD_EXIST');
            return false;
        }
        //取得当前日期最大SEQ
        $seq = $ExRate->where(array('date'=>$date))->Max('seq');
        //准备数据进行写入
        $data = array(
            'date'               => $date,
            'seq'                => ++$seq,
            'exchange_ccy'       => $exchangeCcy,
            'target_ccy'         => $targetCcy,
            'ex_rate'            => $exRate,
            'remark'             => $remark,
            'update_emp'         => UID,
            'time'               => NOW_TIME,
            'error_msg'          => '',
        );
        if($ExRate->add($data)){
            return true;
        }else{
            $this->returnMsg = $ExRate->getError();
            return false;
        }
    }

    /**
     * 修改汇率记录
     * @param $dateInt   上传日期:intval格式
     * @param $seq             顺序号:int
     * @param $exRate          汇率:11,8
     * @param $remark          备注:string
     * @return bool
     */
    public function updateExRate($dateInt,$seq, $exRate, $remark="")
    {
        if (empty($dateInt) || empty($seq)) {
            $this->returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');
            return false;
        }
        if(empty($exRate)){
//            $this->returnMsg = L('CLIENT_ERROR_INTEREST_NOT_INPUT');
//            return false;
            $exRate = 0;
        }elseif(!RegexTools::amountCheck($exRate,8,3,true)){
            $this->returnMsg = L('CLIENT_ERROR_EXRATE_FORMAT_ERR');
            return false;
        }
        $ExRate = M('exrate');
        if (!is_array($exRateResult = $ExRate->where(array('date' => $dateInt, 'seq' => $seq))->find())) {
            $this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
            return false;
        }
        // 取得原记录
        $exRateResult['ex_rate']         = $exRate;
        $exRateResult['remark']          = $remark;
        //$rateResult['update_emp']   = UID;
        //$rateResult['time']         = NOW_TIME;
        if ($rtnCode = $ExRate->save($exRateResult)) {
            $ExRate->where(array('date' => $dateInt, 'seq' => $seq))->setField('update_emp',UID);
            $ExRate->where(array('date' => $dateInt, 'seq' => $seq))->setField('time',time());
            return true;
        } elseif(0 === $rtnCode){
            $this->returnMsg = L('CLIENT_ERROR_EXRATE_NOT_UPD');
            return false;
        }else {
            $this->returnMsg = $ExRate->getError();
            return false;
        }
        return true;
    }

    // 生成汇率Excel文件
    public function exRateExcelGen($date,$dir="")
    {
        if (false == $this->returnflg) {
            return false;
        }
        // 生成文件
        $objPHPExcel = new \PHPExcel();
        $objWrite = \PHPExcel_IOFactory::createWriter($objPHPExcel, C('EXCEL_SAVE_TYPE'));
        if("" == $dir) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . '/Downloads/';
        }
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $this->fileURL = __ROOT__ . '/' . $this->excelName . '_' . UID . '_' .$date. $this->extendName;
        $this->fileSimpleName = $this->excelName . '_' . UID .'_' . $date . $this->extendName;
        $this->filename = $dir .'/'. $this->fileSimpleName;
        $objWrite->save($this->filename);
        $this->returnflg = $this->exRateExcelData("",$date);
        return $this->returnflg;
    }

    // 在已有EXCEL文件基础上写入汇率信息
    public function exRateExcelData($externalFilename = "",$date)
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
        $list = $this->getExDateList($date);

        // 对现有文件添加SHEEL，并新增内容
        $objReader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $objPHPExcel = $objReader->load($filename);
        $pHPExcel_Worksheet = new \PHPExcel_Worksheet($objPHPExcel, $this->sheelName);
        $objPHPExcel->addSheet($pHPExcel_Worksheet, 0);
        $objSheel = $objPHPExcel->setActiveSheetIndex(0);
        $objSheel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objSheel->getDefaultStyle()->getFont()->setSize(10)->setName("微软雅黑");
        // 写文件头
        $objSheel->setCellValue("A1", "Convertible");
        $colIndex = 1;
        //按ccy参数组合队列
        $currencyTool = new CurrencyTool();
        $ccyArr = $currencyTool->getCcyArr(1);
        foreach ($ccyArr as $ccy_key => $ccy_value){
            $colIndex++;
            $objSheel->setCellValue($this->getCellsIndex($colIndex)."1", $ccy_key);
        }
        $objSheel->getStyle("A1:".$this->getCellsIndex($colIndex)."1")->getFont()->setBold(true);
        $objSheel->getStyle("A1:".$this->getCellsIndex($colIndex)."1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(C('EXCEL_TITLE_COLOR'));
        for ($i = 1; $i <= $colIndex; $i++) {
            $objSheel->getStyle($this->getCellsIndex($i) . "1")->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        }
        // 写文件内容
        $rowIndex = 1; //行号
        foreach ($list as $rowKey => $rowValue) {
            $rowIndex++;
            $colIndex = 1;
            $colName = $this->getCellsIndex($colIndex);
            $objSheel->setCellValue($colName . $rowIndex, $rowKey);
            foreach ($rowValue as $colValue) {
                // 取得所在列标识
                $colIndex++;
                $colName = $this->getCellsIndex($colIndex);
                //$objSheel->setCellValueExplicit($colName . $rowIndex, $colValue, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                $objSheel->setCellValueExplicit($colName . $rowIndex, $colValue);
                $objSheel->getStyle($colName . $rowIndex)->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            }
        }
        for ($i = 1; $i <= $rowIndex; $i++) {
            for ($j = 1; $j <= $colIndex; $j++) {
                $objSheel->getStyle($this->getCellsIndex($j) . $i)->getBorders()->getOutline()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
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
        return $downloadTools->downloadFile($this->filename,true, $tempname);
    }

    public function getExDateList($date){
      if(empty($date)){
          $map = array('date' => date_to_int('-',date('d-m-Y')));
      }else{
          $map = array('date' => date_to_int('-',$date));
      }
      $exrate = D('exrate');
      $exRateArr = $exrate->where($map)->order('exchange_ccy,target_ccy')->select();
      $list = $this->getList();
      $exRateParameterArr = $this->getExParameterArr();
      //向框架中填充数据
      foreach($exRateArr as $exRate_value){
          $list[$exRate_value['exchange_ccy']][$exRate_value['target_ccy']] = $exRate_value['ex_rate'];
      }
      foreach ($list as $key => $value) {
        foreach ($value as $vk => $vv) {
          if (!isset($exRateParameterArr[$key][$vk])) {
              $list[$key][$vk] = "false";
          }
        }
      }
      return $list;
    }
    public function getExParameterArr(){
      $Config = M('Config');
      $map = array(
        'name'   => 'EXRATE_PARAMETER',
      );
      $configArr = $Config->where($map)->find();
      $exRateParameterArr = json_decode($configArr['value'] ,true);
      return $exRateParameterArr;
    }
    //取得空的兑换货币与目标货币的框架集合
    public function  getList(){
        //按ccy参数组合队列
        $currencyTool = new CurrencyTool();
        $targetCcyArr = $currencyTool->getCcyArr(1);
        //按配置的兑换货币与货币组合数据框架
        $list = array();
        foreach (C('EX_ARRAY') as $exchangeCcy_key => $exchangeCcy_value){
            foreach ($targetCcyArr as $targetCcyArr_key => $targetCcyArr_value){
              if ($exchangeCcy_key == $targetCcyArr_key) {
                $list[$exchangeCcy_key][$targetCcyArr_key] = "false";
              }else{
                $list[$exchangeCcy_key][$targetCcyArr_key] = "";
              }
            }
        }
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
