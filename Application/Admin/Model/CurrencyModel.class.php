<?php
namespace Admin\Model;
use Think\Model;

/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/8/7
 * Time: 13:03
 */

class CurrencyModel extends Model{
	/* 模型自动验证 */
    protected $_validate = array(
        array('name_en', '', 'CONT_SYS_ERROR_CCY_ENNAME_EXIST', self::VALUE_VALIDATE, 'unique'),
        array('name_zh', '', 'CONT_SYS_ERROR_CCY_ZHNAME_EXIST', self::VALUE_VALIDATE, 'unique'),
    );
}