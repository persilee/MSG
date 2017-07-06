<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/8/7
 * Time: 10:32
 */

/**
 * 系统非常规MD5加密方法
 *
 * @param string $str
 *        	要加密的字符串
 * @return string
 */
function system_md5($str, $key = '') {
    empty ( $key ) && $key = C ( 'DATA_AUTH_KEY' );
    return '' === $str ? '' : md5 ( sha1 ( $str ) . $key );
}

/**
 * 检测用户是否登录
 *
 * @return integer 0-未登录，大于0-当前登录用户ID
 */
function is_login() {
    $user = session ( 'user_auth' );
    if (empty ( $user )) {
        return 0;
    } elseif( $user['system_sign'] != C('SYSTEM_SIGN')){
        return 0;
    } else {
        return session ( 'user_auth_sign' ) == data_auth_sign ( $user ) ? $user ['uid'] : 0;
    }
}

/**
 * 取得操作用户部门
 *
 * @return integer 0-错误，大于0-当前登录用户部门
 */
function get_current_deptid() {
    $Emp = M('Emp');
    return $Emp->where(array('id'=>is_login()))->getField('dept_id');
}

/**
 * 格式化金额
 *
 * @param int $money
 * @param int $len
 * @param string $ccy
 * @param string $sign
 * @return string
 */
function format_money($money, $ccy='',$len=2,$sign='$'){
    $negative = (float)$money >= 0 ? '' : '-';
    $int_money = intval(abs($money));
    $len = intval(abs($len));
    $decimal = '';//小数
    if ($len > 0) {
        $decimal = '.'.substr(sprintf('%01.'.$len.'f', $money),-$len);
    }
    $format_money = "";
    $tmp_money = strrev($int_money);
    $strlen = strlen($tmp_money);
    for ($i = 3; $i < $strlen; $i += 3) {
        $format_money .= substr($tmp_money,0,3).',';
        $tmp_money = substr($tmp_money,3);
    }
    $format_money .= $tmp_money;
    $format_money = strrev($format_money);
    if('' != $ccy){
        $currencyTool = new \Common\Common\CurrencyTool();
        $ccyArr = $currencyTool->getCcyArr();
        if(is_array($ccyArr) && array_key_exists($ccy, $ccyArr)){
            $sign = $ccyArr[$ccy]['sign'];
        }
    }
    return $sign.$negative.$format_money.$decimal;
}

/**
 * 取得操作用户职位
 *
 * @return array 当前操作员的角色数组
 */
function get_current_roleid() {
    $Usergroup_user = M('Usergroup_user');
    $usergroupArr = $Usergroup_user->where(array('user_id'=>is_login()))->getField('usergroup_id',true);
    $usergroupStr = implode(',', $usergroupArr);
    $Role_usergroup = M('Role_usergroup');
    return $Role_usergroup->where(array('usergroup_id'=>array('IN',$usergroupStr)))->getField('role_id',true);
}

/**
 * 数据签名认证
 *
 * @param array $data
 *        	被认证的数据
 * @return string 签名
 */
function data_auth_sign($data) {
    // 数据类型检测
    if (! is_array ( $data )) {
        $data = ( array ) $data;
    }
    ksort ( $data ); // 排序
    $code = http_build_query ( $data ); // url编码并生成query字符串
    $sign = sha1 ( $code ); // 生成签名
    return $sign;
}

/**
 * 无限级分类
 * @access public
 * @param Array $data     //数据库里获取的结果集
 * @param Int $pid
 * @param Int $count       //第几级分类
 * @return Array $treeList
 */
function tree($data,$pid = 0) {
    $treeList = array();
    foreach ($data as $key => $value){
        if($value['pid']==$pid){
            $value['child'] = tree($data,$value['id']);
            $treeList[]=$value;
        }
    }
    return $treeList ;
}

/**
 * 无限级分类
 * @access public
 * @param Array $data     //数据库里获取的结果集
 * @param Int $pid
 * @param Int $count       //第几级分类
 * @return Array $treeList
 */
function levelTree($data,$pid = 0,$Level = 1) {
    $treeList = array();
    foreach ($data as $value){
        if($value['pid']==$pid){
            $value['treeLevel'] = $Level;
            $treeList[]=$value;
            $treeList = array_merge($treeList,levelTree($data,$value['id'],$Level+1));
        }
    }
    return $treeList ;
}


