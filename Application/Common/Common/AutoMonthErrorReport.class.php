<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/25
 * Time: 15:14
 */

namespace Common\Common;

class AutoMonthErrorReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoMonthErrorReport')) {
            $this->echoMsg('H');
            $date = array(
              date_to_int('-',date('Y-m-01', strtotime('-1 month'))),
              date_to_int('-',date('Y-m-t', strtotime('-1 month'))),
            );
            $dayErrorReportTools = new DayErrorReportTools();
            if (false === $dayErrorReportTools->dayErrorExcelGen($code='AutoMonthErrorReport',$date,$fileName='MonthErrorReport')) {
                $this->echoMsg('C', $dayErrorReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "MonthErrorReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
