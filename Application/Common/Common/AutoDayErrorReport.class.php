<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/25
 * Time: 15:14
 */

namespace Common\Common;

class AutoDayErrorReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoDayErrorReport')) {
            $this->echoMsg('H');
            $dayErrorReportTools = new DayErrorReportTools();
            if (false === $dayErrorReportTools->dayErrorExcelGen('AutoDayErrorReport')) {
                $this->echoMsg('C', $daySessReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "DayErrorReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