/**
 * 递归无限级分类【先序遍历算】，获取任意节点下所有子孩子
 * @param array $arrCate 待排序的数组
 * @param int $parent_id 父级节点
 * @param int $level 层级数
 * @return array $arrTree 排序后的数组
 */
function getChildTree($arrCat, $parent_id = 0, $level = 0)
{
    $arrTree = array();
    if( empty($arrCat)) return FALSE;
    $level++;
    foreach($arrCat as $key => $value)
    {
        if($value['pid' ] == $parent_id)
        {
            $value[ 'level'] = $level;
            $arrTree[] = $value;
            unset($arrCat[$key]); //注销当前节点数据，减少已无用的遍历
            $arrTree = array_merge($arrTree,getChildTree($arrCat, $value[ 'id'], $level));
        }
    }

    return $arrTree;
}

/**
 * 递归无限级分类【先序遍历算】，获取任意节点上的所有父节点(包含当前节点)
 * @param array $arrCate 待排序的数组
 * @param int $id 节点ID
 * @return array $arrTree 排序后的数组
 */
function getParentTree($arrCat, $id = 0)
{
    $arrTree = array();
    if( empty($arrCat)) return FALSE;
    foreach($arrCat as $key => $value)
    {
        if($value['id' ] == $id)
        {
            $arrTree[] = $value;
            if(0 == $value['pid']){
                return $arrTree;
            }else{
                unset($arrCat[$key]); //注销当前节点数据，减少已无用的遍历
                $arrTree = array_merge($arrTree,getParentTree($arrCat, $value[ 'pid']));
            }
        }
    }

    return $arrTree;
}

/**
 * 递归无限级分类【先序遍历算】，将节点数组转换为以节点名称为KEY的数组
 * @param array $data 节点数组
 * @param int  $pid 起始节点
 * @return array $arrPlaceTree 返回的位置数组
 */
function sortPlaceTree($data,$pid = 0) {
    $treeList = array();
    foreach ($data as $key => $value){
        if($value['pid']==$pid){
            $value['child'] = sortPlaceTree($data,$value['id']);
            $treeList[strtoupper($value['name'])]=$value;
        }
    }
    return $treeList ;
}

/**
 * 递归无限级分类，将具有子节点的树转换成普通数组
 * @param array $arrTree 待排序的树型数组
 * @return array $arrTree 排序后的数组
 */
function turnTreeToArr($arrTree)
{
    $returnArr = array();
    if( empty($arrTree)) return FALSE;
    foreach($arrTree as $value)
    {
        if(isset($value['child']))
        {
            $returnArr = array_merge($returnArr,turnTreeToArr($value['child']));
            unset($value['child']);
        }
        $returnArr[] = $value;
    }
    return $returnArr;
}

/**
 * 时间戳格式化
 *
 * @param int $time
 * @return string 完整的时间显示
 * @author huajie <banhuajie@163.com>
 */
function time_format($time = NULL, $format = 'Y-m-d H:i') {
    if (empty ( $time ))
        return '';

    $time = $time === NULL ? NOW_TIME : intval ( $time );
    return date ( $format, $time );
}

function day_format($time = NULL,$format="") {
    if(empty($format)){
        return time_format ( $time, 'Y-m-d' );
    }else{
        return time_format ( $time, $format );
    }
}

function hour_format($time = NULL) {
    return time_format ( $time, 'H:i' );
}


/**
 * 日期格式检查
 * @param  string $date 日期字符串yyyy-MM-dd
 * @return string
 */
function date_check($flag,$date){
    $date_elements = explode($flag ,$date);
    return checkdate($date_elements[1],$date_elements[2],$date_elements[0]);
}

/**
 * 日期格式转换为Unix时间戳
 * @param  string $date 日期字符串yyyy-MM-dd
 * @return string
 */
function date_to_int($flag,$date,$h,$i,$s){
    $date_elements = explode($flag ,$date);
    return mktime($h,$i,$s,$date_elements[1],$date_elements[2],$date_elements[0]);
}

/**
 * 将LIST中的用户信息Status状态字转换为说明
 *
 * @param array $map
 *          映射关系二维数组 array(
 *          '字段名1'=>array(映射关系数组),
 *          '字段名2'=>array(映射关系数组),
 *          ......
 *          )
 * @return array array(
 *         array('id'=>1,'title'=>'标题','status'=>'1','status_text'=>'正常')
 *         ....
 *         )
 *
 */
