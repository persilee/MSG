<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/29
 * Time: 15:58
 */

namespace Common\Common;

class AutoQuarterErrorReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoQuarterErrorReport')) {
            $this->echoMsg('H');
            $season = ceil((date('n'))/3)-1;
            $date = array(
              date_to_int('-',date('Y-m-d', mktime(0, 0, 0,$season*3-3+1,1,date('Y')))),
              date_to_int('-',date('Y-m-d', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y')))),
            );
            $dayErrorReportTools = new DayErrorReportTools();
            if (false === $dayErrorReportTools->dayErrorExcelGen($code='AutoQuarterErrorReport',$date,$fileName='QuarterErrorReport',$quarter=$season)) {
                $this->echoMsg('C', $dayErrorReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "QuarterErrorReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
