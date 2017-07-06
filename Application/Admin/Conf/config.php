<?php
return array(

    // 定义配置项分组信息
    'CONFIG_GROUP_LIST' => array(
        '1' => 'Base',    //基本
        '2' => 'User',    //用户
        '3' => 'Site',     //站点
        '4' => 'System',  //系统
        '9' => 'Parameter',  //运行参数
    ),

    //定义员工状态
    'EMP_STATUS' => array(
    	'A' => 'Active',    // 在职 
    	'R' => 'Leave',     // 离职
    ),

    //消息状态
    'MESSAGE_STATUS' => array(
        'R' => 'Read',    //已读
        'U' => 'Unread',   //未读
    ),
    
    //配置类型
    'CONFIG_TYPE_LIST' => array(
        '0' => 'Digital',     //数字
        '1' => 'Character',   //字符
        '2' => 'Text',        //文本
        '3' => 'Array',       //数组
        '4' => 'Enumeration', //枚举
    ),
);