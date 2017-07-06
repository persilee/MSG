<?php
/**
 * Created by PhpStorm.
 * 自动任务配置类
 * User: Hipr
 * Date: 16/8/6
 * Time: 15:37
 */

namespace Common\Common;

class AutoTaskTools
{
    protected $_autoTaskModel = null;
    protected $_errMsg = "";
    protected $_echoFlag = true;
    protected $_taskResult = null;
    protected $_execErr = "Success";
    protected $_logFileName = 'autoTaskLog.txt';
    /**
     * 文件指针
     * @var resource
     */
    private $fp;

    public function __construct()
    {
        $this->_autoTaskModel = M('Autotask');
    }

    /**
     *  修改配置
     */
//    public function modify($data){
//    }

    /**
     *  检查当前任务是否可以执行,如果可以执行,则执行该任务
     */
    public function exec(){
    }

    /**
     * 检查当前任务是否可以运行(根据任务项中的配置进行检查)
     */
    public function checkTask($code){
        if(is_array($this->_taskResult = $this->_autoTaskModel->find($code))){
            //for test
            //return true;
            //这里需要添加是否在运行时点的检查,如果在运行时点,则返回true,如果不在运行时点,则返回false
            if(!$this->_taskResult['switch']){
                $flag = false;
            }else{
                $today = date('Y-m-d');
                //取得当前记录的应执行日期
                switch ($this->_taskResult['cycle']){
                    case 'Y':
                        $execDay = date('Y').'-'.$this->_taskResult['month'].'-'.$this->_taskResult['day_of_month'];
                        break;
                    //新增
                    case 'Q':
                        $season = ceil((date('n'))/3);
                        $execDay = date('Y').'-'.date('m', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))).'-'.$this->_taskResult['day_of_month'];
                        break;
                    case 'M':
                        $execDay = date('Y-m').'-'.$this->_taskResult['day_of_month'];
                        break;
                    case 'W':
                        //取当前是周几
                        $numberOfWeek = date ('w');
                        switch ($this->_taskResult['holiday_rule']){
                            case 'A':
                                //置后一个工作日处理
                                $dayCount = $numberOfWeek - $this->_taskResult['day_of_week'];
                                $dayCount = $dayCount < 0 ? $dayCount + 7 : $dayCount;
                                $execDay = day_format(date_to_int('-',$today) - ( 24 * 3600 * $dayCount));
                                break;
                            default:
                                $dayCount = $this->_taskResult['day_of_week'] - $numberOfWeek;
                                $dayCount = $dayCount < 0 ? $dayCount + 7 : $dayCount;
                                $execDay = day_format(date_to_int('-',$today) + ( 24 * 3600 * $dayCount));
                                break;
                        }
                        break;
                    default:
                        $execDay = $today;
                        break;
                }
                //根据假期处理规则取相应的处理日期
                if(is_holiday_check($execDay,$flag='-')){
                    switch ($this->_taskResult['holiday_rule']){
                        case 'F':
                            //置前一个工作日处理
                            $execDay = get_work_date('F',$execDay,1);
                            break;
                        case 'A':
                            //置后一个工作日处理
                            $execDay = get_work_date('A',$execDay,1);
                            break;
                        default:
                            break;
                    }
                }
                if((1 != $this->_taskResult['loop_exec']) && ($execDay == day_format($this->_taskResult['last_exec_time'])) && (1 == $this->_taskResult['status'])){
                    //当天已经执行完成
                    $flag = false;
                }else {
                    //取得执行时间的各个元素
                    $execDayArr = explode('-',$execDay);
                    $exec_month = intval($execDayArr[1]);
                    $exec_day_of_month = intval($execDayArr[2]);
                    $exec_monthend = intval(date('d', mktime(0, 0, 0, $execDayArr[1] + 1, 0, date('Y'))));
                    $timeArr = explode(':', $this->_taskResult['time']);
                    $exec_hour = intval($timeArr[0]);
                    $exec_minute = intval($timeArr[1]);
                    //取得当前的时间的各个元素信息
                    $now_month = intval(date('m'));
                    $now_day_of_month = intval(date('d'));
                    $now_hour = intval(date('H'));
                    $now_minute = intval(date('i'));
                    //判断当天是否是执行日期
                    if (($exec_month == $now_month) &&
                        (($exec_day_of_month == $now_day_of_month) || ($exec_day_of_month > $now_day_of_month && $now_day_of_month == $exec_monthend)) &&
                        (($now_hour > $exec_hour) || ($now_hour == $exec_hour && $now_minute >= $exec_minute)))
                    {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
            }
        }else{
            $flag = false;
        }
        return $flag;
    }

    /**
     * 启动当前任务,写任务的起始运行时间
     */
    private function taskStart(){
        $data = array(
            'status'         => 0,
            'last_exec_time' => time(),
            'last_comp_time' => 0,
        );
        return $this->_autoTaskModel->where(array('code'=>$this->_taskResult['code']))->setField($data);
    }

    /**
     * 结束当前任务
     */
    private function taskComplete(){
        $data = array(
            'last_comp_time' => time(),
        );
        if($this->_execErr == "Success"){
            $data['status'] = 1;
        }else{
            $data['status'] = 0;
        }
        return $this->_autoTaskModel->where(array('code'=>$this->_taskResult['code']))->setField($data);
    }

    /**
     * 输出信息
     * $flag char H-写头信息,C-信息体,E-信息尾
     * $msg  string
     *       $flag为C时,传入信息内容
     *       $flag为E时,传入状态信息
     * $status boolean 写信息的时候可以同步指定是否出现错误,用于写尾信息的状态
     */
    protected function echoMsg($flag,$msg,$status=true){
        if($this->_echoFlag) {
            $this->open();
            if(false === $status){
                $this->_execErr = "Error";
            }
            switch ($flag) {
                case 'H':
                    $this->taskStart();
                    $this->write( PHP_EOL );
                    $this->write( "*******************************  Task starting  ********************************".PHP_EOL);
                    $this->write( "** Start time       : " . time_format(time()).PHP_EOL);
                    $this->write( "** Task remark      : " . $this->_taskResult['remark'].PHP_EOL);
                    $this->write( "********************************************************************************".PHP_EOL);
                    break;
                case 'E':
                    $this->write( "********************************************************************************".PHP_EOL);
                    $this->write( "** Task status      : " . $this->_execErr .PHP_EOL);
                    $this->write( "** Complete time    : " . time_format(time()).PHP_EOL);
                    $this->write( "*******************************  Task Complete  ********************************".PHP_EOL);
                    $this->write( PHP_EOL);
                    $this->taskComplete();
                    break;
                default:
                    $this->write( '    ==    ' . $msg.PHP_EOL);
                    break;
            }
        }
    }

    /**
     *  指定是否输出运行信息
     */
    public function setEchoFlag($flag){
        if(!$flag){
            $this->_echoFlag = false;
        }
        return true;
    }

    /**
     * 打开log文件，用于写入数据
     */
    protected function open(){
        if(!$this->fp){
            $this->_filename   = "./Data/autoTaskLog/".date('Y-m-d')."_autoTaskLog.txt";
            $this->fp = @fopen($this->_filename, 'a');
        }
    }

    /**
     * 写入LOG数据
     * @param  string $sql 要写入的SQL语句
     * @return boolean     true - 写入成功，false - 写入失败！
     */
    protected function write($data){
        return @fwrite($this->fp, $data);
    }

    /**
     * 取得错误信息
     */
    public function getError(){
        return $this->_errMsg;
    }

    /**
     * 析构方法，用于关闭文件资源
     */
    public function __destruct(){
        @fclose($this->fp);
    }
}