function status_to_string(&$data, $map) {
    if ($data === false || $data === null) {
        return $data;
    }
    $data = ( array ) $data;
    foreach ( $data as $key => $row ) {
        foreach ( $map as $col => $pair ) {
            if (isset ( $row [$col] ) && isset ( $pair [$row [$col]] )) {
                $data [$key] [$col . '_text'] = $pair [$row [$col]];
            }
        }
    }
    return $data;
}

/**
 * 二维数组根据某一字段进行排序
 *
 */
function sortArrByField(&$array, $field, $desc = false){
  $fieldArr = array();
  foreach ($array as $k => $v) {
    $fieldArr[$k] = $v[$field];
  }
  $sort = $desc == false ? SORT_ASC : SORT_DESC;
  array_multisort($fieldArr, $sort, $array);
}

//分析枚举类型配置值 格式 a:名称1,b:名称2
//将枚举字符串转换成数组
function parse_config_attr($string){
    $array = preg_split ( '/[;\r\n]+/', trim ( $string, ",;\r\n" ) );
    if (strpos ( $string, ':' )) {
        $value = array ();
        foreach ( $array as $val ) {
            list ( $k, $v ) = explode ( ':', $val );
            $value [$k] = $v;
        }
    } else {
        $value = $array;
    }
    return $value;
}

/**
 * 日期计算，计算指定单位自然日时间之后的日期
 * @param  string $flag  日期分隔符
 * @param  string $date  日期字符串YYYY-MM-DD
 * @param  string $unit  单位：Y-年，M-月，其他-日(默认)
 * @param  int    $count 数量（年数、月数、天数）
 * @param  int    $type  0-实际天数差(默认)；1-满月满年
 */
function date_computer($flag,$date,$unit="",$count,$type=0){
    $date_elements = explode($flag ,$date);
    if('Y' == $unit){
        $date_elements[0] = $date_elements[0] + $count;
    }elseif ('M' == $unit) {
        $date_elements[1] = $date_elements[1] + $count;
    }else{
        $date_elements[2] = $date_elements[2] + $count;
    }
    $returnDate = day_format(mktime(0,0,0,$date_elements[1],$date_elements[2],$date_elements[0]));
    if(1 == $type && ( "Y" == $unit || "M" == $unit)){
        $returnDate_elements = explode("-" ,$returnDate);
        if($returnDate_elements[2] != $date_elements[2]){
            $returnDate = day_format(mktime(0,0,0,$returnDate_elements[1],0,$returnDate_elements[0]));
        }
    }
    return $returnDate;
}

/**
 * 日期计算，计算两个日期之间的天数差
 * @param  int $type 1：计算两个日期间的自然天数
 *                   2：计算两个日期间的工作日天数
 *                   3：计算两个日期间的假期天数
 * @param  “yyyy-MM-dd” $date_1  日期1
 * @param  “yyyy-MM-dd” $date_2  日期2
 * @param  int $days
 * @return booleam 返回错误
 * @return string  返回计算后的日期
 * @return int     返回计算后的天数差
 */
function get_days($start_date,$end_date,$type = 1){
    if(!is_string($start_date) || !date_check('-',$start_date)){
        return false;
    }
    if(!is_string($end_date) || !date_check('-',$end_date)){
        return false;
    }
    if (strtotime($start_date) > strtotime($end_date)) {
        list($start_date, $end_date) = array($end_date, $start_date);
    }
    $startDayInt = strtotime($start_date);
    $endDayInt = strtotime($end_date);
    $alldays = abs($endDayInt - $startDayInt)/86400 + 1;
    if(1 == $type){
        return $alldays;
    }else{
//        $start_reduce = $end_add = 0;
        $start_N = date('N',$startDayInt);
        $start_reduce = ($start_N == 7) ? 1 : 0;
        $end_N = date('N',$endDayInt);
        in_array($end_N,array(6,7)) && $end_add = ($end_N == 7) ? 2 : 1;
        $weekend_days = floor(($alldays + $start_N - 1 - $end_N) / 7) * 2 - $start_reduce + $end_add;
        // 查询日历表中的工作日及假期标志
        $Calendar = M('Calendar');
        $workDayCount = $Calendar->where(array('date'=>array('BETWEEN',array($startDayInt,$endDayInt)),'vacation_flag'=>0))->count();
        $vacationDayCount = $Calendar->where(array('date'=>array('BETWEEN',array($startDayInt,$endDayInt)),'vacation_flag'=>1))->count();
        if (2 == $type){
            $workday_days = $alldays - $weekend_days + $workDayCount - $vacationDayCount;
            return intval($workday_days);
        }elseif(3 == $type){
            $weekend_days = $weekend_days - $workDayCount + $vacationDayCount;
            return $weekend_days;
        }else{
            return false;
        }
    }
    return false;
}

