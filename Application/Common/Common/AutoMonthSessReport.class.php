<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/25
 * Time: 15:14
 */

namespace Common\Common;

class AutoMonthSessReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoMonthSessReport')) {
            $this->echoMsg('H');
            $date = array(
              date_to_int('-',date('Y-m-01', strtotime('-1 month'))),
              date_to_int('-',date('Y-m-t', strtotime('-1 month'))),
            );
            $daySessReportTools = new DaySessReportTools();
            if (false === $daySessReportTools->daySessExcelGen($code='AutoMonthSessReport',$date,$fileName='MonthSessReport')) {
                $this->echoMsg('C', $daySessReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "MonthSessReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
