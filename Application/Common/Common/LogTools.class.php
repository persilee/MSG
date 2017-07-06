<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/1
 * Time: 14:59
 */

namespace Common\Common;


class LogTools
{
    /**
     *  用户活动LOG登记
     * @param string $content 用户添加Log内容
     */
    static function activeLog($content=""){
        //$Log = M('Log');
        //$Log->add($data);
        //组织log数据
        $dataStr = PHP_EOL;
        $dataStr .= "==========================Start===============================".PHP_EOL;
        $dataStr .= "[ User ID     : ".is_login()." ]".PHP_EOL;
        $dataStr .= "[ IP          : ".get_client_ip(0,true)." ]".PHP_EOL;
        $dataStr .= "[ Time        : ".date("Y-m-d H:i:s")." ]".PHP_EOL;
        $dataStr .= "[ Request url : ".$_SERVER['REQUEST_URI']." ]".PHP_EOL;
        $dataStr .= "  ==DATA==".PHP_EOL;
        if(is_array($content)){
            foreach($content as $key => $value){
                if(is_array($value)){
                    $value = json_encode($value);
                }
                $dataStr .= "   [ ".$key." : ".$value." ]".PHP_EOL;
            }
        }else{
            $dataStr .= "   [ content : ".$content." ]".PHP_EOL;
        }
        $dataStr .= "===========================END================================".PHP_EOL;
        //定位log文件
        $log_file = './Data/activeLog/'.date('Y').'/'.date('Y-m-d').'.log';
        // 自动创建日志目录
        $log_dir = dirname($log_file);
        if (!is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        if(!($fp = fopen($log_file,'a'))){
            return false;
        }
        fwrite($fp,$dataStr);
        fclose($fp);
        return true;
    }
}