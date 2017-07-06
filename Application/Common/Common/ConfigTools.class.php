<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/7/27
 * Time: 17:35
 */

namespace Common\Common;


class ConfigTools
{
    /**
     * 截入配置项
     * @return boolean 载入是否成功
     */
    public static function init(){
        /* 读取数据库中的配置 */
        $config = S('DB_CONFIG_DATA');
        if(!$config){
            $config = self::lists();
            S('DB_CONFIG_DATA',$config,3600);
        }
        C($config);//添加配置，C方法动态配置只能是单次请求有效，所以每次请求都需要配置
        return true;
    }

    /**
     * 获取配置列表
     * @return array 配置数组
     */
    public static function lists(){
        $configModel = M('Config');
        $data   = $configModel->field('type,name,value')->select();

        $config = array();
        if($data && is_array($data)){
            foreach ($data as $value) {
                $config[$value['name']] = self::parse($value['type'], $value['value']);
            }
        }
        return $config;
    }

    /**
     * 根据配置类型解析配置
     * @param  integer $type  配置类型
     * @param  string  $value 配置值
     * @return string  $value 返回的参数值
     */
    public static function parse($type, $value){
        switch ($type) {
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if(strpos($value,':')){
                    $value  = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                }else{
                    $value = $array;
                }
                break;
        }
        return $value;
    }
}