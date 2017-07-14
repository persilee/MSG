<?php
/**
 * Created by Sublime Text.
 * User: Hipr
 * Date: 2015/11/03
 * Time: 16:43
 * 功能包含：系统日历管理模块、系统货币参数管理
 * 修改历史：
 *       日期           修改人             修改功能
 *    2015/12/13       林海宾            xxxxxxxxxx
 */

namespace Admin\Controller;
use Common\Common\CalendarTools;
use Common\Common\CurrencyTool;
use Common\Common\ExRateTools;
use Common\Common\LogTools;

Vendor('PHPExcel.PHPExcel');
Vendor('PHPExcel.PHPExcel.IOFactory');

class ParameterController extends AdminController{
    /*================================================================================================*/
    /*  系统日历管理模块
    /*      功能清单：
    /*          01-calendarList      ： 系统日历浏览
    /*          02-calendarSign      ： 系统日历标记（标记假期及工作日、取消原有标记）
    /*          03-calendarUpload    ： 系统日历上传
    /*================================================================================================*/
    public function calendarList(){
        if(IS_POST){
            //$calendar_code = I('calendar_code');
            $startDate = I('start');
            $endDate = I('end');
            // 输入检查
//            if("" == $calendar_code){
//                $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
//            }
            if(!date_check("-",$startDate)){
                $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
            }else{
                $startDate = date_to_int('-',$startDate);
            }
            if(!date_check("-",$endDate)){
                $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1003');
            }else{
                $endDate = date_to_int('-',$endDate);
            }

            $Calendar = M('Calendar');
            $map = array(
//                'calendar_code' => $calendar_code,
                'date'=>array('between',array($startDate,$endDate-1)),
            );
            $calenderArr = $Calendar->where($map)->select();
            // 组织数据返回，格式如下：
            // {
            //     title: 'All Day Event',
            //     start: new Date(y, m, 1),
            //     backgroundColor: "#f56954", //red
            //     borderColor: "#f56954" //red
            // },
            $returnArr = array();
            foreach ($calenderArr as $key => $value) {
                $returnEle = array();
                $returnEle['title'] = L('TEMP_PARM_CALENDAR_FLAG_TEXT')[$value['flag']];
                if(0 == $value['flag']){
                    //工作日
                    $returnEle['backgroundColor'] =  "#f39c12";
                    $returnEle['borderColor'] =  "#f39c12";
                }elseif(1 == $value['flag']){
                    $returnEle['backgroundColor'] =  "#00a65a";
                    $returnEle['borderColor'] =  "#00a65a";
                }else{
                    $returnEle['backgroundColor'] =  "#F264A8";
                    $returnEle['borderColor'] =  "#F264A8";
                }
                $returnEle['start'] = day_format($value['date'],'Y-m-d');
                $returnArr[] = $returnEle;
            }
            $returnStr = json_encode($returnArr,true);
            $this->ajaxReturn($returnStr);
        }else{
            // 记录当前列表页的cookie
            Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
            $this->display('calendarList');
        }
    }