/**
 * 假期判断
 * @param  “yyyy-MM-dd” $date  日期
 * @return booleam 返回错误
 * @return string  返回判断结果（1-假期，0-工作日）
 */
function is_holiday_check($date,$flag='-'){
    if(!($tempDate = date_to_int($flag,$date))){
        return false;
    }
    $Calendar = M('calendar');
    if(is_array($dateResult = $Calendar->find($tempDate))){
        return $dateResult['flag'] == 0 ? 0 : 1 ;
    }else{
        $numberOfWeek = date ( 'w',  $tempDate );
        if($numberOfWeek == 0 || $numberOfWeek == 6){
            return 1;
        }else{
            return 0;
        }
    }

}

/**
 * 取工作日处理
 * @param $type char : F-取前工作日,A-取后工作日
 * @param  “yyyy-MM-dd” $date  日期
 * @param   $count int :取之前(之后)第几个工作日
 * @return booleam 返回错误
 * @return string  返回工作日
 */
function get_work_date($type,$date,$count,$flag='-'){
    if(!($tempDate = date_to_int($flag,$date))){
        return false;
    }
    while ($count > 0){
        if('F' == $type){
            $tempDate -= 24*3600;
        }else{
            $tempDate += 24*3600;
        }
        $returnDate = day_format($tempDate);
        if(!is_holiday_check($returnDate)){
            $count--;
        }
    }
    return $returnDate;
}

/**
 * 删除指定目录下的所有文件及文件夹，可以保留根目录，也可以删除根目录
 * @param  string  $dirName  目录
 * @param  booleam $flag     是否删除根目录
 * @return booleam false->处理错误
 */
function deleteDir($dirName,$flag = true){
    if(!is_dir($dirName)){
        return false;
    }
    if($handle = opendir($dirName)){
        while(false !== ($item = readdir($handle))){
            if($item != "." && $item != "..") {
                if(is_dir($dirName."/".$item)) {
                    if(false === deleteDir($dirName."/".$item)){
                        return false;
                    }
                }elseif(!unlink($dirName."/".$item)){
                    return false;
                }
            }
        }
    }
    closedir($handle);
    if(true === $flag){
        if(!rmdir($dirName)){
            return false;
        }
    }
    return true;
}

/**
 * 去除小数点后多余0函数
 * @param $s
 * @return mixed|string
 */
function delZero($s)
{
    $s = trim(strval($s));
    if (preg_match('#^-?\d+?\.0+$#', $s)) {
        return preg_replace('#^(-?\d+?)\.0+$#','$1',$s);
    }
    if (preg_match('#^-?\d+?\.[0-9]+?0+$#', $s)) {
        return preg_replace('#^(-?\d+\.[0-9]+?)0+$#','$1',$s);
    }
    return $s;
}

/**
 * 格式化字节大小
 *
 * @param number $size
 *        	字节数
 * @param string $delimiter
 *        	数字和单位分隔符
 * @return string 格式化后的带单位的大小
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function format_bytes($size, $delimiter = '') {
    $units = array (
        'B',
        'KB',
        'MB',
        'GB',
        'TB',
        'PB'
    );
    for($i = 0; $size >= 1024 && $i < 5; $i ++)
        $size /= 1024;
    return round ( $size, 2 ) . $delimiter . $units [$i];
}

/**
 * 密码格式检查
 * @param $pwd
 */
function pwdFormatCheck($pwd){
    if(empty($pwd)){
        return false;
    }
    //检查密码必须由大小写字母及数字组成
    if(!preg_match("/^[a-zA-Z0-9]*$/",$pwd)){
        return false;
    }
    //检查密码中必须包含大小写字母及数字,且最少8位
    if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',$pwd)){
        return false;
    }
    return true;
}

/*
 * 保留小数位处理
 * @param $num 需要做小数被截取的数据
 * @param $digits 小数位位数,可以为0
 * @param $round 是否四舍五入,true-是,false-否
 */
function decimalCut($num,$digits,$round=false){
    $num  = floatval($num);
    $digits = intval($digits);
    if(false == $round){
        $baseNum = pow(10,$digits);
        $num = floor(floatval($num) * $baseNum) / $baseNum;
    }
    return sprintf('%.'.$digits.'f',$num);
}
