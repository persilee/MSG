<?php
/**
 * Created by PhpStorm.
 * User: per
 * Date: 17/7/14
 * Time: 15:14
 */

namespace Common\Common;

class AutoExRateMail extends AutoTaskTools
{
    public function exec(){
        $runFlag = true;
        while ($runFlag) {
            if ($this->checkTask('AutoExRateMail')) {
                $this->echoMsg('H');
                $exchangeMailTools = new ExchangeMailTools();
                if (false === $exchangeMailTools->sendExchangeMail('B')) {
                    $this->echoMsg('C', $exchangeMailTools->getError(), false);
                } else {
                    $this->echoMsg('C', "Exchange mail send success ");
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
