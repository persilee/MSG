<?php
return array(
    // 用于保证同一台主机多服务不会免密直接登录，但登录另一服务后前一服务会被退出
    'SYSTEM_SIGN'       => 'msg_sys',

    // 系统数据加密设置
	'DATA_AUTH_KEY' 	=> '$eNB:+h@Z2^.Xb6D|&dJ)TcF}YVRw;xPkQ5,=a_[', // 默认数据加密KEY

    // 数据库配置
    'DB_TYPE'   		=> 'mysql', // 数据库类型
    'DB_HOST'   		=> 'localhost', // 服务器地址
    'DB_NAME'   		=> 'msg', // 数据库名
    'DB_USER'   		=> 'msg_test', // 用户名
    'DB_PWD'    		=> '123',  // 密码
    'DB_PORT'   		=> '8889', // 端口
    'DB_PREFIX' 		=> 'uits_', // 数据库表前缀
	// 'DB_PARAMS' 		=> array (\PDO::ATTR_CASE => \PDO::CASE_NATURAL ),

	// 显示页面Trace信息
	//'SHOW_PAGE_TRACE' =>true,
	//'PAGE_TRACE_SAVE'=>true,

	// 多语言支持
    'LANG_SWITCH_ON'    => true,   // 开启语言包功能
    'LANG_AUTO_DETECT'  => true, // 自动侦测语言 开启多语言功能后有效
    'LANG_LIST'         => 'en-us,zh-cn', // 允许切换的语言列表 用逗号分隔
	'DEFAULT_LANG'      => 'en-us', // 默认语言
    'VAR_LANGUAGE'      => 'l', // 默认语言切换变量

    /*===========================RBAC配置项=============================*/
    'USER_AUTH_ON'          =>  true,                     //是否需要认证
    'USER_AUTH_TYPE'        =>  1,                        // 认证类型
    'USER_AUTH_KEY'         => 'uid',                     //认证识别号
    // REQUIRE_AUTH_MODULE  需要认证模块
    'NOT_AUTH_MODULE'       => 'Account,Admin,Common', //无需认证模块
    // 'USER_AUTH_GATEWAY' 认证网关
    // 'RBAC_DB_DSN'  数据库连接DSN
    'RBAC_ROLE_TABLE'       => 'uits_role',              //角色表名称
    'RBAC_ACCESS_TABLE'     => 'uits_access',            //权限表名称
    'RBAC_NODE_TABLE'       => 'uits_node',              //节点表名称
    //以下两个表是对原框架RBAC修改后的用户组、用户组与用户、用户组与角色的表
    'RBAC_USERGROUP_TABLE'  => 'uits_role_usergroup',    //用户分组表
    'RBAC_USER_TABLE'       => 'uits_usergroup_user',    //用户与用户组表名称
    /*=================================================================*/
);
