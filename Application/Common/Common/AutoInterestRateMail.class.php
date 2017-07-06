<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/8
 * Time: 15:14
 */

namespace Common\Common;

class AutoInterestRateMail extends AutoTaskTools
{
    public function exec(){
        $runFlag = true;
        while ($runFlag) {
            if ($this->checkTask('AutoInterestRateMail')) {
                $this->echoMsg('H');
                $interestMailTools = new InterestMailTools();
                if (false === $interestMailTools->sendInterestMail('B')) {
                    $this->echoMsg('C', $interestMailTools->getError(), false);
                } else {
                    $this->echoMsg('C', "Interest mail send success ");
                }
                $this->echoMsg('E');
            }
            if(date('H:i') > '23:20'){
                $runFlag = false;
            }else{
                sleep(1800);
            }
        }
        return true;
    }
}
