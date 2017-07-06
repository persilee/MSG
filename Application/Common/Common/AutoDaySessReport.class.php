<?php
/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/25
 * Time: 15:14
 */

namespace Common\Common;

class AutoDaySessReport extends AutoTaskTools
{
    public function exec(){
        if ($this->checkTask('AutoDaySessReport')) {
            $this->echoMsg('H');
            $daySessReportTools = new DaySessReportTools();
            if (false === $daySessReportTools->daySessExcelGen('AutoDaySessReport')) {
                $this->echoMsg('C', $daySessReportTools->getError(), false);
            } else {
                $this->echoMsg('C', "DaySessReport generate success ");
            }
            $this->echoMsg('E');
        }
        return true;
    }
}
