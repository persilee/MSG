<?php
/**
 * 正则格式检查类
 */
namespace Common\Common;

class RegexTools{
	private static $validate = array(
		'require'  		=>  '/.+/',
		'int'      		=>  '/^(-|\+)?\d+$/',
		'alphanumeric'	=> 	'/^[A-Za-z0-9_]+$/',
		'posiint'  		=>  '/^[\+]?\d+$/',
		'negint'   		=>  '/^[-]\d+$/',
		'float'    		=>  '/^\d+.\d+$/',
		'amount'   		=>  '/^\d+(\.\d{1,2}?$/',
		'phone'    		=>  '/^\d{3,4}-\d{8}$|\d{4}-\d{7}$/',
		'qq'       		=>  '/^[1-9][0-9]{4,}$/',
		'idcard'   		=>  '/^\d{15}|\d{18}$/',
		'mobile'   		=>  '/^1(|3|4|5|7|8)\d{9}$/',
		'email'    		=>  '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
	);
	private static $returnMsg = "";
	private static $pattern = "";

	// 通用验证方法
	public static function regex($pattern,$subject){
		if(!self::patternCheck($pattern)){
			return false;
		}
		array_key_exists($pattern, self::$validate) ?
			self::$pattern = self::$validate[$pattern] :
			self::$pattern = $pattern;
		if(preg_match(self::$pattern, $subject)){
			return true;
		}else{
			self::$returnMsg = "验证失败！";
			return false ;
		}
	}

	// 字段非空验证
	public static function notEmpty($subject){
		if(preg_match(self::$validate['require'], $subject)){
			return true;
		}else{
			self::$returnMsg = "字段为空！";
			return false;
		}
	}

	/**
	 * 检查金额
	 *
	 * @param string $subject    需要验证的金额字段
	 * @param int    $intDig     整数位长度(必须指定)
	 * @return booleam
	 *
	 */
	public static function intCheck($subject,$intDig){
		if(!self::regex('posiint',$intDig)){
			self::$returnMsg = "输入的整数位必须为正整数！";
			return false;
		}
		self::$pattern = '/^\d{1,'.str_replace('+','',$intDig).'}$/';
		if(preg_match(self::$pattern, $subject)){
			return true;
		}else{
			self::$returnMsg = "金额格式不正确！";
			return false;
		}
	}

	/**
	 * 检查金额
	 *
	 * @param string     $subject    需要验证的金额字段
	 * @param int        $decDig     小数位长度(必须指定)
	 * @param int        $intDig     整数位长度(为空、0表示不限制整数位)
	 * @param boolean    $negative   是否允许负数
	 * @return booleam
	 *
	 */
	public static function amountCheck($subject,$decDig,$intDig="",$negative=false){
		if(!self::regex('posiint',$decDig)){
			self::$returnMsg = "输入的小数位必须为正整数！";
			return false;
		}
		if(!empty($intDig) && !self::regex('posiint',$intDig)){
			self::$returnMsg = "整数位可以不指定，指定的情况下须为正整数！";
			return false;
		}
		if($negative){
			self::$pattern = '/^-?';
		}else{
			self::$pattern = '/^';
		}
		if(!empty($intDig)){
			$intDig = intval($intDig) - 1;
			self::$pattern .= '(([1-9]\d{0,'.str_replace('+','',$intDig).'})|0)(\.\d{1,'.str_replace('+','',$decDig).'})?$/';
		}else{
			self::$pattern .= '(([1-9]\d*)|0)(\.\d{1,'.str_replace('+','',$decDig).'})?$/';
		}
		if(preg_match(self::$pattern, $subject)){
			return true;
		}else{
			self::$returnMsg = "格式不正确！";
			return false;
		}
	}

	// 取得验证错误信息
	public static function getError(){
		return self::$returnMsg;
	}

	// 内部检查类、检查正则验证模式是否输入
	private static function patternCheck($pattern){
		if("" == $pattern || null == $pattern){
			self::$returnMsg = "请指定正则验证模式！";
			return false;
		}else{
			return true;
		}
	}
}