    // 系统日历标记
    public function calendarSign(){
//        $calendar_code = I('calendar_code');
        $date = I('date');
        $flag = I('flag');
        $calendarTools = new CalendarTools();
        if(false === $calendarTools->calendarSign($date,$flag)){
            $this->error($calendarTools->getError());
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'));
    }

    //系统日历上传
    public function calendarUpload(){
        if(IS_POST){
            $return  = array('status' => 1, 'info' => L('SYSTEM_ACTION_UPLOAD_SUCCESS'), 'data' => '');
            // 读取上传文件并写表
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     2000000 ;// 设置附件上传大小
            $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类型
            $upload->rootPath  =     $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/'; // 设置附件上传目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            $upload->autoSub   =     false;// 上传文件
            $info   =   $upload->uploadOne($_FILES['temp_file']);
            if(!$info) {// 上传错误提示错误信息
                $return['status'] = 0;
                $return['info']   = $upload->getError();
            }else{// 上传成功 获取上传文件信息
                $filename = $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/'.$info['savename'];
                $fileType = \PHPExcel_IOFactory::identify($filename);
                $objReader = \PHPExcel_IOFactory::createReader($fileType);
                $objReader->setLoadSheetsOnly(true);
                $objPHPExcel = $objReader->load($filename);
                $calendarTools = new CalendarTools();
//                $calendarArr = array();
                $returnArr = array();
                //=============================================//
                $commitFlag = true;
                $Emp = D('Emp');
                $Emp->startTrans();
                foreach ($objPHPExcel->getWorksheetIterator() as $sheet) {
                    foreach ($sheet->getRowIterator() as $row) {
                        if($row->getRowIndex() != 1) {
                            foreach ($row->getCellIterator() as $key => $cell) {
                                if(2 == $key){
                                    $year = $cell->getValue();
                                }elseif(3 == $key){
                                    $month = $cell->getValue();
                                }elseif (4 == $key){
                                    $holflag = $cell->getValue();
                                }
                            }
                            for ($i=0;$i<30;$i++){
                                if($i < 9){
                                    $date = $year.'-'.$month.'-0'.($i+1);
                                }else{
                                    $date = $year.'-'.$month.'-'.($i+1);
                                }
                                if(!(empty($holflag[$i]) || " " == $holflag[$i]) || date_check('-',$date)) {
                                    if ('Y' == $holflag[$i]) {
                                        $flag = '1';
                                    } elseif ('N' == $holflag[$i]) {
                                        $flag = '0';
                                    } else {
                                        $flag = false;
                                    }
                                    if (false !== $flag) {
                                        if (false === $calendarTools->calendarSign($date, $flag)) {
                                            $commitFlag = false;
                                            $returnArr[] = array(
                                                $date, 'ERROR', $calendarTools->getError(),
                                            );
                                        } elseif ("" != $calendarTools->getError()) {
                                            $returnArr[] = array(
                                                $date, 'MESSAGE', $calendarTools->getError(),
                                            );
                                        }
                                    } else {
                                        $commitFlag = false;
                                        $returnArr[] = array(
                                            $date, 'ERROR', L('PARAMETER_ERROR_FILE_ERROR'),
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
                if($commitFlag){
                    $Emp->commit();
                }else{
                    $return['status'] = 0;
                    $return['info'] = L('SYSTEM_MESSAGE_UPLOAD_ERROR');
                    $Emp->rollback();
                }
                unlink($filename);
                if(!empty($returnArr)){
                    //$return['info'] = L('SYSTEM_MESSAGE_HAVE_ERROR');
                    $return['data'] = $returnArr;
                }
            }
            $this->assign('return',$return);
        }
        $this->display('calendarUpload');
    }
    /*================================================================================================*/
    /*  货币维护
    /*      功能清单：
    /*          1-currencyList    ： 货币参数列表
    /*          2-currencyAdd     ： 货币参数添加
    /*          3-currencyUpdate  ： 货币参数修改
    /*          4-currencyDelete  ： 货币参数删除
    /*          5-currencyInquery ： 货币参数查询
    /*          5-currencySort    ： 货币参数排序
    /*================================================================================================*/
    // 浏览节点列表
    public function currencyList(){
        $list = $this->lists ( 'Currency', '', '`sort` ASC' );
        //转换LIST中的状态STATUS栏位为说明
        $statusArray = array(
            'status'=>L('TEMP_SYS_CCY_STATUS_TEXT'),
        );
        status_to_string($list,$statusArray);
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('list',$list);// 赋值数据集
        $this->display('currencyList'); // 输出模板
    }

    //货币信息添加
    public function currencyAdd(){
        if(IS_POST){
            $data = array(
                'id' => I('id'),
                'name_en' => I('name_en'),
                'name_zh' => I('name_zh'),
                'sign' => I('sign'),
                'sort' => I('sort',0,'intval'),
                'remark' => I('remark'),
            );
            $currencyTool = new CurrencyTool();
            if(false === $currencyTool->add($data)){
                $this->error($currencyTool->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            $this->display('currencyAdd');
        }
    }

    //货币信息修改
    public function currencyUpdate(){
        if(IS_POST){
            $data = array(
                'id' => I('id'),
                'name_en' => I('name_en'),
                'name_zh' => I('name_zh'),
                'sign' => I('sign'),
                'sort' => I('sort',0,'intval'),
                'remark' => I('remark'),
                'status' => I('status',0,'intval'),
            );
            $currencyTool = new CurrencyTool();
            if(false === $currencyTool->update($data)){
                $this->error($currencyTool->getError());
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            $id = I('id');
            if("" == $id){
                $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));    //请指定记录
            }
            $Currency = M('currency');
            $result = $Currency->find($id);
            $this->assign('result',$result);
            $this->display('currencyUpdate');
        }
    }

    //货币信息删除
    public function currencyDelete(){
        $id = I('id');
        if("" == $id){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));    //请指定记录
        }
        $currencyTool = new CurrencyTool();
        if(false === $currencyTool->delete($id)){
            $this->error($currencyTool->getError());
        }else{
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }

    //货币信息查询
    public function currencyInquery(){
        $id = I('id');
        if("" == $id){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));    //请指定记录
        }
        $Currency = M('Currency');
        $result = $Currency->find($id);
        $this->assign('result',$result);
        $this->display('currencyInquery');
    }

    //货币排序
    public function currencySort(){
        $ids = I('ids');
        $sort = I('sort',0,'intval');
        $Currency = M('Currency');
        $i = 0;
        while(null != $ids[$i]){
            $where = array(
                'id' => $ids[$i],
            );
            $Currency->where($where)->setField('sort',$sort[$i]);
            $i++;
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }
    /*================================================================================================*/
    /*  参数维护
    /*      功能清单：
    /*          1-parameterSet    ： 运行参数配置
    /*================================================================================================*/
    //配置
    public function parameterSet(){
        if(IS_POST){
            $item = I('config');
            $Config = D('Config');
            $Config->startTrans();
            foreach ($item as $key => $value) {
                if(!is_array($result = $Config->where(array('name'=>$key))->find())){
                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
                }
                if($value != $result['value']){
                    switch ($result['type']) {
                        case '0':
                            if(!is_numeric($value)){
                                $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_NUM'));
                            }
                            break;
                        case '1':
                            if(!ereg( "^[A-Za-z0-9]+$",$value)){
                                $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_ENG'));
                            }
                            break;
                        case '2':
                        case '3':
                            break;
                        case '4':
                            $selectArr = parse_config_attr($result['extra']);
                            if(!array_key_exists($value, $selectArr)){
                                $this->error(L('CONT_SYS_ERROR_ENUM_BEYOND_OPT'));
                            }
                            break;
                        default:
                            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
                            break;
                    }
                    $data = array(
                        'id' => $result['id'],
                        'value' => $value,
                        'update_time' => NOW_TIME,
                    );
                    if(!$Config->save($data)){
                        $Config->rollback();
                    }
                }
            }
            $Config->commit();
            S ( 'DB_CONFIG_DATA', null );
            LogTools::activeLog($data);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $Config = M('Config');
            //grouping为9是运行参数
            $list = $Config->where(array('grouping'=>'9'))->order('sort')->select();
            // dump($list);
            foreach ($list as $key => $value) {
                // if ($list[$key]['name'] == 'EXRATE_PARAMETER') {
                //   $list[$key]['value'] = json_decode($list[$key]['value'],true);
                // }
                if('en-us' == LANG_SET){
                    $list[$key]['title'] = $value['title_en'];
                    $list[$key]['remark'] = $value['remark_en'];
                }else{
                    $list[$key]['title'] = $value['title_zh'];
                    $list[$key]['remark'] = $value['remark_zh'];
                }
            }
            // 记录当前列表页的cookie
            Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
            $this->assign('list',$list);
            $this->display('parameterSet');
        }
    }
    /*================================================================================================*/
    /*  自动任务维护
    /*      功能清单：
    /*          1-autoTaskList    ： 自动任务列表
    /*          2-autoTaskConfig  ： 自动任务配置
    /*================================================================================================*/
    // 浏览节点列表
    public function autoTaskList(){
        $Autotask = M('Autotask');
        $list = $Autotask->select();
        //转换LIST中的swift栏位转为为说明
        $switchArray = array(
            'switch'=>L('COMMON_STATUS_TEXT'),
        );
        status_to_string($list,$switchArray);
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('list',$list);// 赋值数据集
        $this->display('autoTaskList'); // 输出模板
    }

    //自动任务配置
    public function autoTaskConfig(){
        $code = I('code');
        if("" == $code){
            $this->error(L('SYSTEM_ERROR_NOT_SPECIFY_RECORD'));
        }
        $Autotask = M('Autotask');
        if(!is_array($result = $Autotask->find($code))){
            $this->error(L('SYSTEM_ERROR_RECORD_NOT_EXIST'));
        }
        if(IS_POST){
            $data = array(
                'code'          => I('code'),
                'remark'        => I('remark'),
                'switch'        => I('switch',0,'intval'),
                'holiday_rule'  => I('holiday_rule'),
                'cycle'         => I('cycle'),
                'month'         => I('month',0,'intval'),
                'day_of_month'  => I('day_of_month',0,'intval'),
                'day_of_week'   => I('day_of_week',0,'intval'),
                'time'          => I('time'),
            );
            //检查输入项
            if(empty($data['code'])){
                $this->error(L('TEMP_AUTOTASK_CODE').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
            }
            if(empty($data['remark'])){
                $this->error(L('TEMP_AUTOTASK_REMARK').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
            }
            if(empty($data['holiday_rule'])){
                $this->error(L('TEMP_AUTOTASK_HOLIDAY_RULE').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
            }
            if(empty($data['cycle'])){
                $this->error(L('TEMP_AUTOTASK_CYCLE').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
            }
            if(empty($data['time'])){
                $this->error(L('TEMP_AUTOTASK_TIME').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
            }else{
                $timeArr = explode(':',$data['time']);
                if($timeArr[0] < 0 || $timeArr[0] > 23 || $timeArr[0] < 0 || $timeArr[0] > 59){
                    $this->error(L('TEMP_AUTOTASK_TIME')." , ".L('SYSTEM_ERROR_FORMAT'));
                }
            }
            //周期与日期项之间的关系检查
            switch ($data['cycle']) {
                case 'Y':
                    if(empty($data['month']) || empty($data['day_of_month'])){
                        $this->error(L('TEMP_AUTOTASK_MONTH').' & '.L('TEMP_AUTOTASK_DAY_OF_MONTH').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
                    }
                    $data['day_of_week'] = 0;
                    break;
                case 'Q':
                    if(empty($data['day_of_month'])){
                        $this->error(L('TEMP_AUTOTASK_DAY_OF_MONTH').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
                    }
                    $data['month'] = 0;
                    $data['day_of_week'] = 0;
                    break;
                case 'M':
                    if(empty($data['day_of_month'])){
                        $this->error(L('TEMP_AUTOTASK_DAY_OF_MONTH').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
                    }
                    $data['month'] = 0;
                    $data['day_of_week'] = 0;
                    break;
                case 'W':
//                    if(empty($data['day_of_week'])){
//                        $this->error(L('TEMP_AUTOTASK_DAY_OF_WEEK').' , '.L('SYSTEM_ERROR_MUST_INPUT'));
//                    }
                    $data['month'] = 0;
                    $data['day_of_month'] = 0;
                    break;
                case 'D':
                    $data['month'] = 0;
                    $data['day_of_month'] = 0;
                    $data['day_of_week'] = 0;
                    break;
                default:
                    $this->error(L('TEMP_AUTOTASK_CYCLE').' , '.L('SYSTEM_ERROR_BEYOND_OPTION'));
                    break;
            }
            $Report = M('Report');
            $reportMap = array(
              'code'       =>  $data['code'],
            );
            if (is_array($Report->where($reportMap)->select())) {
              $reportData['status'] = $data['switch'];
              if (false === $Report->where($reportMap)->save($reportData)) {
                $this->error($Report->getError());
              }
            }
            $returnCode = $Autotask->save($data);
            if(false === $returnCode){
                $this->error($this->_autoTaskModel->getError());
            }elseif(0 === $returnCode){
                $this->error(L('SYSTEM_MESSAGE_NOT_CHANGE'));
            }
            LogTools::activeLog($data);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $this->assign('result',$result);
            $this->display('autoTaskConfig');
        }
    }
    /*================================================================================================*/
    /*  汇率参数维护
    /*      功能清单：
    /*          1-exRateParameterSet    ： 汇率参数配置
    /*================================================================================================*/
    public function exRateParameterSet(){
      if (IS_POST) {
        $exRateArr = I('exRateArr');
        $Config = M('Config');
        $map = array(
          'name'   => 'EXRATE_PARAMETER',
        );
        if (false === $Config->where($map)->setField('value',json_encode($exRateArr))) {
          $this->error(L('SYSTEM_MESSAGE_ERROR'));
        }else{
          $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }
      }else{
        $exRateTools = new ExRateTools();
        $exRateArr = $exRateTools->getList();
        $Config = M('Config');
        $map = array(
          'name'   => 'EXRATE_PARAMETER',
        );
        $configArr = $Config->where($map)->find();
        $exRateParameter = json_decode($configArr['value'] ,true);



        foreach ($exRateParameter as $exchange_ccy_key => $exchange_ccy_item) {
            foreach ($exchange_ccy_item as $target_ccy_key => $target_ccy_item) {
                if (isset($exRateArr[$exchange_ccy_key][$target_ccy_key])) {
                    $exRateArr[$exchange_ccy_key][$target_ccy_key] = $exRateParameter[$exchange_ccy_key][$target_ccy_key];
                }
            }
        }

        // foreach ($exRateArr as $key => $value) {
        //   foreach ($value as $vk => $vv) {
        //     if (empty($exRateArr[$key][$vk])) {
        //       $exRateArr[$key][$vk]['is_exRate'] = '1';
        //       $exRateArr[$key][$vk]['value'] = '2';
        //     }
        //   }
        // }
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->assign('exRateArr', $exRateArr);
        $this->display('exRateParameterSet');
      }
    }
    /*================================================================================================*/
    /*  信息发送秘钥参数维护
    /*      功能清单：
    /*          1-sendKeyList    ： 信息发送秘钥参数列表
    /*          2-sendKeyAdd     :  信息发送秘钥参数新增
    /*          3-sendKeyDelete  :  信息发送秘钥参数删除
    /*          4-sendKeyUpdate  :  信息发送秘钥参数修改
    /*================================================================================================*/
    public function sendKeyList(){
      $list = $this->lists ( 'Key', '', '`id` ASC' );
      Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
      $this->assign('list',$list);
      $this->display('sendKeyList');
    }

    //秘钥添加
    public function sendKeyAdd(){
        if(IS_POST){
            $data = array(
                'id' => I('id'),
                'date' => date('Y-m-d'),
                'key' => I('key'),
            );
            $Key = M('Key');
            if(false === $Key->add($data)){
                $this->error(L('SYSTEM_MESSAGE_ERROR'));
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            $Key = M('Key');
            $Emp = M('Emp');
            $id = $Key->max('id') + 1;
            $k_key = MD5(randCode(10));
            $k_value = $Emp->getField('id,name',true);
            $this->assign('id',$id);
            $this->assign('k_key',$k_key);
            $this->assign('k_value',$k_value);
            $this->display('sendKeyAdd');
        }
    }
    //秘钥修改
    public function sendKeyUpdate(){
        if(IS_POST){
            $data = array(
                'id' => I('id'),
                'date' => date('Y-m-d'),
                'key' => I('key'),
            );
            $Key = M('Key');
            if(false === $Key->save($data)){
                $this->error(L('SYSTEM_MESSAGE_ERROR'));
            }else{
                $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
            }
        }else{
            $Key = M('Key');
            $Emp = M('Emp');
            $id = I('id');
            $keyArr = $Key->where("id = $id")->find();
            $keyCon = $keyArr['key'];
            $keyValArr = explode(':',$keyArr['key']);
            $k_key = $keyValArr[0];
            $k_value = $Emp->getField('id,name',true);
            $k_value_id = $keyValArr[1];
            // $k_value = ;
            $this->assign('id',$id);
            $this->assign('k_key',$k_key);
            $this->assign('k_value',$k_value);
            $this->assign('k_value_id',$k_value_id);
            $this->assign('keyCon',$keyCon);
            $this->display('sendKeyUpdate');
        }
    }
    //秘钥删除
    public function sendKeyDelete(){
        $id = I('id');
        $Key = M('Key');
        if(false === $Key->where("id = $id")->delete()){
            $this->error(L('SYSTEM_MESSAGE_ERROR'));
        }else{
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }
    }
}
