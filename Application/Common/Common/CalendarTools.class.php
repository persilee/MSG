<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/9
 * Time: 16:49
 */

namespace Common\Common;


class CalendarTools
{
    private $_errMsg = "";

    /**
     * 日历标记
     * @param $date YYYY-MM-DD 日期
     * @param $flag char 1-假期,0-工作日
     */
    public function calendarSign($date,$flag){
        $this->_errMsg = "";
        // 输入检查
        if(empty($date)){
            $this->_errMsg = L('CONT_SYS_ERROR_SPECIFY_DATE');
            return false;
        }elseif(!date_check("-",$date)){
            $this->_errMsg = L('SYSTEM_ERROR_DATE_FORMAT');
            return false;
        }else{
            $date = date_to_int('-',$date);
        }
//        if(!array_key_exists($calendar_code, C('CALENDAR_CODE'))){
//            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
//        }
        // 计算当天是周末还是周中
        $is_holiday = false;
        $numberOfWeek = date ( 'w',  $date );
        if(0 == $numberOfWeek || 6 == $numberOfWeek){
            $is_holiday = true;
        }

        $Calendar = M('Calendar');
        $map = array(
//            'calendar_code' => $calendar_code,
            'date' => $date,
        );
        $data = array(
//            'calendar_code' => $calendar_code,
            'date' => $date,
            'flag' => $flag,
        );
        $result = $Calendar->where($map)->find();
        $returnCode = true;
        switch ($flag) {
            case '0':
                if($is_holiday){
                    if(!is_array($result)){
                        $returnCode = $Calendar->add($data);
                    }elseif($flag != $result['flag']){
                        $returnCode = $Calendar->save($data);
                    }
                    $this->_errMsg = L('PARAMETER_MSG_SING_WORKING_DATE');
                }else{
                    if(is_array($result)){
                        $returnCode = $Calendar->where($map)->delete();
                    }
                }
                break;
            case '1':
                if($is_holiday){
                    if(is_array($result)){
                        $returnCode = $Calendar->where($map)->delete();
                    }
                }else{
                    if(!is_array($result)){
                        $returnCode = $Calendar->add($data);
                    }elseif($flag != $result['flag']){
                        $returnCode = $Calendar->save($data);
                    }
                    $this->_errMsg = L('PARAMETER_MSG_SING_HOLIDAY_DATE');
                }
                break;
            case '2':
                if(!is_array($result)){
                    $returnCode = $Calendar->add($data);
                }elseif($flag != $result['flag']){
                    $returnCode = $Calendar->save($data);
                }
                break;
            default:
                $this->_errMsg = L('TEMP_PARAMETER_CALENDAR_DATE_FLAG').' , '.L('SYSTEM_ERROR_MUST_INPUT');
                return false;
                break;
        }
        if(!$returnCode){
            $this->_errMsg = $Calendar->getError();
            return false;
        }
        LogTools::activeLog(array('date'=>$date,'flag'=>$flag));
        return true;
        // 启动事务
        // $Calendar->startTrans();
        // $tempDate = $startDate;
        // while ($tempDate <= $endDate) {
        //     $calendarResult = $Calendar->find($tempDate);
        //     if($calendarResult){
        //         if($flag != $calendarResult){
        //             if(!$Calendar->where(array('date'=>$tempDate))->delete()){
        //                 $Calendar->rollback();
        //                 $this->error($Calendar->getError());
        //             }
        //         }
        //     }
        //     $numberOfWeek = date ( 'w',  $tempDate );
        //     if(((0 == $numberOfWeek || 6 == $numberOfWeek) && ('0' == $flag)) ||
        //        ((0 != $numberOfWeek && 6 != $numberOfWeek) && ('1' == $flag))){
        //         $data = array(
        //             'date' => $tempDate,
        //             'vacation_flag' => $flag,
        //         );
        //         if(!$Calendar->add($data)){
        //             $Calendar->rollback();
        //             $this->error($Calendar->getError());
        //         }
        //     }
        //     $tempDate += 3600*24;
        // }
        // $Calendar->commit();
    }

    public function getError(){
        return $this->_errMsg;
    }
}