<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/29
 * Time: 17:14
 */

namespace Common\Common;

class AutoYearSessReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoYearSessReport')) {
            $this->echoMsg('H');
            $monthEndDays = cal_days_in_month(CAL_GREGORIAN, 12, date("Y", strtotime("-1 year")));
            $date = array(
              date_to_int('-',date("Y-01-01", strtotime("-1 year"))),
              date_to_int('-',date("Y-12-".$monthEndDays, strtotime("-1 year"))),
            );
            $daySessReportTools = new DaySessReportTools();
            if (false === $daySessReportTools->daySessExcelGen($code='AutoYearSessReport',$date,$fileName='YearSessReport',$quarter=date("Y", strtotime("-1 year")))) {
                $this->echoMsg('C', $daySessReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "YearSessReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
