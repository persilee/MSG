<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/29
 * Time: 18:14
 */

namespace Common\Common;

class AutoYearErrorReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoYearErrorReport')) {
            $this->echoMsg('H');
            $monthEndDays = cal_days_in_month(CAL_GREGORIAN, 12, date("Y", strtotime("-1 year")));
            $date = array(
              date_to_int('-',date("Y-01-01", strtotime("-1 year"))),
              date_to_int('-',date("Y-12-".$monthEndDays, strtotime("-1 year"))),
            );
            $dayErrorReportTools = new DayErrorReportTools();
            if (false === $dayErrorReportTools->dayErrorExcelGen($code='AutoYearErrorReport',$date,$fileName='YearErrorReport',$quarter=date("Y", strtotime("-1 year")))) {
                $this->echoMsg('C', $dayErrorReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "YearErrorReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
