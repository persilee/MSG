-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: 2017-05-16 17:47:25
-- 服务器版本： 5.6.35
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `msg`
--
CREATE DATABASE IF NOT EXISTS `msg` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `msg`;

-- --------------------------------------------------------

--
-- 表的结构 `posts`
--

CREATE TABLE `posts` (
  `id` int(16) NOT NULL,
  `title` varchar(50) NOT NULL,
  `content` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`) VALUES
(1, 'dog', '狗，（拉丁文:Canis lupus familiaris,英文名称dog）中文亦称“犬”，狗属于食肉目，分布于世界各地。狗与马、牛、羊、猪、鸡并称“六畜”。有科学家认为狗是由早期人类从灰狼...'),
(2, 'cat', '猫，属于猫科动物，分家猫、野猫，是全世界家庭中较为广泛的宠物。'),
(3, 'cat', '猫，属于猫科动物，分家猫、野猫，是全世界家庭中较为广泛的宠物。');

-- --------------------------------------------------------

--
-- 表的结构 `uits_access`
--

CREATE TABLE `uits_access` (
  `role_id` smallint(6) UNSIGNED NOT NULL,
  `node_id` smallint(6) UNSIGNED NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uits_access`
--

INSERT INTO `uits_access` (`role_id`, `node_id`, `level`, `module`) VALUES
(4, 1, 1, NULL),
(4, 86, 2, NULL),
(4, 87, 3, NULL),
(4, 98, 3, NULL),
(4, 88, 3, NULL),
(4, 99, 2, NULL),
(4, 100, 3, NULL),
(4, 260, 2, NULL),
(4, 314, 3, NULL),
(4, 313, 3, NULL),
(4, 312, 3, NULL),
(4, 310, 3, NULL),
(4, 309, 3, NULL),
(4, 336, 3, NULL),
(4, 311, 3, NULL),
(4, 287, 2, NULL),
(4, 294, 3, NULL),
(4, 288, 3, NULL),
(4, 290, 3, NULL),
(5, 1, 1, NULL),
(5, 86, 2, NULL),
(5, 87, 3, NULL),
(5, 98, 3, NULL),
(5, 88, 3, NULL),
(5, 99, 2, NULL),
(5, 100, 3, NULL),
(5, 302, 2, NULL),
(5, 319, 3, NULL),
(5, 318, 3, NULL),
(5, 317, 3, NULL),
(5, 316, 3, NULL),
(5, 335, 3, NULL),
(5, 334, 3, NULL),
(5, 333, 3, NULL),
(5, 303, 3, NULL),
(5, 260, 2, NULL),
(5, 328, 3, NULL),
(5, 329, 3, NULL),
(5, 265, 3, NULL),
(5, 264, 3, NULL),
(5, 263, 3, NULL),
(5, 262, 3, NULL),
(5, 315, 3, NULL),
(5, 310, 3, NULL),
(5, 261, 3, NULL),
(5, 309, 3, NULL),
(5, 336, 3, NULL),
(5, 337, 3, NULL),
(3, 1, 1, NULL),
(3, 86, 2, NULL),
(3, 88, 3, NULL),
(3, 87, 3, NULL),
(3, 98, 3, NULL),
(3, 99, 2, NULL),
(3, 100, 3, NULL),
(3, 302, 2, NULL),
(3, 333, 3, NULL),
(3, 260, 2, NULL),
(3, 310, 3, NULL),
(3, 265, 3, NULL),
(3, 264, 3, NULL),
(3, 263, 3, NULL),
(3, 262, 3, NULL),
(3, 329, 3, NULL),
(3, 328, 3, NULL),
(3, 315, 3, NULL),
(3, 261, 3, NULL),
(3, 309, 3, NULL),
(3, 337, 3, NULL),
(3, 336, 3, NULL),
(3, 287, 2, NULL),
(3, 294, 3, NULL),
(3, 288, 3, NULL),
(3, 290, 3, NULL),
(1, 1, 1, NULL),
(1, 86, 2, NULL),
(1, 88, 3, NULL),
(1, 87, 3, NULL),
(1, 98, 3, NULL),
(1, 99, 2, NULL),
(1, 100, 3, NULL),
(1, 302, 2, NULL),
(1, 319, 3, NULL),
(1, 318, 3, NULL),
(1, 317, 3, NULL),
(1, 316, 3, NULL),
(1, 335, 3, NULL),
(1, 303, 3, NULL),
(1, 333, 3, NULL),
(1, 334, 3, NULL),
(1, 260, 2, NULL),
(1, 310, 3, NULL),
(1, 265, 3, NULL),
(1, 264, 3, NULL),
(1, 263, 3, NULL),
(1, 262, 3, NULL),
(1, 312, 3, NULL),
(1, 313, 3, NULL),
(1, 329, 3, NULL),
(1, 328, 3, NULL),
(1, 315, 3, NULL),
(1, 314, 3, NULL),
(1, 261, 3, NULL),
(1, 341, 3, NULL),
(1, 327, 3, NULL),
(1, 309, 3, NULL),
(1, 349, 3, NULL),
(1, 350, 3, NULL),
(1, 342, 3, NULL),
(1, 336, 3, NULL),
(1, 337, 3, NULL),
(1, 311, 3, NULL),
(1, 351, 3, NULL),
(1, 11, 2, NULL),
(1, 348, 3, NULL),
(1, 308, 3, NULL),
(1, 307, 3, NULL),
(1, 306, 3, NULL),
(1, 305, 3, NULL),
(1, 204, 3, NULL),
(1, 347, 3, NULL),
(1, 345, 3, NULL),
(1, 344, 3, NULL),
(1, 340, 3, NULL),
(1, 339, 3, NULL),
(1, 17, 3, NULL),
(1, 14, 3, NULL),
(1, 51, 3, NULL),
(1, 15, 3, NULL),
(1, 45, 3, NULL),
(1, 44, 3, NULL),
(1, 42, 3, NULL),
(1, 16, 3, NULL),
(1, 18, 3, NULL),
(1, 13, 3, NULL),
(1, 94, 3, NULL),
(1, 43, 3, NULL),
(1, 95, 3, NULL),
(1, 96, 3, NULL),
(1, 97, 3, NULL),
(1, 12, 3, NULL),
(1, 338, 3, NULL),
(1, 304, 3, NULL),
(1, 346, 3, NULL),
(1, 41, 3, NULL),
(1, 343, 3, NULL),
(1, 93, 3, NULL),
(1, 287, 2, NULL),
(1, 294, 3, NULL),
(1, 293, 3, NULL),
(1, 292, 3, NULL),
(1, 291, 3, NULL),
(1, 289, 3, NULL),
(1, 326, 3, NULL),
(1, 288, 3, NULL),
(1, 290, 3, NULL),
(1, 295, 3, NULL),
(1, 330, 3, NULL),
(1, 331, 3, NULL),
(1, 332, 3, NULL),
(1, 320, 2, NULL),
(1, 325, 3, NULL),
(1, 324, 3, NULL),
(1, 323, 3, NULL),
(1, 321, 3, NULL),
(1, 322, 3, NULL),
(1, 116, 2, NULL),
(1, 122, 3, NULL),
(1, 120, 3, NULL),
(1, 119, 3, NULL),
(1, 50, 3, NULL),
(1, 49, 3, NULL),
(1, 48, 3, NULL),
(1, 47, 3, NULL),
(1, 117, 3, NULL),
(1, 101, 3, NULL),
(1, 83, 3, NULL),
(1, 121, 3, NULL),
(1, 118, 3, NULL),
(1, 46, 3, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `uits_appove`
--

CREATE TABLE `uits_appove` (
  `date` int(10) NOT NULL COMMENT '记录生成日期',
  `seq` int(10) NOT NULL,
  `maker` int(10) NOT NULL COMMENT '录入人ID',
  `type` varchar(10) NOT NULL COMMENT '业务类型',
  `func` varchar(1) NOT NULL COMMENT '操作类型',
  `reference` varchar(120) DEFAULT NULL COMMENT '业务编号',
  `time` int(10) NOT NULL COMMENT '录入时间',
  `content` text COMMENT '业务数据',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='复核数据表';

--
-- 转存表中的数据 `uits_appove`
--

INSERT INTO `uits_appove` (`date`, `seq`, `maker`, `type`, `func`, `reference`, `time`, `content`, `remark`) VALUES
(1492963200, 1, 1, 'EMP', 'M', '3', 1493031433, '{\"id\":3,\"email\":\"david_YLchan\",\"name\":\"David Chan\",\"sex\":\"\",\"mobile\":\"\",\"dept_id\":2,\"is_director\":0,\"remark\":\"12332112312\",\"groupid\":[1]}', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `uits_autotask`
--

CREATE TABLE `uits_autotask` (
  `code` varchar(30) NOT NULL COMMENT '自动邮件代码（与后台程序相对应）',
  `remark` varchar(255) NOT NULL COMMENT '说明',
  `switch` tinyint(1) NOT NULL COMMENT '0-关闭，1-开启',
  `holiday_rule` varchar(1) NOT NULL COMMENT 'N=>正常处理,F=>置前一个工作日,A=>置后一个工作日',
  `cycle` varchar(1) NOT NULL,
  `month` tinyint(2) DEFAULT NULL,
  `day_of_month` tinyint(2) DEFAULT NULL,
  `day_of_week` tinyint(1) DEFAULT NULL,
  `time` varchar(5) NOT NULL COMMENT '执行时间',
  `loop_exec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '循环执行：0-否，1-是',
  `status` tinyint(1) DEFAULT NULL COMMENT '1-正常，0-错误',
  `last_exec_time` int(10) DEFAULT NULL COMMENT '最后执行时间',
  `last_comp_time` int(10) DEFAULT NULL COMMENT '最后完成时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='定时任务配置';

--
-- 转存表中的数据 `uits_autotask`
--

INSERT INTO `uits_autotask` (`code`, `remark`, `switch`, `holiday_rule`, `cycle`, `month`, `day_of_month`, `day_of_week`, `time`, `loop_exec`, `status`, `last_exec_time`, `last_comp_time`) VALUES
('AutoDataBackup', 'Database backup', 1, 'N', 'Y', 1, 12, 0, '00:00', 0, 0, 1470476540, 0),
('AutoInterestRateMail', 'Send client interest rate mail', 1, 'N', 'D', 0, 0, 0, '09:00', 1, 1, 1481075945, 1481075946),
('AutoInterestRateUpload', '自动利率文件上传', 1, 'N', 'D', 0, 0, 0, '08:00', 1, 1, 1474617166, 1474617168);

-- --------------------------------------------------------

--
-- 表的结构 `uits_calendar`
--

CREATE TABLE `uits_calendar` (
  `date` int(10) NOT NULL COMMENT '日期',
  `flag` tinyint(1) NOT NULL COMMENT '假期及工作日标志：0-工作日，1-假期,2-半天工作日'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日历表，存放指定日期是工作日还是假期';

--
-- 转存表中的数据 `uits_calendar`
--

INSERT INTO `uits_calendar` (`date`, `flag`) VALUES
(1476374400, 1),
(1476460800, 0);

-- --------------------------------------------------------

--
-- 表的结构 `uits_client`
--

CREATE TABLE `uits_client` (
  `ci_no` int(10) NOT NULL COMMENT '客户编号',
  `type` varchar(1) DEFAULT NULL COMMENT '客户类型：P-个人，C-公司',
  `id_type` varchar(2) DEFAULT NULL COMMENT '证件类型',
  `id_code` varchar(32) NOT NULL COMMENT '证件编码',
  `name` varchar(120) NOT NULL COMMENT '客户名称',
  `ot_name` varchar(120) DEFAULT NULL COMMENT '客户其他名称',
  `tpl_priority_lang` varchar(20) NOT NULL COMMENT '模板语言优先级',
  `auto_email_flag` tinyint(1) NOT NULL COMMENT '是否自动发邮件：0-否，1-是',
  `mail_plan_time` varchar(5) DEFAULT NULL COMMENT '自动邮件发送时间',
  `email` text COMMENT 'Email地址,多个之间以;分隔',
  `bcc_email` text COMMENT '暗送邮件地址，多人以;分隔',
  `inst_rate_mailtpl` int(10) DEFAULT NULL COMMENT '利率模板ID',
  `inst_send_time` int(10) DEFAULT '0' COMMENT '利率邮件发送时间',
  `auto_phone_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自动发SMS',
  `phone` varchar(11) DEFAULT NULL,
  `cust_group` varchar(32) DEFAULT NULL COMMENT '客户分组',
  `credit_rate` tinyint(1) DEFAULT NULL COMMENT '信用等级',
  `sex` varchar(1) DEFAULT NULL COMMENT '性别',
  `education` varchar(32) DEFAULT NULL COMMENT '学历',
  `area` varchar(32) DEFAULT NULL COMMENT '区域',
  `create_emp` int(10) NOT NULL COMMENT '添加人',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `rate_float` text COMMENT '利率浮动定义',
  `negative_rate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许负利率：0-不允许，1-允许'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='客户信息表';

--
-- 转存表中的数据 `uits_client`
--

INSERT INTO `uits_client` (`ci_no`, `type`, `id_type`, `id_code`, `name`, `ot_name`, `tpl_priority_lang`, `auto_email_flag`, `mail_plan_time`, `email`, `bcc_email`, `inst_rate_mailtpl`, `inst_send_time`, `auto_phone_flag`, `phone`, `cust_group`, `credit_rate`, `sex`, `education`, `area`, `create_emp`, `remark`, `rate_float`, `negative_rate`) VALUES
(9, '', '', 'test', 'test', '親愛的客戶', 'zh_t-en-zh_s', 1, '09:00', 'hiprrrr@gmail.com', '', 6, 1481075946, 0, '', '', 0, '', '', '', 1, '', '{\"Saving\":{\"HKD\":{\"is_rate\":1,\"value\":\"-1\"},\"USD\":{\"value\":0,\"is_rate\":0}},\"O\\/N\":{\"USD\":{\"is_rate\":1,\"value\":\"0.3\"}}}', 0);

-- --------------------------------------------------------

--
-- 表的结构 `uits_config`
--

CREATE TABLE `uits_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置ID',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置类型',
  `value` text NOT NULL COMMENT '配置值',
  `extra` varchar(255) DEFAULT NULL COMMENT '可配置值',
  `title_en` varchar(50) NOT NULL DEFAULT '' COMMENT '配置英文标题',
  `title_zh` varchar(50) NOT NULL COMMENT '配置中文标题',
  `grouping` tinyint(3) NOT NULL COMMENT '配置项分组',
  `remark_en` varchar(100) DEFAULT NULL COMMENT '配置英文说明',
  `remark_zh` varchar(100) DEFAULT NULL COMMENT '配置中文说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(3) NOT NULL COMMENT '字段排序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uits_config`
--

INSERT INTO `uits_config` (`id`, `name`, `type`, `value`, `extra`, `title_en`, `title_zh`, `grouping`, `remark_en`, `remark_zh`, `create_time`, `update_time`, `sort`) VALUES
(1, 'VERIFY_SWITCH', 4, '1', '0:关闭\r\n1:开启', 'Verify code', '登录验证码', 4, 'Need verify code when user login', '登录时是否需要验证码', 0, 1480646653, 1),
(2, 'LIST_ROWS', 0, '15', '', 'Record number in single page', '单页记录条数', 2, 'List each page shows that the maximum number of records', '列表页每一页显示的最大记录数', 0, 1480646670, 2),
(3, 'MAIL_SUFFIX', 2, '@szuitss.com.cn', '', 'Email suffix', '邮箱后缀', 1, 'Email suffix', '公司的邮箱后缀', 0, 1480646636, 5),
(11, 'COMPANY_NAME', 2, '浦发银行', '', 'Name of the company', '公司名称', 1, 'The name of the company', '使用本系统的公司的名称', 1450093286, 1483076343, 10),
(13, 'EXCEL_SAVE_TYPE', 4, 'Excel2007', 'Excel2007:xlsx格式\r\nExcel5:xls格式', 'EXCEL file suffix', 'EXCEL文件保存格式', 2, 'EXCEL file save suffix', '用于下载EXCEL文件时指定其保存类型', 1450263581, 1473407476, 10),
(14, 'SYSTEM_MAIL', 2, 'TEST|uitslhb@szuits.com|uits123|465|smtp.exmail.qq.com|ssl', '', 'System email parameter', '系统邮件发送的邮箱配置', 4, 'FORMAT（sender|sender_email_address|password|port|send_server|ssl/tls），Every field separated by “|”.', '填写格式（sender|sender_email_address|password|port|send_server|ssl/tls），每个字段以“|”分隔', 1450348491, 1476427281, 10),
(15, 'COMPANY_HOMEPAGE', 2, 'http://www.spdb.com.cn/', '', 'The company web site', '公司主页网址', 1, 'The company web site', '记录公司主页网址地址', 1451550150, 1483076343, 10),
(16, 'COMPANY_ENG_SHORT_NAME', 1, 'SPDB', '', 'The company English abbreviations', '公司英文简称', 1, 'The company English abbreviations', '公司英文简称', 1451550551, 1483076343, 10),
(24, 'MAIL_TYPE', 3, 'TEST:Mail parameter test\r\nINTEREST:Interest rate information\r\nSYSERROR:System error information\r\nINSTZERO:Interest rate less zero\r\nRATEWARN:Interest rate spread warning\r\nINTRATEUPL:Interest rate file upload', '', 'Email business type ', '邮件业务类型', 9, '', '', 1466830514, 1474611585, 10),
(29, 'TENOR_ARRAY', 3, 'Saving:Saving\r\nO/N:O/N\r\n1W:One week\r\n2W:Two weeks\r\n1M:One Month\r\n2M:Two Months\r\n3M:Three Months\r\n6M:Six Months\r\n9M:Nine Months\r\n12M:One year', '', 'Tenor config', '存期配置', 9, '', '', 1467905442, 1480646605, 10),
(30, 'EXCEL_TITLE_COLOR', 1, '57C43C', '', 'Excel file title color(RGB)', 'Excel文件标题栏颜色(RGB)', 2, '', '', 1467967852, 1480646687, 10),
(32, 'DATA_BACKUP_PATH', 2, './Data/', '', 'The database backup directory', '数据库备份目录', 4, 'The path must end with a /', '路径必须以 / 结尾', 1469259454, 1469259454, 10),
(33, 'DATA_BACKUP_PART_SIZE', 0, '20971520', '', 'The database backup roll size', '数据库备份卷大小', 4, 'The value is used to limit the maximum length of compressed volume classification. Unit: B; Suggest ', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1469259542, 1469259542, 10),
(34, 'DATA_BACKUP_COMPRESS', 4, '1', '0:不压缩\r\n1:启用压缩', 'Whether to enable the database backup file compres', '数据库备份文件是否启用压缩', 4, 'Compressed backup files need the  PHP environment to support gzopen and gzwrite function', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1469259714, 1470468980, 10),
(35, 'DATA_BACKUP_COMPRESS_LEVEL', 4, '9', '1:普通\r\n4:一般\r\n9:最高', 'The database backup file compression level', '数据库备份文件压缩级别', 4, 'The database backup file compression level, this configuration in enable compression', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1469259821, 1469259821, 10),
(36, 'INT_RATE_WARNING_SPREAD', 3, 'ALL:0.3\r\nHKD:0.3\r\nCNY:0.3', '', 'Interest rate warning spread', '优惠利率提示参数', 9, '', '', 1470276691, 1480646777, 10),
(37, 'RECEIVER_EMAIL_ADDR_FOR_SYS', 2, 'test@hisuntech.com', '', 'Receiver email for system', '系统运行异常时接收邮件的邮件地址', 3, '', '当系统运行异常时，接收通知邮件Email地址。地址可以多个，以英文冒号“;”分隔。', 1470306999, 1483076382, 10),
(38, 'CLIENT_APPOVE', 4, 'Y', 'Y:Yes\r\nN:No', 'Client information update appove', '客户信息修改是否需要复核', 9, '', '', 1470880795, 1493031421, 10),
(39, 'ACCESS_APPOVE', 4, 'Y', 'N:No\r\nY:Yes', 'Access information update appove', '权限信息维护是否需要复核', 9, '', '', 1471333197, 1493031421, 10),
(40, 'ESB_SWITCH', 4, 'OFF', 'ON:On\r\nOFF:Off', 'Esb system switch', 'ESB系统开关', 4, '', '', 1471419012, 1472439806, 10),
(41, 'ESB_CONFIG', 2, '10.97.229.112:36000', NULL, 'Esb system config', 'Esb系统配置', 4, 'format（IP:port）', '格式（IP:port）', 1471168840, 1471168925, 10),
(42, 'INT_RATE_UPLOAD_FILE', 2, '/Applications/XAMPP/htdocs/msg/Data/rate', '', ' File Name For Interest Rate Upload', '利率上传文件名称', 4, ' File Name For Interest Rate Upload（Include file route &amp; file name）', '利率上传文件名称（包含文件目录及文件名）', 1474602996, 1474612564, 10),
(43, 'INT_RATE_UPLOAD_RECEIVER', 2, 'uitslhb@szuits.com', '', 'Receiver for interest rate upload email', '利率上传通知邮件收件人', 9, '', '', 1474603084, 1474603084, 10);

-- --------------------------------------------------------

--
-- 表的结构 `uits_currency`
--

CREATE TABLE `uits_currency` (
  `id` varchar(3) NOT NULL COMMENT '货币名称',
  `name_en` varchar(120) NOT NULL COMMENT '货币英文名称',
  `name_zh` varchar(120) NOT NULL COMMENT '货币中文名称',
  `status` tinyint(1) NOT NULL COMMENT '状态：0-禁用，1-启用',
  `sign` varchar(3) DEFAULT NULL COMMENT '货币标识',
  `sort` tinyint(3) NOT NULL COMMENT '排序',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='倾币信息表';

--
-- 转存表中的数据 `uits_currency`
--

INSERT INTO `uits_currency` (`id`, `name_en`, `name_zh`, `status`, `sign`, `sort`, `remark`) VALUES
('AUD', 'AUSTRALIAN DOLLARS', '澳元', 1, '$', 100, '澳元'),
('BRL', 'BRAZILIAN REAL', '巴西雷亚尔', 1, 'R$', 100, '巴西雷亚尔'),
('CAD', 'CANADIAN DOLLARS', '加拿大元', 1, '$', 100, '加拿大元'),
('CHF', 'SWISS FRANC', '瑞士法郎', 1, '$', 100, '瑞士法郎'),
('CNH', 'RENMINBI YUAN IN HK MKT', '人民币（HK MKT）', 1, '￥', 100, '人民币（HK MKT）'),
('CNN', 'RENMINBI YUAN FOR NDF', '人币币（NDF）', 1, '￥', 100, '人币币（NDF）'),
('CNY', 'China Yuan', '人民币', 1, '￥', 20, 'China Yuan'),
('EUR', 'EURO', '欧元', 1, '€', 100, '欧元'),
('GBP', 'STERLING POUND', '英镑', 1, '￡', 100, '英镑'),
('HKD', 'HONG KONG DOLLARS', '港币', 1, '$', 30, 'HKD'),
('IDR', 'INDONESIAN RUPIAH', '印尼盾', 1, 'Rp', 100, '印尼盾'),
('INR', 'INDIAN RUPEE', '印度卢比', 1, 'Rs.', 100, '印度卢比'),
('JPY', 'JAPANESE YEN', '日元', 1, '￥', 100, '日元'),
('KRW', 'KOREAN WON', '韩元', 1, '₩', 100, '韩元'),
('NZD', 'NEW ZEALAND DOLLARS', '新西兰元', 1, '$', 100, '新西兰元'),
('RUB', 'RUSSIAN RUBLE', '俄罗斯卢布', 1, '₽', 100, '俄罗斯卢布'),
('SEK', 'SWEDEN KRONA', '瑞典克朗', 1, '$', 100, '瑞典克朗'),
('SGD', 'SINGAPORE DOLLARS', '新加坡元', 1, '$', 100, '新加坡元'),
('THB', 'THAI BAHT', '泰铢', 1, '฿', 100, '泰铢'),
('TWD', 'NEW TAIWAN DOLLARS', '新台币', 1, 'NT', 100, 'TWD'),
('USD', 'UNITED STATES DOLLARS', '美元', 1, '$', 10, 'USD'),
('ZAR', 'SOUTH AFRICAN RAND', '南非兰特', 1, 'R', 100, '南非兰特');

-- --------------------------------------------------------

--
-- 表的结构 `uits_dept`
--

CREATE TABLE `uits_dept` (
  `id` int(10) NOT NULL COMMENT '部门ID',
  `name` varchar(30) NOT NULL COMMENT '部门名称',
  `pid` int(10) NOT NULL COMMENT '父级ID',
  `sort` tinyint(3) NOT NULL COMMENT '排序字段',
  `area` varchar(4) DEFAULT NULL COMMENT '地区',
  `property` tinyint(1) DEFAULT NULL COMMENT '属性',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='部门表';

--
-- 转存表中的数据 `uits_dept`
--

INSERT INTO `uits_dept` (`id`, `name`, `pid`, `sort`, `area`, `property`, `remark`, `ts`) VALUES
(1, 'Department Test', 0, 100, '', 0, 'Department Test', '2016-07-28 04:01:16'),
(2, 'IT', 0, 100, '', 0, '', '2016-08-11 00:29:17'),
(3, 'FID', 0, 100, '', 0, '', '2016-08-15 01:14:42'),
(4, 'CBD', 0, 100, '', 0, '', '2016-08-15 01:14:51'),
(5, 'GMT', 0, 100, '', 0, '', '2016-08-15 18:22:10');

-- --------------------------------------------------------

--
-- 表的结构 `uits_emp`
--

CREATE TABLE `uits_emp` (
  `id` int(10) NOT NULL COMMENT '员工ID',
  `email` varchar(30) DEFAULT NULL COMMENT '员工邮箱地址',
  `name` varchar(30) DEFAULT NULL COMMENT '姓名',
  `nickname` varchar(30) DEFAULT NULL COMMENT '昵称',
  `sex` varchar(1) DEFAULT NULL COMMENT '姓名',
  `birthday` int(10) DEFAULT NULL COMMENT '生日',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号码',
  `password` varchar(32) NOT NULL COMMENT '登录密码',
  `pwd_err_count` tinyint(1) NOT NULL DEFAULT '0' COMMENT '密码输错次数',
  `pwd_change_date` int(10) DEFAULT NULL COMMENT '密码修改日期',
  `status` char(1) DEFAULT '' COMMENT '状态，空为正常，其他不正常',
  `is_director` tinyint(1) DEFAULT NULL COMMENT '主管标志:0-否,1-是',
  `login_switch` tinyint(1) DEFAULT NULL COMMENT '登录开关',
  `login_count` int(10) DEFAULT NULL COMMENT '登录总次数',
  `last_login_time` int(10) DEFAULT NULL COMMENT '最后分录时间',
  `real_email` varchar(30) DEFAULT NULL COMMENT '真实邮箱地址（用于外包人员）',
  `dept_id` int(10) NOT NULL COMMENT '员工归属部门',
  `img_file` varchar(30) NOT NULL COMMENT '头像文件',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` int(10) NOT NULL COMMENT '记录建立时间',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='员工信息表';

--
-- 转存表中的数据 `uits_emp`
--

INSERT INTO `uits_emp` (`id`, `email`, `name`, `nickname`, `sex`, `birthday`, `mobile`, `password`, `pwd_err_count`, `pwd_change_date`, `status`, `is_director`, `login_switch`, `login_count`, `last_login_time`, `real_email`, `dept_id`, `img_file`, `remark`, `create_time`, `ts`) VALUES
(3, 'david_YLchan', 'David Chan', '', '', 0, '', '1ef19c193b5dec8be6564fb6a0502e19', 0, NULL, 'A', 0, 1, 4, 1471413862, '', 2, 'noneImg.jpg', '123321', 1470967157, '2016-08-11 17:59:17'),
(5, 'yangsz1', 'Ricky Yeung', '', '', 0, '', '278e375671c41827d197487b76ddbeeb', 0, NULL, 'A', 1, 1, 0, 0, '', 2, 'noneImg.jpg', '', 1471251382, '2016-08-15 00:56:22'),
(6, 'jasmine_chan', 'Jasmine Chan', '', '', 0, '', 'f76e9ac17cb465950d2711b2de44a63f', 0, NULL, 'A', 0, 1, 14, 1471406425, '', 5, 'noneImg.jpg', '', 1471314358, '2016-08-15 18:25:58'),
(7, 'xiao_xiruo', 'Jessie Xiao', '', '', 0, '', '278e375671c41827d197487b76ddbeeb', 0, NULL, 'A', 0, 1, 4, 1471318736, '', 5, 'noneImg.jpg', '', 1471316131, '2016-08-15 18:55:31'),
(11, 'SUPER01', 'Super Account 01', '', '', 0, '', '1ef19c193b5dec8be6564fb6a0502e19', 0, NULL, 'A', 0, 1, 1, 1473409598, '', 2, 'noneImg.jpg', '', 1471333740, '2016-08-15 23:49:00'),
(13, 'tony_zhuyixin', 'Tony Zhu', '', '', 0, '', 'f9ba95253f9aa10d2617ce1f2064086b', 0, NULL, 'A', 0, 1, 6, 1471413914, '', 4, 'noneImg.jpg', '', 1471333823, '2016-08-15 23:50:23'),
(14, 'elky_fong', 'Elky Fong', '', '', 0, '', 'f9ba95253f9aa10d2617ce1f2064086b', 0, NULL, 'A', 0, 0, 4, 1471420245, '', 3, 'noneImg.jpg', '', 1471333861, '2016-08-15 23:51:01'),
(16, 'test', 'test', NULL, NULL, NULL, '', '3d4d4b67556f5d1b089b05f4548969e5', 0, 1494864000, 'A', 0, 1, 24, 1494922685, NULL, 1, 'noneImg.jpg', 'test', 1471833326, '2016-08-22 02:35:26'),
(17, 'test123', 'test123', NULL, '', NULL, '', '1ef19c193b5dec8be6564fb6a0502e19', 0, 1492963200, 'A', 0, 1, 5, 1493031459, NULL, 4, 'noneImg.jpg', '123', 1472458602, '2016-08-29 08:16:42'),
(18, '', 'test11111', 'hello', NULL, NULL, '', '1ef19c193b5dec8be6564fb6a0502e19', 1, 1476374400, 'D', 0, 1, 2, 1476431934, NULL, 1, 'noneImg.jpg', '', 1476431874, '2016-10-14 07:57:54');

-- --------------------------------------------------------

--
-- 表的结构 `uits_log`
--

CREATE TABLE `uits_log` (
  `empid` int(10) NOT NULL COMMENT '用户ID',
  `time` int(10) NOT NULL COMMENT '发生时间',
  `ip` varchar(15) NOT NULL COMMENT '访问IP地址',
  `content` text NOT NULL COMMENT '明细'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户操作日志';

-- --------------------------------------------------------

--
-- 表的结构 `uits_maillog`
--

CREATE TABLE `uits_maillog` (
  `date` int(10) NOT NULL COMMENT '发送日期',
  `seq` int(10) NOT NULL COMMENT '序号',
  `outside_flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '外部邮件标志：0-内部，1-外部',
  `mailtpl_id` int(10) NOT NULL COMMENT '模板ID',
  `ci_no` int(10) NOT NULL COMMENT '客户号',
  `receiver` text NOT NULL COMMENT '收件人',
  `emp_id` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '发送状态：0-成功，1-失败',
  `send_time` int(10) NOT NULL COMMENT '发送开始时间',
  `comp_time` int(10) NOT NULL COMMENT '发送完成时间',
  `error_msg` varchar(255) DEFAULT NULL COMMENT '失败原因'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮件LOG';

--
-- 转存表中的数据 `uits_maillog`
--

INSERT INTO `uits_maillog` (`date`, `seq`, `outside_flag`, `mailtpl_id`, `ci_no`, `receiver`, `emp_id`, `status`, `send_time`, `comp_time`, `error_msg`) VALUES
(1471449600, 1, 0, 6, 5, 'yldchan@gmail.com', 0, 0, 1471504297, 1471504297, 'SMTP connect() failed.'),
(1471449600, 2, 0, 6, 6, 'yldchan@gmail.com', 1, 0, 1471504317, 1471504317, 'SMTP connect() failed.'),
(1471449600, 3, 0, 6, 7, 'yldchan@gmail.com', 1, 0, 1471505188, 1471505188, 'SMTP connect() failed.'),
(1471449600, 4, 0, 6, 8, 'yldchan@gmail.com', 1, 0, 1471505219, 1471505219, 'SMTP connect() failed.'),
(1471449600, 5, 0, 6, 9, 'yldchan@gmail.com', 1, 0, 1471506958, 1471506958, 'SMTP connect() failed.'),
(1471449600, 6, 0, 6, 0, 'yldchan@gmail.com', 1, 0, 1471507029, 1471507029, 'SMTP connect() failed.'),
(1471449600, 7, 0, 6, 0, 'yldchan@gmail.com', 1, 0, 1471507878, 1471507878, 'SMTP connect() failed.'),
(1471536000, 1, 0, 6, 0, 'ricky.yeung@gmail.com', 1, 1, 1471577086, 1471577086, ''),
(1471536000, 2, 0, 6, 0, 'ricky.yeung@gmail.com', 1, 1, 1471577213, 1471577213, ''),
(1471536000, 3, 0, 6, 0, 'ricky.yeung@gmail.com', 1, 1, 1471577248, 1471577248, ''),
(1471536000, 4, 0, 6, 0, 'ricky.yeung@gmail.com', 1, 1, 1471577711, 1471577711, ''),
(1471536000, 5, 0, 6, 0, 'ricky.yeung@gmail.com', 1, 1, 1471577772, 1471577772, ''),
(1471536000, 6, 0, 6, 0, 'test@test.com', 1, 1, 1471577816, 1471577816, ''),
(1471536000, 7, 0, 6, 0, 'test@test.com', 1, 1, 1471578116, 1471578116, ''),
(1471536000, 8, 0, 6, 0, 'test@test.com', 1, 1, 1471578544, 1471578544, ''),
(1471536000, 9, 0, 6, 0, 'test@test.com', 1, 1, 1471578615, 1471578615, ''),
(1471536000, 10, 0, 6, 0, 'test@test.com', 1, 1, 1471579514, 1471579514, ''),
(1471536000, 11, 0, 6, 0, 'test@test.com', 1, 0, 1471580274, 1471580274, 'Transaction code did not config in HUBTXNCTL. Code: NELG'),
(1471536000, 12, 0, 6, 0, 'test@test.com', 1, 0, 1471580319, 1471580319, 'Transaction code did not config in HUBTXNCTL. Code: NELG'),
(1471536000, 13, 0, 6, 0, 'test@test.com', 1, 1, 1471580535, 1471580535, ''),
(1471536000, 14, 0, 6, 0, 'test@test.com', 1, 0, 1471581708, 1471581708, 'SMTP connect() failed.'),
(1471536000, 15, 0, 6, 0, 'test@test.com', 1, 0, 1471581914, 1471581914, 'SMTP connect() failed.'),
(1471536000, 16, 0, 6, 0, 'test@test.com', 1, 0, 1471582168, 1471582168, 'SMTP connect() failed.'),
(1471536000, 17, 0, 6, 0, 'test@test.com', 1, 0, 1471582285, 1471582285, 'SMTP connect() failed.'),
(1471536000, 18, 0, 6, 0, 'test@test.com', 1, 0, 1471582384, 1471582384, 'SMTP connect() failed.'),
(1471536000, 19, 0, 6, 0, 'test@test.com', 1, 0, 1471582442, 1471582442, 'SMTP connect() failed.'),
(1471536000, 20, 0, 6, 0, 'test@test.com', 1, 0, 1471582510, 1471582510, 'SMTP connect() failed.'),
(1471536000, 21, 0, 6, 0, 'test@test.com', 1, 0, 1471582643, 1471582643, 'SMTP connect() failed.'),
(1471536000, 22, 0, 6, 0, 'test@test.com', 1, 0, 1471582720, 1471582720, 'SMTP connect() failed.'),
(1471536000, 23, 0, 6, 0, 'test@test.com', 1, 0, 1471583063, 1471583063, 'Could not instantiate mail function.'),
(1471536000, 24, 0, 6, 0, 'test@test.com', 1, 0, 1471583968, 1471583968, 'SMTP connect() failed.'),
(1471536000, 25, 0, 6, 0, 'test@test.com', 1, 0, 1471584092, 1471584092, 'SMTP connect() failed.'),
(1471536000, 26, 0, 6, 0, 'test@test.com', 1, 0, 1471584111, 1471584111, 'Could not instantiate mail function.'),
(1471536000, 27, 0, 6, 0, 'test@test.com', 1, 0, 1471584333, 1471584333, 'Could not instantiate mail function.'),
(1471536000, 28, 0, 6, 0, 'test@test.com', 1, 0, 1471584535, 1471584535, 'Could not instantiate mail function.'),
(1471536000, 29, 0, 6, 0, 'test@test.com', 1, 0, 1471584565, 1471584565, 'Could not instantiate mail function.'),
(1471536000, 30, 0, 6, 0, 'test@test.com', 1, 0, 1471584577, 1471584577, 'SMTP connect() failed.'),
(1471536000, 31, 0, 6, 0, 'test@test.com', 1, 0, 1471587037, 1471587037, 'SMTP connect() failed.'),
(1471536000, 32, 0, 6, 0, 'test@test.com', 1, 0, 1471587214, 1471587214, 'SMTP connect() failed.'),
(1471536000, 33, 0, 6, 0, 'test@test.com', 1, 0, 1471587736, 1471587736, 'SMTP connect() failed.'),
(1471536000, 34, 0, 6, 0, 'test@test.com', 1, 0, 1471588022, 1471588022, 'SMTP connect() failed.'),
(1471536000, 35, 0, 6, 0, 'test@test.com', 1, 0, 1471588083, 1471588083, 'SMTP connect() failed.'),
(1471536000, 36, 0, 6, 0, 'test@test.com', 1, 0, 1471588410, 1471588410, 'SMTP connect() failed.'),
(1471536000, 37, 0, 6, 0, 'test@test.com', 1, 0, 1471588480, 1471588480, 'SMTP connect() failed.'),
(1471536000, 38, 0, 6, 0, 'test@test.com', 1, 0, 1471589773, 1471589773, 'SMTP connect() failed.'),
(1471536000, 39, 0, 6, 0, 'test@test.com', 1, 0, 1471591166, 1471591166, 'SMTP connect() failed.'),
(1471536000, 40, 0, 6, 0, 'test@test.com', 1, 0, 1471591216, 1471591216, 'SMTP connect() failed.'),
(1471536000, 41, 0, 6, 0, 'test@test.com', 1, 0, 1471591309, 1471591309, 'SMTP connect() failed.'),
(1471536000, 42, 0, 6, 0, 'test@test.com', 1, 0, 1471591464, 1471591464, 'SMTP connect() failed.'),
(1471536000, 43, 0, 6, 0, 'test@test.com', 1, 0, 1471591540, 1471591540, 'SMTP connect() failed.'),
(1471536000, 44, 0, 6, 0, 'test@test.com', 1, 0, 1471591640, 1471591640, 'SMTP connect() failed.'),
(1471536000, 45, 0, 6, 0, 'test@test.com', 1, 0, 1471591679, 1471591679, 'SMTP connect() failed.'),
(1471536000, 46, 0, 6, 0, 'test@test.com', 1, 0, 1471591738, 1471591738, 'SMTP connect() failed.'),
(1471536000, 47, 0, 6, 0, 'test@test.com', 1, 0, 1471591814, 1471591814, 'SMTP connect() failed.'),
(1471536000, 48, 0, 6, 0, 'test@test.com', 1, 0, 1471591870, 1471591870, 'SMTP connect() failed.'),
(1471536000, 49, 0, 6, 0, 'test@test.com', 1, 0, 1471591955, 1471591955, 'SMTP connect() failed.'),
(1471536000, 50, 0, 6, 0, 'test@test.com', 1, 0, 1471591992, 1471591992, 'SMTP connect() failed.'),
(1471536000, 51, 0, 6, 0, 'test@test.com', 1, 0, 1471592019, 1471592019, 'SMTP connect() failed.'),
(1471536000, 52, 0, 6, 0, 'test@test.com', 1, 0, 1471592046, 1471592046, 'SMTP connect() failed.'),
(1471536000, 53, 0, 6, 0, 'test@test.com', 1, 0, 1471592075, 1471592075, 'SMTP connect() failed.'),
(1471536000, 54, 0, 6, 0, 'test@test.com', 1, 0, 1471592123, 1471592123, 'SMTP connect() failed.'),
(1471536000, 55, 0, 6, 0, 'test@test.com', 1, 0, 1471592182, 1471592182, 'SMTP connect() failed.'),
(1471536000, 56, 0, 6, 0, 'test@test.com', 1, 0, 1471592377, 1471592377, 'SMTP connect() failed.'),
(1471536000, 57, 0, 6, 0, 'test@test.com', 1, 0, 1471592432, 1471592432, 'SMTP connect() failed.'),
(1471536000, 58, 0, 6, 0, 'test@test.com', 1, 0, 1471592471, 1471592471, 'SMTP connect() failed.'),
(1471536000, 59, 0, 6, 0, 'test@test.com', 1, 0, 1471592526, 1471592526, 'SMTP connect() failed.'),
(1471536000, 60, 0, 6, 0, 'test@test.com', 1, 0, 1471592581, 1471592581, 'SMTP connect() failed.'),
(1471536000, 61, 0, 6, 0, 'test@test.com', 1, 0, 1471592615, 1471592615, 'SMTP connect() failed.'),
(1471536000, 62, 0, 6, 0, 'test@test.com', 1, 0, 1471592895, 1471592895, 'SMTP connect() failed.'),
(1471536000, 63, 0, 6, 0, 'test@test.com', 1, 0, 1471592906, 1471592906, 'SMTP connect() failed.'),
(1471536000, 64, 0, 6, 0, 'test@test.com', 1, 0, 1471593229, 1471593229, 'SMTP connect() failed.'),
(1471536000, 65, 0, 6, 0, 'test@test.com', 1, 0, 1471593332, 1471593332, 'SMTP connect() failed.'),
(1471536000, 66, 0, 6, 0, 'test@test.com', 1, 0, 1471593384, 1471593384, 'SMTP connect() failed.'),
(1471536000, 67, 0, 6, 0, 'test@test.com', 1, 0, 1471593420, 1471593420, 'SMTP connect() failed.'),
(1471536000, 68, 0, 6, 0, 'test@test.com', 1, 0, 1471593515, 1471593516, 'SMTP Error: The following recipients failed: test@test.com'),
(1471536000, 69, 0, 6, 0, 'test@test.com', 1, 0, 1471593589, 1471593589, 'The following From address failed: root@localhost : MAIL FROM command failed,550,,The address is not valid.\r\n'),
(1471536000, 70, 0, 6, 0, 'test@test.com', 1, 0, 1471593697, 1471593697, 'SMTP Error: The following recipients failed: test@test.com'),
(1471536000, 71, 0, 6, 0, 'test@test.com', 1, 0, 1471593778, 1471593778, 'SMTP connect() failed.'),
(1471536000, 72, 0, 6, 0, 'test@test.com', 1, 0, 1471593901, 1471593901, 'SMTP connect() failed.'),
(1471536000, 73, 0, 6, 0, 'test@test.com', 1, 0, 1471593985, 1471593985, 'SMTP connect() failed.'),
(1471536000, 74, 0, 6, 0, 'test@test.com', 1, 0, 1471594193, 1471594193, 'SMTP connect() failed.'),
(1471536000, 75, 0, 6, 0, 'test@test.com', 1, 0, 1471594373, 1471594373, 'The following From address failed: root@localhost : MAIL FROM command failed,550,,The address is not valid.\r\n'),
(1471536000, 76, 0, 6, 0, 'test@test.com', 1, 0, 1471594531, 1471594531, 'The following From address failed: root@localhost : MAIL FROM command failed,550,,The address is not valid.\r\n'),
(1471536000, 77, 0, 6, 0, 'test@test.com', 1, 0, 1471594582, 1471594582, 'SMTP connect() failed.'),
(1471536000, 78, 0, 10, 0, 'test@test.com', 1, 1, 1471594708, 1471594708, ''),
(1471536000, 79, 0, 10, 0, 'test@test.com', 1, 1, 1471594890, 1471594890, ''),
(1471536000, 80, 0, 10, 0, 'test@test.com', 1, 1, 1471594966, 1471594966, ''),
(1471536000, 81, 0, 10, 0, 'test@test.com', 1, 1, 1471595011, 1471595011, ''),
(1471536000, 82, 0, 10, 0, 'testa@test.spdb.com.cn', 1, 1, 1471595060, 1471595060, ''),
(1471536000, 83, 0, 10, 0, 'testa@test.spdb.com.cn', 1, 0, 1471595364, 1471595364, 'SMTP connect() failed.'),
(1471536000, 84, 0, 10, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471595670, 1471595670, ''),
(1471536000, 85, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471595783, 1471595783, ''),
(1471536000, 86, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471595824, 1471595824, ''),
(1471536000, 87, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471596405, 1471596405, ''),
(1471536000, 88, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471596444, 1471596444, ''),
(1471536000, 89, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471596526, 1471596526, ''),
(1471536000, 90, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471596593, 1471596593, ''),
(1471536000, 91, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471596637, 1471596637, ''),
(1471536000, 92, 0, 6, 0, 'HKBS@test.spdb.com.cn', 1, 1, 1471596746, 1471596746, ''),
(1471536000, 93, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 1, 1, 1471597423, 1471597423, ''),
(1471536000, 94, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 1, 1, 1471597933, 1471597933, ''),
(1471536000, 95, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 1, 1, 1471599321, 1471599321, ''),
(1471536000, 96, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 1, 0, 1471600415, 1471600415, 'ESB Retrun Error'),
(1471536000, 97, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 1, 0, 1471600460, 1471600460, 'ESB Retrun Error'),
(1471536000, 98, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 1, 1, 1471600491, 1471600491, ''),
(1471536000, 99, 0, 6, 0, 'yldchan@gmail.com', 0, 0, 1471602191, 1471602191, 'ESB Retrun Error'),
(1471536000, 100, 0, 6, 0, 'spdbtesting@gmai.com', 0, 0, 1471602192, 1471602192, 'ESB Retrun Error'),
(1471536000, 101, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 0, 1471602195, 1471602195, 'ESB Retrun Error'),
(1471536000, 102, 0, 6, 0, 'yldchan@gmail.com', 0, 0, 1471602202, 1471602202, 'ESB Retrun Error'),
(1471536000, 103, 0, 6, 0, 'spdbtesting@gmai.com', 0, 0, 1471602203, 1471602203, 'ESB Retrun Error'),
(1471536000, 104, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 0, 1471602205, 1471602205, 'ESB Retrun Error'),
(1471536000, 105, 0, 6, 0, 'yldchan@gmail.com', 0, 0, 1471603060, 1471603060, 'ESB Retrun Error'),
(1471536000, 106, 0, 6, 0, 'spdbtesting@gmai.com', 0, 0, 1471603061, 1471603061, 'ESB Retrun Error'),
(1471536000, 107, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 0, 1471603063, 1471603063, 'ESB Retrun Error'),
(1471536000, 108, 0, 6, 0, 'yldchan@gmail.com', 0, 1, 1471603614, 1471603614, ''),
(1471536000, 109, 0, 6, 0, 'spdbtesting@gmai.com', 0, 1, 1471603616, 1471603616, ''),
(1471536000, 110, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 1, 1471603619, 1471603619, ''),
(1471536000, 111, 0, 6, 0, 'yldchan@gmail.com', 0, 1, 1471604101, 1471604101, ''),
(1471536000, 112, 0, 6, 0, 'spdbtesting@gmai.com', 0, 1, 1471604103, 1471604103, ''),
(1471536000, 113, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 1, 1471604117, 1471604117, ''),
(1471536000, 114, 0, 6, 0, 'yldchan@gmail.com', 0, 1, 1471604587, 1471604587, ''),
(1471536000, 115, 0, 6, 0, 'spdbtesting@gmai.com', 0, 1, 1471604588, 1471604588, ''),
(1471536000, 116, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 1, 1471604591, 1471604591, ''),
(1471536000, 117, 0, 6, 0, 'yldchan@gmail.com', 0, 1, 1471604620, 1471604620, ''),
(1471536000, 118, 0, 6, 0, 'spdbtesting@gmai.com', 0, 1, 1471604622, 1471604622, ''),
(1471536000, 119, 0, 6, 0, 'HKBS@test.spdb.com.cn;testa@test.spdb.com.cn;testb@test.spdb.com.cn', 0, 1, 1471604629, 1471604629, ''),
(1472400000, 1, 1, 6, 0, 'lhb_5@163.com', 1, 0, 1472439644, 1472439644, 'Operation timed out'),
(1472400000, 2, 0, 6, 9, 'admin@szuitss.com.cn;david_YLchan@szuitss.com.cn;SUPER01@szuitss.com.cn;SUPER02@szuitss.com.cn;test@szuitss.com.cn;yangsz1@szuitss.com.cn', 1, 1, 1472439646, 1472439646, ''),
(1472400000, 3, 1, 6, 9, 'lhb_5@163.com', 1, 0, 1472439749, 1472439749, 'Operation timed out'),
(1472400000, 4, 0, 6, 9, 'admin@szuitss.com.cn;david_YLchan@szuitss.com.cn;SUPER01@szuitss.com.cn;SUPER02@szuitss.com.cn;test@szuitss.com.cn;yangsz1@szuitss.com.cn', 1, 1, 1472439750, 1472439750, ''),
(1472400000, 5, 1, 6, 9, 'lhb_5@163.com', 1, 1, 1472439815, 1472439815, ''),
(1472400000, 6, 0, 6, 9, 'admin@szuitss.com.cn;david_YLchan@szuitss.com.cn;SUPER01@szuitss.com.cn;SUPER02@szuitss.com.cn;test@szuitss.com.cn;yangsz1@szuitss.com.cn', 0, 1, 1472439816, 1472439816, ''),
(1472486400, 1, 1, 6, 9, 'lhb_5@163.com;lin_hb@hisuntech.com', 1, 0, 1472544218, 1472544218, '系统错误'),
(1472486400, 2, 1, 6, 9, 'lhb_5@163.com;lin_hb@hisuntech.com', 1, 0, 1472544275, 1472544275, '系统错误'),
(1472486400, 3, 1, 6, 9, 'lhb_5@163.com;lin_hb@hisuntech.com', 1, 0, 1472544325, 1472544325, '系统错误'),
(1472486400, 4, 1, 6, 9, 'lhb_5@163.com;lin_hb@hisuntech.com', 1, 0, 1472544349, 1472544349, '系统错误'),
(1472486400, 5, 1, 6, 9, 'lhb_5@163.com;lin_hb@hisuntech.com', 1, 1, 1472544368, 1472544368, ''),
(1472486400, 6, 0, 6, 9, 'admin@szuitss.com.cn;david_YLchan@szuitss.com.cn;SUPER01@szuitss.com.cn;SUPER02@szuitss.com.cn;test@szuitss.com.cn;yangsz1@szuitss.com.cn', 1, 1, 1472544370, 1472544370, ''),
(1474560000, 1, 0, 11, 0, 'uitslhb@szuits.com', 0, 1, 1474612659, 1474612659, NULL),
(1474560000, 2, 0, 11, 0, 'uitslhb@szuits.com', 0, 1, 1474613074, 1474613074, NULL),
(1474560000, 3, 0, 11, 0, 'uitslhb@szuits.com', 0, 1, 1474613435, 1474613435, NULL),
(1474560000, 4, 0, 11, 0, 'uitslhb@szuits.com', 0, 1, 1474613689, 1474613689, NULL),
(1474560000, 5, 0, 11, 0, 'uitslhb@szuits.com', 0, 1, 1474613954, 1474613954, NULL),
(1474560000, 6, 0, 11, 0, 'uitslhb@szuits.com', 0, 1, 1474617168, 1474617168, NULL),
(1476201600, 1, 1, 6, 9, 'test@test.com', 0, 1, 1476269601, 1476269601, ''),
(1476201600, 2, 0, 6, 9, 'test123@szuitss.com.cn', 0, 1, 1476269601, 1476269601, ''),
(1476201600, 3, 0, 8, 0, 'test123@szuitss.com.cn', 0, 1, 1476269602, 1476269602, ''),
(1476374400, 1, 1, 6, 9, 'test@test.com', 1, 0, 1476432090, 1476432090, 'Email地址不归属于当前客户'),
(1480953600, 1, 1, 6, 9, 'test@test.com', 1, 0, 1480993256, 1480993256, '收件地址被设置为拒收'),
(1480953600, 2, 1, 6, 9, 'hiprrrr@gmail.com', 1, 1, 1480993284, 1480993284, ''),
(1480953600, 3, 1, 6, 9, 'hiprrrr@gmail.com', 1, 1, 1480995613, 1480995613, ''),
(1480953600, 4, 0, 7, 0, 'uitslhb@szuits.com', 0, 1, 1480996355, 1480996355, ''),
(1480953600, 5, 1, 6, 9, 'hiprrrr@gmail.com', 0, 1, 1480996709, 1480996709, ''),
(1480953600, 6, 0, 7, 0, 'uitslhb@szuits.com', 0, 1, 1480997205, 1480997205, ''),
(1481040000, 1, 0, 7, 0, 'uitslhb@szuits.com', 0, 1, 1481075688, 1481075688, ''),
(1481040000, 2, 1, 6, 9, 'hiprrrr@gmail.com', 1, 1, 1481075842, 1481075842, ''),
(1481040000, 3, 1, 6, 9, 'hiprrrr@gmail.com', 0, 1, 1481075946, 1481075946, '');

-- --------------------------------------------------------

--
-- 表的结构 `uits_mailrefuse`
--

CREATE TABLE `uits_mailrefuse` (
  `mail` varchar(64) NOT NULL COMMENT '电邮地址',
  `time` int(10) NOT NULL COMMENT '维护时间',
  `emp_id` int(10) NOT NULL COMMENT '维护人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拒收邮件表';

--
-- 转存表中的数据 `uits_mailrefuse`
--

INSERT INTO `uits_mailrefuse` (`mail`, `time`, `emp_id`) VALUES
('test2@test2.com', 1478512287, 16),
('test3@test2.com', 1478512298, 16),
('test@test.com', 1478512277, 16);

-- --------------------------------------------------------

--
-- 表的结构 `uits_mailtpl`
--

CREATE TABLE `uits_mailtpl` (
  `id` int(10) NOT NULL COMMENT 'tpl id',
  `name` varchar(120) NOT NULL COMMENT 'tpl名称',
  `title_en` varchar(120) DEFAULT NULL COMMENT '邮件标题',
  `title_zh_s` varchar(120) DEFAULT NULL COMMENT '简体中文标题',
  `title_zh_t` varchar(120) DEFAULT NULL COMMENT '繁体中文标题',
  `type` varchar(30) DEFAULT NULL COMMENT '邮件业务类型',
  `cc_user_group` int(10) DEFAULT NULL COMMENT '抄送的用户组',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `en_content` text COMMENT '英文模板(JSON格式)',
  `zh_s_content` text COMMENT '简体中文模板(JSON)格式',
  `zh_t_content` text COMMENT '繁体中文模板(JSON格式)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮件模板列表';

--
-- 转存表中的数据 `uits_mailtpl`
--

INSERT INTO `uits_mailtpl` (`id`, `name`, `title_en`, `title_zh_s`, `title_zh_t`, `type`, `cc_user_group`, `remark`, `en_content`, `zh_s_content`, `zh_t_content`) VALUES
(5, 'Mail parameter test', 'Mail parameter test', '', '', 'TEST', 0, 'Mail parameter test', '\"&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t&lt;tbody&gt;\\r\\n\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t&lt;table align=&quot;center&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse; border:1px solid #cccccc; width:600px&quot;&gt;\\r\\n\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;img alt=&quot;&quot; src=&quot;http:\\/\\/p1.so.qhimg.com\\/t01d43fe817b042067b.jpg&quot; style=&quot;display:block; height:430px; width:600px&quot; \\/&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td style=&quot;text-align:center&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;h2&gt;&lt;strong&gt;\\u987a\\u52bf\\u800c\\u4e3a\\uff0c\\u8fc8\\u5411\\u56fd\\u9645&lt;\\/strong&gt;&lt;\\/h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\u3000\\u30002011\\u5e746\\u67088\\u65e5\\uff0c\\u6d66\\u53d1\\u94f6\\u884c\\u9999\\u6e2f\\u5206\\u884c\\u4f5c\\u4e3a\\u6d66\\u53d1\\u94f6\\u884c\\u9996\\u5bb6\\u5883\\u5916\\u5206\\u884c, \\u9999\\u6e2f\\u7b2c149\\u95f4\\u6301\\u724c\\u94f6\\u884c, \\u7b2c8\\u95f4\\u53ef\\u4e8e\\u9999\\u6e2f\\u4ece\\u4e8b\\u6240\\u6709\\u94f6\\u884c\\u4e1a\\u52a1\\u7684\\u4e2d\\u8d44\\u94f6\\u884c\\u6b63\\u5f0f\\u6210\\u7acb\\u3002 \\u4f5c\\u4e3a\\u4e00\\u5bb6\\u6765\\u81ea\\u4e0a\\u6d77\\u7684\\u65b0\\u4e2d\\u8d44\\u94f6\\u884c, \\u6d66\\u53d1\\u94f6\\u884c\\u9999\\u6e2f\\u5206\\u884c\\u7684\\u6210\\u7acb\\u6807\\u5fd7\\u7740\\u6caa\\u6e2f\\u91d1\\u878d\\u5408\\u4f5c\\u8fc8\\u51fa\\u4e86\\u5177\\u6709\\u5386\\u53f2\\u610f\\u4e49\\u7684\\u4e00\\u6b65, \\u540c\\u65f6, \\u4e5f\\u8fc8\\u51fa\\u6d66\\u53d1\\u94f6\\u884c\\u56fd\\u9645\\u5316\\u7ecf\\u8425\\u7684\\u5b9e\\u8d28\\u6027\\u6b65\\u4f10\\u3002\\u200b&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;img alt=&quot;&quot; src=&quot;http:\\/\\/static.xykol.com\\/media\\/k2\\/items\\/cache\\/af2ef6a0e2c9c528b09655df79f3b312_XL.jpg&quot; style=&quot;display:block; height:140px; width:100%&quot; \\/&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;ul&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;li&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempus adipiscing felis, sit amet blandit ipsum volutpat sed. Morbi porttitor, eget accumsan dictum, nisi libero ultricies ipsum, in posuere mauris neque at erat.&lt;\\/li&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/ul&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&amp;nbsp;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;img alt=&quot;&quot; src=&quot;http:\\/\\/static.xykol.com\\/media\\/k2\\/items\\/cache\\/af2ef6a0e2c9c528b09655df79f3b312_XL.jpg&quot; style=&quot;display:block; height:140px; width:100%&quot; \\/&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;ol&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;li&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempus adipiscing felis, sit amet blandit ipsum volutpat sed. Morbi porttitor, eget accumsan dictum, nisi libero ultricies ipsum, in posuere mauris neque at erat.&lt;\\/li&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/ol&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\u9999\\u6e2f\\u590f\\u60ab\\u905312\\u53f7\\u7f8e\\u56fd\\u94f6\\u884c\\u4e2d\\u5fc315\\u53ca24\\u697c&lt;br \\/&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u8054\\u7cfb\\u7535\\u8bdd: 852 2996 5600 &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;\\u5ba2\\u6237\\u670d\\u52a1\\u70ed\\u7ebf:852 216 95528&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;a href=&quot;http:\\/\\/www.spdb.com.cn\\/&quot;&gt;&lt;img alt=&quot;Twitter&quot; src=&quot;http:\\/\\/static.xykol.com\\/media\\/k2\\/items\\/cache\\/af2ef6a0e2c9c528b09655df79f3b312_XL.jpg&quot; style=&quot;display:block; height:38px; width:76px&quot; \\/&gt; &lt;\\/a&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t&lt;\\/tr&gt;\\r\\n\\t&lt;\\/tbody&gt;\\r\\n&lt;\\/table&gt;\\r\\n\"', '', ''),
(6, 'Interest rate information', 'Interest rate information[{name_title}]', '', '定期利率报价[{name_title}]', 'INTEREST', 8, 'Interest information email template', '\"&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t&lt;tbody&gt;\\r\\n\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t&lt;table align=&quot;center&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse; border-image:none; border:1px solid rgb(204, 204, 204); width:600px&quot;&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td style=&quot;text-align: center;&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;strong&gt;Interest rate information&lt;\\/strong&gt;&lt;\\/h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u3000Dear [{name}]:&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; Interest rate update date : [{rate_date}]&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; Interest rate update time : [{rate_time}]&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t[{rate}]&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u9999\\u6e2f\\u590f\\u60ab\\u905312\\u53f7\\u7f8e\\u56fd\\u94f6\\u884c\\u4e2d\\u5fc315\\u53ca24\\u697c&lt;br \\/&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u8054\\u7cfb\\u7535\\u8bdd: 852 2996 5600 &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;\\u5ba2\\u6237\\u670d\\u52a1\\u70ed\\u7ebf:852 216 95528&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t[{receiver}]&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t&lt;\\/tr&gt;\\r\\n\\t&lt;\\/tbody&gt;\\r\\n&lt;\\/table&gt;\\r\\n&lt;p&gt;\\r\\n\\t&amp;nbsp;&lt;\\/p&gt;\\r\\n\"', '', '\"&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t&lt;tbody&gt;\\r\\n\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t&lt;table align=&quot;center&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse; border-image:none; border:1px solid rgb(204, 204, 204); width:600px&quot;&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td style=&quot;text-align: center;&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u512a\\u60e0\\u5b58\\u6b3e\\u5229\\u7387\\u5831\\u50f9\\u53c3\\u8003 - UAT&lt;\\/h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;[{related_staffs}]:&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u65e9\\u4e0a\\u597d!&lt;br \\/&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u4ee5\\u4e0b\\u662f\\u4eca\\u5929\\u6211\\u884c\\u7684\\u512a\\u60e0\\u5b58\\u6b3e\\u5229\\u7387\\u4f9b\\u53c3\\u8003\\u3002\\u5982\\u53e6\\u9700\\u5916\\u532f\\u5831\\u50f9,\\u8acb\\u81f4\\u96fb\\u672c\\u884c\\u67e5\\u8a62\\u3002&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t[{rate}]&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u5982\\u6b32\\u67e5\\u8a62\\uff0c\\u8acb\\u81f4\\u96fb\\u5289\\u61ff\\u82ac\\u5973\\u58eb(29965653)(&lt;a href=&quot;mailto:susan_lau@spdb.com.cn&quot;&gt;susan_lau@spdb.com.cn&lt;\\/a&gt;)\\u6216\\u623f\\u6d77\\u831c\\u5973\\u58eb(29965656)(elky_fong@spdb.com.cn)&lt;br \\/&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t***\\u4ee5\\u4e0a\\u8cc7\\u6599\\u53ea\\u4f9b\\u53c3\\u8003\\uff0c\\u8a73\\u60c5\\u8acb\\u8207\\u672c\\u884c\\u806f\\u7d61***&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t[{receiver}]&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&amp;nbsp;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t&lt;\\/tr&gt;\\r\\n\\t&lt;\\/tbody&gt;\\r\\n&lt;\\/table&gt;\\r\\n&lt;p&gt;\\r\\n\\t&amp;nbsp;&lt;\\/p&gt;\\r\\n\"'),
(7, 'System error information', 'System error information', '', '', 'SYSERROR', 0, 'System error information', '\"&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t&lt;tbody&gt;\\r\\n\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t&lt;h1&gt;&lt;strong&gt;\\u7cfb\\u7edf\\u8fd0\\u884c\\u51fa\\u9519&lt;\\/strong&gt;&lt;\\/h1&gt;\\r\\n\\r\\n\\t\\t\\t&lt;p&gt;[{content}]\\u200b&lt;\\/p&gt;\\r\\n\\r\\n\\t\\t\\t&lt;p&gt;\\u9999\\u6e2f\\u590f\\u60ab\\u905312\\u53f7\\u7f8e\\u56fd\\u94f6\\u884c\\u4e2d\\u5fc315\\u53ca24\\u697c&lt;br \\/&gt;\\r\\n\\t\\t\\t\\u8054\\u7cfb\\u7535\\u8bdd: 852 2996 5600&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; \\u5ba2\\u6237\\u670d\\u52a1\\u70ed\\u7ebf:852 216 95528&lt;br \\/&gt;\\r\\n\\t\\t\\t&amp;nbsp;&lt;br \\/&gt;\\r\\n\\t\\t\\t&amp;nbsp;&lt;\\/p&gt;\\r\\n\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t&lt;\\/tr&gt;\\r\\n\\t&lt;\\/tbody&gt;\\r\\n&lt;\\/table&gt;\\r\\n\"', '', ''),
(8, 'Interest rate less zero', 'Interest rate less zero', '', '', 'INSTZERO', 0, 'Interest rate less zero', '\"&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t&lt;tbody&gt;\\r\\n\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t&lt;table align=&quot;center&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse; border:1px solid #cccccc; width:600px&quot;&gt;\\r\\n\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;img alt=&quot;&quot; src=&quot;http:\\/\\/p1.so.qhimg.com\\/t01d43fe817b042067b.jpg&quot; style=&quot;display:block; height:430px; width:600px&quot; \\/&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td style=&quot;text-align:center&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;h2&gt;&lt;strong&gt;Interest rate information&lt;\\/strong&gt;&lt;\\/h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\u3000Dear All:&lt;\\/p&gt;\\r\\n\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; Interest rate update date : [{rate_date}]&lt;\\/p&gt;\\r\\n\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; Interest rate update time : [{rate_time}]&lt;\\/p&gt;\\r\\n\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;&amp;nbsp;&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;[{content}]&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\u9999\\u6e2f\\u590f\\u60ab\\u905312\\u53f7\\u7f8e\\u56fd\\u94f6\\u884c\\u4e2d\\u5fc315\\u53ca24\\u697c&lt;br \\/&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u8054\\u7cfb\\u7535\\u8bdd: 852 2996 5600 &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;\\u5ba2\\u6237\\u670d\\u52a1\\u70ed\\u7ebf:852 216 95528&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;a href=&quot;http:\\/\\/www.spdb.com.cn\\/&quot;&gt;&lt;img alt=&quot;Twitter&quot; src=&quot;http:\\/\\/static.xykol.com\\/media\\/k2\\/items\\/cache\\/af2ef6a0e2c9c528b09655df79f3b312_XL.jpg&quot; style=&quot;display:block; height:38px; width:76px&quot; \\/&gt; &lt;\\/a&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t&lt;\\/tr&gt;\\r\\n\\t&lt;\\/tbody&gt;\\r\\n&lt;\\/table&gt;\\r\\n\"', '', ''),
(9, 'Interest rate spread warning', 'Interest rate spread warning', '', '', 'RATEWARN', 0, 'Interest rate spread warning', '\"&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t&lt;tbody&gt;\\r\\n\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t&lt;table align=&quot;center&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse; border:1px solid #cccccc; width:600px&quot;&gt;\\r\\n\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;img alt=&quot;&quot; src=&quot;http:\\/\\/p1.so.qhimg.com\\/t01d43fe817b042067b.jpg&quot; style=&quot;display:block; height:430px; width:600px&quot; \\/&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td style=&quot;text-align:center&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;h2&gt;&lt;strong&gt;Interest rate spread warning&lt;\\/strong&gt;&lt;\\/h2&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\u3000\\u3000[{content}]\\u200b&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&amp;nbsp;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;width:100%&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;p&gt;\\u9999\\u6e2f\\u590f\\u60ab\\u905312\\u53f7\\u7f8e\\u56fd\\u94f6\\u884c\\u4e2d\\u5fc315\\u53ca24\\u697c&lt;br \\/&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\u8054\\u7cfb\\u7535\\u8bdd: 852 2996 5600 &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;\\u5ba2\\u6237\\u670d\\u52a1\\u70ed\\u7ebf:852 216 95528&lt;\\/p&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot;&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;td&gt;&lt;a href=&quot;http:\\/\\/www.spdb.com.cn\\/&quot;&gt;&lt;img alt=&quot;Twitter&quot; src=&quot;http:\\/\\/static.xykol.com\\/media\\/k2\\/items\\/cache\\/af2ef6a0e2c9c528b09655df79f3b312_XL.jpg&quot; style=&quot;display:block; height:38px; width:76px&quot; \\/&gt; &lt;\\/a&gt;&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t\\t\\t\\t&lt;\\/tr&gt;\\r\\n\\t\\t\\t\\t&lt;\\/tbody&gt;\\r\\n\\t\\t\\t&lt;\\/table&gt;\\r\\n\\t\\t\\t&lt;\\/td&gt;\\r\\n\\t\\t&lt;\\/tr&gt;\\r\\n\\t&lt;\\/tbody&gt;\\r\\n&lt;\\/table&gt;\\r\\n\"', '', ''),
(10, 'testtest', '123', '', '', 'INTEREST', 0, '234234', '\"&lt;p&gt;123&lt;\\/p&gt;\\r\\n\"', '', ''),
(11, 'Interest rate file auto upload', 'Interest rate file upload', '', '', 'INTRATEUPL', 0, 'test', '\"&lt;p&gt;[{date}]&lt;\\/p&gt;\\r\\n\\r\\n&lt;p&gt;[{rate}]&lt;\\/p&gt;\\r\\n\\r\\n&lt;p&gt;&amp;nbsp;&lt;\\/p&gt;\\r\\n\\r\\n&lt;p&gt;[{message}]&lt;\\/p&gt;\\r\\n\"', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `uits_message`
--

CREATE TABLE `uits_message` (
  `id` int(10) NOT NULL COMMENT '消息ID',
  `group_id` int(10) NOT NULL COMMENT '会话ID',
  `send_emp` int(10) NOT NULL,
  `recv_emp` int(10) NOT NULL COMMENT '接收者',
  `create_time` int(10) NOT NULL COMMENT '发布时间',
  `status` tinyint(1) NOT NULL COMMENT '状态',
  `content` varchar(255) NOT NULL COMMENT '消息内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息列表';

-- --------------------------------------------------------

--
-- 表的结构 `uits_node`
--

CREATE TABLE `uits_node` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `title_en` varchar(50) DEFAULT NULL,
  `title_zh` varchar(50) NOT NULL COMMENT '中文标题',
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(3) UNSIGNED DEFAULT NULL,
  `pid` smallint(6) UNSIGNED NOT NULL,
  `catalog_item` tinyint(1) NOT NULL COMMENT '是否目录显示标志：1-目录项；0-不是目录项',
  `catalog_show` tinyint(1) NOT NULL COMMENT '目录定制项',
  `level` tinyint(1) UNSIGNED NOT NULL,
  `icon` varchar(30) DEFAULT NULL COMMENT '图标名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uits_node`
--

INSERT INTO `uits_node` (`id`, `name`, `title_en`, `title_zh`, `status`, `remark`, `sort`, `pid`, `catalog_item`, `catalog_show`, `level`, `icon`) VALUES
(1, 'admin', 'Management Application', '后台应用', 1, '后台应用', 0, 0, 1, 1, 1, 'fa-dashboard'),
(11, 'Emp', 'Access right', '组织架构', 1, '组织架构', 80, 1, 1, 1, 2, 'fa-users'),
(12, 'empList', 'User List', '员工列表', 1, '员工列表', 10, 11, 1, 1, 3, ''),
(13, 'empAdd', 'User Add', '员工添加', 1, '员工添加', 0, 11, 0, 0, 3, ''),
(14, 'empDelete', 'User Delete', '员工删除', 1, '员工删除', 0, 11, 0, 0, 3, ''),
(15, 'empUpdate', 'User Update', '员工更新', 1, '员工更新', 0, 11, 0, 0, 3, ''),
(16, 'empInquery', 'User Inquery', '员工查询', 1, '员工查询', 0, 11, 0, 0, 3, ''),
(17, 'empHold', 'Login lock', '禁止登录', 1, '禁止登录', 0, 11, 0, 0, 3, ''),
(18, 'empRelease', 'Login unlock', '解禁登录', 1, '解禁登录', 0, 11, 0, 0, 3, ''),
(41, 'roleList', 'Role List', '角色列表', 1, '角色列表', 50, 11, 1, 1, 3, ''),
(42, 'roleAdd', 'Role Add', '角色添加', 1, '角色添加', 0, 11, 0, 0, 3, ''),
(43, 'roleDelete', 'Role Delete', '角色删除', 1, '角色删除', 0, 11, 0, 0, 3, ''),
(44, 'roleUpdate', 'Role Update', '角色修改', 1, '角色修改', 0, 11, 0, 0, 3, ''),
(45, 'roleSort', 'Role Sort', '角色排序', 1, '角色排序', 0, 11, 0, 0, 3, ''),
(46, 'nodeList', 'Node List', '节点列表', 1, '节点列表', 40, 116, 1, 1, 3, ''),
(47, 'nodeAdd', 'Node Add', '节点添加', 1, '节点添加', 0, 116, 0, 0, 3, ''),
(48, 'nodeUpdate', 'Node Update', '节点更新', 1, '节点更新', 0, 116, 0, 0, 3, ''),
(49, 'nodeDelete', 'Node Delete', '节点删除', 1, '节点删除', 0, 116, 0, 0, 3, ''),
(50, 'nodeSort', 'Node Sort', '节点排序', 1, '节点排序', 0, 116, 0, 0, 3, ''),
(51, 'access', 'Access Configuration', '权限配置', 1, '权限配置', 0, 11, 0, 0, 3, ''),
(83, 'catalog', 'Custom Catalog', '目录定制', 1, '目录定制', 10, 116, 1, 1, 3, ''),
(86, 'account', 'Account', '个人账户', 1, '个人账户', 0, 1, 0, 0, 2, 'fa-user'),
(87, 'account', 'My Account', '我的账户', 1, '我的账户', 0, 86, 0, 0, 3, ''),
(88, 'update', 'Account Update', '账户更新', 1, '账户更新', 0, 86, 0, 0, 3, ''),
(93, 'deptList', 'Department List', '部门列表', 1, '部门列表', 70, 11, 1, 1, 3, ''),
(94, 'deptSort', 'Department Sort', '部门排序', 1, '部门排序', 0, 11, 0, 0, 3, ''),
(95, 'deptDelete', 'Department Delete', '部门删除', 1, '部门删除', 0, 11, 0, 0, 3, ''),
(96, 'deptUpdate', 'Department Update', '部门修改', 1, '部门修改', 0, 11, 0, 0, 3, ''),
(97, 'deptAdd', 'Department Add', '部门新增', 1, '部门新增', 0, 11, 0, 0, 3, ''),
(98, 'changePwd', 'Password Change', '修改密码', 1, '修改密码', 0, 86, 0, 0, 3, ''),
(99, 'Admin', 'Home', '首页', 1, '起始', 0, 1, 0, 0, 2, ''),
(100, 'home', 'Home', '首页', 1, '首页', 0, 99, 0, 0, 3, ''),
(101, 'catalogItem', 'Catalog Item Sign', '目录项更新', 1, '目录项更新', 0, 116, 0, 0, 3, ''),
(116, 'System', 'System', '系统配置', 1, '系统配置', 100, 1, 1, 1, 2, 'fa-gears'),
(117, 'configAdd', 'Configuration Add', '配置添加', 1, '配置添加', 0, 116, 0, 0, 3, ''),
(118, 'configList', 'Configuration List', '配置清单', 1, '配置清单', 30, 116, 1, 1, 3, ''),
(119, 'configDelete', 'Configuration Delete', '配置删除', 1, '配置删除', 0, 116, 0, 0, 3, ''),
(120, 'configUpdate', 'Configuration Update', '配置修改', 1, '配置修改', 0, 116, 0, 0, 3, ''),
(121, 'configSet', 'Configuration Set', '后台配置', 1, '后台配置', 20, 116, 1, 1, 3, ''),
(122, 'configSort', 'Configuration Sort', '配置排序', 1, '配置排序', 0, 116, 0, 0, 3, ''),
(204, 'empPwdReset', 'Password Reset', '密码重置', 1, '密码重置', 0, 11, 0, 0, 3, ''),
(260, 'Client', 'Client', '客户信息', 1, '客户信息', 70, 1, 1, 1, 2, 'fa-smile-o'),
(261, 'clientList', 'Client List', '客户信息列表', 1, '客户信息列表', 10, 260, 1, 1, 3, ''),
(262, 'clientAdd', 'Client Add', '客户信息添加', 1, '客户信息添加', 0, 260, 0, 0, 3, ''),
(263, 'clientUpdate', 'Client Update', '客户信息修改', 1, '客户信息修改', 0, 260, 0, 0, 3, ''),
(264, 'clientDelete', 'Client Delete', '客户信息删除', 1, '客户信息删除', 0, 260, 0, 0, 3, ''),
(265, 'clientInquery', 'Client Inquery', '客户信息查询', 1, '客户信息查询', 0, 260, 0, 0, 3, ''),
(287, 'Parameter', 'Parameter', '参数管理', 1, '参数管理', 90, 1, 1, 1, 2, 'fa-tags'),
(288, 'calendarList', 'Calendar View', '系统日历', 1, '系统日历', 10, 287, 1, 1, 3, 'fa-calendar'),
(289, 'calendarSign', 'Calendar Sign', '系统日历标记', 1, '系统日历标记', 0, 287, 0, 0, 3, ''),
(290, 'currencyList', 'Currency List', '货币参数列表', 1, '货币参数列表', 20, 287, 1, 1, 3, 'fa-dollar'),
(291, 'currencyAdd', 'Currency Add', '货币参数添加', 1, '货币参数添加', 0, 287, 0, 0, 3, ''),
(292, 'currencyUpdate', 'Currency Update', '货币参数修改', 1, '货币参数修改', 0, 287, 0, 0, 3, ''),
(293, 'currencyDelete', 'Currency Delete', '货币参数删除', 1, '货币参数删除', 0, 287, 0, 0, 3, ''),
(294, 'currencyInquery', 'Currency Inquery', '货币参数查询', 1, '货币参数查询', 0, 287, 0, 0, 3, ''),
(295, 'parameterSet', 'Parameter Set', '运行参数配置', 1, '运行参数配置', 30, 287, 1, 1, 3, 'fa-tags'),
(302, 'Mail', 'Mail', '邮件', 1, '邮件', 10, 1, 1, 1, 2, 'fa-envelope-o'),
(303, 'mailTester', 'Mail Tester', '邮件测试', 1, '邮件测试', 100, 302, 1, 1, 3, 'fa-share'),
(304, 'groupList', 'User Group List', '用户组列表', 1, '用户组列表', 30, 11, 1, 1, 3, 'fa-users'),
(305, 'groupAdd', 'User Group Add', '用户组添加', 1, '用户组添加', 0, 11, 0, 0, 3, ''),
(306, 'groupDelete', 'User Group Delete', '用户组删除', 1, '用户组删除', 0, 11, 0, 0, 3, ''),
(307, 'groupUpdate', 'User Group Update', '用户组更新', 1, '用户组更新', 0, 11, 0, 0, 3, ''),
(308, 'groupSort', 'User Group Sort', '用户组排序', 1, '用户组排序', 0, 11, 0, 0, 3, ''),
(309, 'rateList', 'Interest Rate Info.', '利率信息', 1, '利率列表', 90, 260, 1, 1, 3, 'fa-bar-chart-o'),
(310, 'rateDownload', 'Interest Rate Download', '利率下载', 1, '利率下载', 0, 260, 0, 0, 3, 'fa-download'),
(311, 'rateUpload', 'Interest Rate Upload', '利率上传', 1, '利率上传', 100, 260, 1, 1, 3, ''),
(312, 'rateAdd', 'Interest Rate Add', '利率添加', 1, '利率添加', 0, 260, 0, 0, 3, ''),
(313, 'rateUpdate', 'Interest Rate Update', '利率修改', 1, '利率修改', 0, 260, 0, 0, 3, ''),
(314, 'rateDelete', 'Interest Rate Delete', '利率删除', 1, '利率删除', 0, 260, 0, 0, 3, ''),
(315, 'rateFloatUpdate', 'Interest Rate Float Update', '客户优惠利率维护', 1, '客户优惠利率维护', 0, 260, 0, 0, 3, ''),
(316, 'mailTplList', 'Mail Template List', '邮件模板列表', 1, '邮件模板列表', 10, 302, 1, 1, 3, ''),
(317, 'mailTplAdd', 'Mail Template Add', '邮件模板添加', 1, '邮件模板添加', 0, 302, 0, 0, 3, ''),
(318, 'mailTplUpdate', 'Mail Template Update', '邮件模板修改', 1, '邮件模板修改', 0, 302, 0, 0, 3, ''),
(319, 'mailTplDelete', 'Mail Template Delete', '邮件模板删除', 1, '邮件模板删除', 0, 302, 0, 0, 3, ''),
(320, 'Database', 'Database', '数据备份', 1, '数据备份', 95, 1, 1, 1, 2, 'fa-clipboard'),
(321, 'export', 'Export', '备份数据库', 1, '备份数据库', 10, 320, 1, 1, 3, ''),
(322, 'import', 'Import', '还原数据库', 1, '还原数据库', 20, 320, 1, 1, 3, ''),
(323, 'optimize', 'Optimize', '优化表', 1, '优化表', 0, 320, 0, 0, 3, ''),
(324, 'repair', 'Repair', '修复表', 1, '修复表', 0, 320, 0, 0, 3, ''),
(325, 'delete', 'Delete', '删除备份文件', 1, '删除备份文件', 0, 320, 0, 0, 3, ''),
(326, 'currencySort', 'Currency Sort', '货币排序', 1, '货币排序', 0, 287, 0, 0, 3, ''),
(327, 'clientAppoveList', 'Client Appove List', '待复核队列', 1, '待复核队列', 30, 260, 1, 1, 3, 'fa-check-square-o'),
(328, 'clientAppove', 'Client Appove', '客户信息复核', 1, '客户信息复核', 0, 260, 0, 0, 3, ''),
(329, 'clientReject', 'Client Reject', '客户信息拒绝', 1, '客户信息拒绝', 0, 260, 0, 0, 3, ''),
(330, 'autoTaskList', 'Auto Task List', '自动任务列表', 1, '自动任务列表', 100, 287, 1, 1, 3, ''),
(331, 'autoTaskConfig', 'Auto Task Config', '自动任务配置', 1, '自动任务配置', 100, 287, 0, 0, 3, ''),
(332, 'calendarUpload', 'Calendar Upload', '系统日历上传', 1, '系统日历上传', 100, 287, 0, 0, 3, ''),
(333, 'maillogList', 'Mail Log List', '邮件记录列表', 1, '邮件记录列表', 100, 302, 1, 1, 3, 'fa-reply-all'),
(334, 'maillogInquery', 'Mail Log Inquery', '邮件记录查询', 1, '邮件记录查询', 100, 302, 0, 0, 3, ''),
(335, 'maillogFileInq', 'Mail Log File Inquery', '邮件文件查看', 1, '邮件文件查看', 100, 302, 0, 0, 3, ''),
(336, 'rateBatchDownload', 'Interest Rate Download', '利率批量下载', 1, '利率批量下载', 100, 260, 1, 1, 3, 'fa-download'),
(337, 'rateMailSend', 'Interest Rate Mail Send', '客户利率邮件发送', 1, '客户利率邮件发送', 100, 260, 0, 0, 3, ''),
(338, 'empAppoveList', 'User Appove List', '用户信息复核列表', 1, '用户信息复核列表', 20, 11, 1, 1, 3, 'fa-check-square-o'),
(339, 'empAppove', 'User Maintenance Appove', '用户信息复核', 1, '用户信息复核', 0, 11, 0, 0, 3, ''),
(340, 'empReject', 'User Reject', '用户信息拒绝', 1, '用户信息拒绝', 0, 11, 0, 0, 3, ''),
(341, 'rateMailSentCiList', 'Mail Sent Cient List', '已发邮件客户列表', 1, '利率邮件重发', 20, 260, 1, 1, 3, 'fa-share-square-o'),
(342, 'rateMailResend', 'Rate Mail Resend', '利率邮件重发', 1, '利率邮件重发', 100, 260, 0, 0, 3, ''),
(343, 'accessAppoveList', 'Access Appove List', '权限复核列表', 1, '权限复核列表', 60, 11, 1, 1, 3, 'fa-shield'),
(344, 'accessAppove', 'Access Appove', '角色信息复核', 1, '角色信息复核', 0, 11, 0, 0, 3, ''),
(345, 'accessReject', 'Access Reject', '角色信息拒绝', 1, '角色信息拒绝', 0, 11, 0, 0, 3, ''),
(346, 'groupAppoveList', 'User Group Appove List', '用户组复核队列', 1, '用户组复核队列', 40, 11, 1, 1, 3, 'fa-users'),
(347, 'groupAppove', 'User Group Appove', '用户组信息复核', 1, '用户组信息复核', 0, 11, 0, 0, 3, ''),
(348, 'groupReject', 'User Group Appove Reject', '用户组信息拒绝', 1, '用户组信息拒绝', 0, 11, 0, 0, 3, ''),
(349, 'refuseList', 'Refuse Email ', '拒收邮件列表', 1, '拒收邮件列表', 100, 260, 1, 1, 3, 'fa-unlink'),
(350, 'refuseAdd', 'Refuse Email Add', '拒收邮件地址添加', 1, '拒收邮件地址添加', 100, 260, 0, 0, 3, ''),
(351, 'refuseDelete', 'Refuse Email Delete', '拒收邮件地址删除', 1, '拒收邮件地址删除', 100, 260, 0, 0, 3, ''),
(352, 'refuseListaaa', 'Refuse Email ', '拒收邮件列表', 1, '拒收邮件列表', 100, 260, 1, 1, 3, 'fa-unlink');

-- --------------------------------------------------------

--
-- 表的结构 `uits_rate`
--

CREATE TABLE `uits_rate` (
  `date` int(10) NOT NULL COMMENT '上传日期',
  `seq` int(10) NOT NULL COMMENT '记录顺序号',
  `tenor` varchar(32) NOT NULL,
  `ccy` varchar(32) NOT NULL COMMENT '货币',
  `rate` decimal(11,8) NOT NULL COMMENT '利率',
  `update_emp` int(10) NOT NULL COMMENT '上传人',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `time` int(10) NOT NULL COMMENT '上传时间',
  `error_msg` varchar(255) DEFAULT NULL COMMENT '错误信息'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='利率信息表';

--
-- 转存表中的数据 `uits_rate`
--

INSERT INTO `uits_rate` (`date`, `seq`, `tenor`, `ccy`, `rate`, `update_emp`, `remark`, `time`, `error_msg`) VALUES
(1471968000, 1, 'Saving', 'HKD', '3.30000000', 16, '', 1472032821, ''),
(1472486400, 1, 'Saving', 'HKD', '1.00000000', 1, '', 1472544210, ''),
(1472745600, 1, 'Saving', 'HKD', '12.00000000', 1, '', 1474353882, ''),
(1473350400, 1, 'Saving', 'HKD', '3.10000000', 1, '', 1473387863, ''),
(1473350400, 2, 'Saving', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 3, 'Saving', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 4, 'Saving', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 5, 'Saving', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 6, 'Saving', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 7, 'Saving', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 8, 'Saving', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 9, 'Saving', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 10, 'Saving', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 11, 'Saving', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 12, 'Saving', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 13, 'Saving', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 14, 'Saving', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 15, 'Saving', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 16, 'Saving', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 17, 'Saving', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 18, 'Saving', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 19, 'Saving', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 20, 'Saving', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 21, 'Saving', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 22, 'Saving', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 23, 'O/N', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 24, 'O/N', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 25, 'O/N', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 26, 'O/N', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 27, 'O/N', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 28, 'O/N', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 29, 'O/N', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 30, 'O/N', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 31, 'O/N', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 32, 'O/N', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 33, 'O/N', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 34, 'O/N', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 35, 'O/N', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 36, 'O/N', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 37, 'O/N', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 38, 'O/N', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 39, 'O/N', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 40, 'O/N', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 41, 'O/N', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 42, 'O/N', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 43, 'O/N', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 44, 'O/N', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 45, '1W', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 46, '1W', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 47, '1W', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 48, '1W', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 49, '1W', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 50, '1W', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 51, '1W', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 52, '1W', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 53, '1W', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 54, '1W', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 55, '1W', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 56, '1W', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 57, '1W', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 58, '1W', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 59, '1W', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 60, '1W', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 61, '1W', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 62, '1W', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 63, '1W', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 64, '1W', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 65, '1W', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 66, '1W', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 67, '2W', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 68, '2W', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 69, '2W', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 70, '2W', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 71, '2W', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 72, '2W', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 73, '2W', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 74, '2W', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 75, '2W', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 76, '2W', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 77, '2W', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 78, '2W', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 79, '2W', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 80, '2W', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 81, '2W', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 82, '2W', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 83, '2W', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 84, '2W', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 85, '2W', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 86, '2W', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 87, '2W', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 88, '2W', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 89, '1M', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 90, '1M', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 91, '1M', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 92, '1M', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 93, '1M', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 94, '1M', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 95, '1M', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 96, '1M', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 97, '1M', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 98, '1M', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 99, '1M', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 100, '1M', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 101, '1M', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 102, '1M', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 103, '1M', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 104, '1M', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 105, '1M', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 106, '1M', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 107, '1M', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 108, '1M', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 109, '1M', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 110, '1M', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 111, '2M', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 112, '2M', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 113, '2M', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 114, '2M', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 115, '2M', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 116, '2M', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 117, '2M', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 118, '2M', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 119, '2M', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 120, '2M', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 121, '2M', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 122, '2M', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 123, '2M', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 124, '2M', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 125, '2M', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 126, '2M', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 127, '2M', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 128, '2M', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 129, '2M', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 130, '2M', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 131, '2M', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 132, '2M', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 133, '3M', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 134, '3M', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 135, '3M', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 136, '3M', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 137, '3M', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 138, '3M', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 139, '3M', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 140, '3M', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 141, '3M', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 142, '3M', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 143, '3M', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 144, '3M', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 145, '3M', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 146, '3M', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 147, '3M', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 148, '3M', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 149, '3M', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 150, '3M', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 151, '3M', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 152, '3M', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 153, '3M', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 154, '3M', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 155, '6M', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 156, '6M', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 157, '6M', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 158, '6M', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 159, '6M', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 160, '6M', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 161, '6M', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 162, '6M', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 163, '6M', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 164, '6M', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 165, '6M', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 166, '6M', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 167, '6M', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 168, '6M', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 169, '6M', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 170, '6M', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 171, '6M', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 172, '6M', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 173, '6M', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 174, '6M', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 175, '6M', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 176, '6M', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 177, '9M', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 178, '9M', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 179, '9M', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 180, '9M', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 181, '9M', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 182, '9M', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 183, '9M', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 184, '9M', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 185, '9M', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 186, '9M', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 187, '9M', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 188, '9M', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 189, '9M', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 190, '9M', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 191, '9M', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 192, '9M', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 193, '9M', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 194, '9M', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 195, '9M', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 196, '9M', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 197, '9M', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 198, '9M', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 199, '12M', 'HKD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 200, '12M', 'USD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 201, '12M', 'CNY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 202, '12M', 'KRW', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 203, '12M', 'NZD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 204, '12M', 'RUB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 205, '12M', 'AUD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 206, '12M', 'SEK', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 207, '12M', 'SGD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 208, '12M', 'THB', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 209, '12M', 'TWD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 210, '12M', 'JPY', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 211, '12M', 'INR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 212, '12M', 'BRL', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 213, '12M', 'CAD', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 214, '12M', 'CHF', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 215, '12M', 'CNH', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 216, '12M', 'CNN', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 217, '12M', 'EUR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 218, '12M', 'GBP', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 219, '12M', 'IDR', '0.00000000', 1, '', 1473387863, ''),
(1473350400, 220, '12M', 'ZAR', '0.00000000', 1, '', 1473387863, ''),
(1474560000, 1, 'Saving', 'HKD', '1.10000000', 0, '', 1474617166, ''),
(1474560000, 2, 'Saving', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 3, 'Saving', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 4, 'Saving', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 5, 'Saving', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 6, 'Saving', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 7, 'Saving', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 8, 'Saving', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 9, 'Saving', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 10, 'Saving', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 11, 'Saving', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 12, 'Saving', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 13, 'Saving', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 14, 'Saving', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 15, 'Saving', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 16, 'Saving', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 17, 'Saving', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 18, 'Saving', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 19, 'Saving', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 20, 'Saving', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 21, 'Saving', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 22, 'Saving', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 23, 'O/N', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 24, 'O/N', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 25, 'O/N', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 26, 'O/N', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 27, 'O/N', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 28, 'O/N', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 29, 'O/N', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 30, 'O/N', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 31, 'O/N', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 32, 'O/N', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 33, 'O/N', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 34, 'O/N', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 35, 'O/N', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 36, 'O/N', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 37, 'O/N', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 38, 'O/N', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 39, 'O/N', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 40, 'O/N', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 41, 'O/N', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 42, 'O/N', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 43, 'O/N', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 44, 'O/N', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 45, '1W', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 46, '1W', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 47, '1W', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 48, '1W', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 49, '1W', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 50, '1W', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 51, '1W', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 52, '1W', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 53, '1W', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 54, '1W', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 55, '1W', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 56, '1W', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 57, '1W', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 58, '1W', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 59, '1W', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 60, '1W', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 61, '1W', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 62, '1W', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 63, '1W', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 64, '1W', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 65, '1W', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 66, '1W', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 67, '2W', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 68, '2W', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 69, '2W', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 70, '2W', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 71, '2W', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 72, '2W', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 73, '2W', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 74, '2W', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 75, '2W', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 76, '2W', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 77, '2W', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 78, '2W', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 79, '2W', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 80, '2W', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 81, '2W', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 82, '2W', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 83, '2W', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 84, '2W', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 85, '2W', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 86, '2W', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 87, '2W', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 88, '2W', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 89, '1M', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 90, '1M', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 91, '1M', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 92, '1M', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 93, '1M', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 94, '1M', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 95, '1M', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 96, '1M', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 97, '1M', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 98, '1M', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 99, '1M', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 100, '1M', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 101, '1M', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 102, '1M', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 103, '1M', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 104, '1M', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 105, '1M', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 106, '1M', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 107, '1M', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 108, '1M', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 109, '1M', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 110, '1M', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 111, '2M', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 112, '2M', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 113, '2M', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 114, '2M', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 115, '2M', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 116, '2M', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 117, '2M', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 118, '2M', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 119, '2M', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 120, '2M', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 121, '2M', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 122, '2M', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 123, '2M', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 124, '2M', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 125, '2M', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 126, '2M', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 127, '2M', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 128, '2M', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 129, '2M', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 130, '2M', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 131, '2M', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 132, '2M', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 133, '3M', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 134, '3M', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 135, '3M', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 136, '3M', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 137, '3M', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 138, '3M', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 139, '3M', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 140, '3M', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 141, '3M', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 142, '3M', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 143, '3M', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 144, '3M', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 145, '3M', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 146, '3M', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 147, '3M', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 148, '3M', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 149, '3M', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 150, '3M', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 151, '3M', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 152, '3M', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 153, '3M', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 154, '3M', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 155, '6M', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 156, '6M', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 157, '6M', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 158, '6M', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 159, '6M', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 160, '6M', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 161, '6M', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 162, '6M', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 163, '6M', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 164, '6M', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 165, '6M', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 166, '6M', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 167, '6M', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 168, '6M', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 169, '6M', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 170, '6M', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 171, '6M', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 172, '6M', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 173, '6M', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 174, '6M', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 175, '6M', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 176, '6M', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 177, '9M', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 178, '9M', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 179, '9M', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 180, '9M', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 181, '9M', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 182, '9M', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 183, '9M', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 184, '9M', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 185, '9M', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 186, '9M', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 187, '9M', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 188, '9M', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 189, '9M', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 190, '9M', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 191, '9M', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 192, '9M', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 193, '9M', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 194, '9M', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 195, '9M', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 196, '9M', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 197, '9M', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 198, '9M', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 199, '12M', 'HKD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 200, '12M', 'USD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 201, '12M', 'CNY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 202, '12M', 'KRW', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 203, '12M', 'NZD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 204, '12M', 'RUB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 205, '12M', 'AUD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 206, '12M', 'SEK', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 207, '12M', 'SGD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 208, '12M', 'THB', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 209, '12M', 'TWD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 210, '12M', 'JPY', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 211, '12M', 'INR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 212, '12M', 'BRL', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 213, '12M', 'CAD', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 214, '12M', 'CHF', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 215, '12M', 'CNH', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 216, '12M', 'CNN', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 217, '12M', 'EUR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 218, '12M', 'GBP', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 219, '12M', 'IDR', '0.00000000', 0, '', 1474617166, ''),
(1474560000, 220, '12M', 'ZAR', '0.00000000', 0, '', 1474617166, ''),
(1476201600, 1, 'Saving', 'HKD', '0.10000000', 1, '', 1476269324, ''),
(1476374400, 1, 'Saving', 'HKD', '1.10000000', 1, '', 1476432081, ''),
(1478448000, 1, 'Saving', 'HKD', '1.10000000', 16, '', 1478511995, ''),
(1481040000, 1, 'Saving', 'HKD', '4.40000000', 1, '', 1481075782, ''),
(1481040000, 2, 'Saving', 'USD', '3.00000000', 1, '', 1481075788, '');

-- --------------------------------------------------------

--
-- 表的结构 `uits_role`
--

CREATE TABLE `uits_role` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT NULL,
  `sort` tinyint(3) NOT NULL COMMENT '排序',
  `remark` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uits_role`
--

INSERT INTO `uits_role` (`id`, `name`, `pid`, `status`, `sort`, `remark`) VALUES
(1, 'Administrator', 0, 1, 10, 'System Administrator'),
(3, 'CBD - CSO', 0, 1, 100, 'CBD - CSO'),
(4, 'GMT', 0, 1, 100, 'GMT'),
(5, 'FID - CSO', 0, 1, 100, 'FID - CSO');

-- --------------------------------------------------------

--
-- 表的结构 `uits_role_usergroup`
--

CREATE TABLE `uits_role_usergroup` (
  `role_id` mediumint(9) UNSIGNED NOT NULL DEFAULT '0',
  `usergroup_id` mediumint(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `uits_role_usergroup`
--

INSERT INTO `uits_role_usergroup` (`role_id`, `usergroup_id`) VALUES
(1, 1),
(3, 6),
(3, 8),
(4, 9),
(5, 7);

-- --------------------------------------------------------

--
-- 表的结构 `uits_usergroup`
--

CREATE TABLE `uits_usergroup` (
  `id` mediumint(9) NOT NULL COMMENT '用户组ID',
  `pid` smallint(9) NOT NULL,
  `name` varchar(120) NOT NULL COMMENT '用户组名称',
  `sort` tinyint(3) NOT NULL COMMENT '排序',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组表';

--
-- 转存表中的数据 `uits_usergroup`
--

INSERT INTO `uits_usergroup` (`id`, `pid`, `name`, `sort`, `remark`) VALUES
(1, 0, 'Bank', 10, 'Bank level test'),
(6, 1, 'CBD Department Level', 100, 'CBD Department Level'),
(7, 1, 'FID Department Level', 100, 'FID Department Level'),
(8, 6, 'CBD Group 1', 100, 'CBD Group 1'),
(9, 1, 'GMT Department Level', 100, 'GMT Department Level');

-- --------------------------------------------------------

--
-- 表的结构 `uits_usergroup_user`
--

CREATE TABLE `uits_usergroup_user` (
  `usergroup_id` mediumint(9) NOT NULL COMMENT '用户组ID',
  `user_id` varchar(32) NOT NULL COMMENT '用户ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户分组表';

--
-- 转存表中的数据 `uits_usergroup_user`
--

INSERT INTO `uits_usergroup_user` (`usergroup_id`, `user_id`) VALUES
(1, '1'),
(1, '11'),
(1, '16'),
(1, '17'),
(1, '3'),
(1, '5'),
(6, '13'),
(7, '14'),
(9, '18'),
(9, '6'),
(9, '7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uits_access`
--
ALTER TABLE `uits_access`
  ADD KEY `groupId` (`role_id`),
  ADD KEY `nodeId` (`node_id`);

--
-- Indexes for table `uits_appove`
--
ALTER TABLE `uits_appove`
  ADD PRIMARY KEY (`date`,`seq`),
  ADD KEY `reference` (`reference`),
  ADD KEY `maker` (`maker`),
  ADD KEY `maker_2` (`maker`);

--
-- Indexes for table `uits_autotask`
--
ALTER TABLE `uits_autotask`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `uits_calendar`
--
ALTER TABLE `uits_calendar`
  ADD PRIMARY KEY (`date`);

--
-- Indexes for table `uits_client`
--
ALTER TABLE `uits_client`
  ADD PRIMARY KEY (`ci_no`);

--
-- Indexes for table `uits_config`
--
ALTER TABLE `uits_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `uits_currency`
--
ALTER TABLE `uits_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uits_dept`
--
ALTER TABLE `uits_dept`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uits_emp`
--
ALTER TABLE `uits_emp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `nikename` (`nickname`);

--
-- Indexes for table `uits_log`
--
ALTER TABLE `uits_log`
  ADD PRIMARY KEY (`empid`,`time`);

--
-- Indexes for table `uits_maillog`
--
ALTER TABLE `uits_maillog`
  ADD PRIMARY KEY (`date`,`seq`);

--
-- Indexes for table `uits_mailrefuse`
--
ALTER TABLE `uits_mailrefuse`
  ADD PRIMARY KEY (`mail`);

--
-- Indexes for table `uits_mailtpl`
--
ALTER TABLE `uits_mailtpl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uits_message`
--
ALTER TABLE `uits_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uits_node`
--
ALTER TABLE `uits_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level` (`level`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `uits_rate`
--
ALTER TABLE `uits_rate`
  ADD PRIMARY KEY (`date`,`seq`);

--
-- Indexes for table `uits_role`
--
ALTER TABLE `uits_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `uits_role_usergroup`
--
ALTER TABLE `uits_role_usergroup`
  ADD PRIMARY KEY (`role_id`,`usergroup_id`),
  ADD KEY `group_id` (`role_id`),
  ADD KEY `user_id` (`usergroup_id`);

--
-- Indexes for table `uits_usergroup`
--
ALTER TABLE `uits_usergroup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `uits_usergroup_user`
--
ALTER TABLE `uits_usergroup_user`
  ADD PRIMARY KEY (`usergroup_id`,`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `uits_client`
--
ALTER TABLE `uits_client`
  MODIFY `ci_no` int(10) NOT NULL AUTO_INCREMENT COMMENT '客户编号', AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `uits_config`
--
ALTER TABLE `uits_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID', AUTO_INCREMENT=44;
--
-- 使用表AUTO_INCREMENT `uits_dept`
--
ALTER TABLE `uits_dept`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '部门ID', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `uits_emp`
--
ALTER TABLE `uits_emp`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '员工ID', AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `uits_mailtpl`
--
ALTER TABLE `uits_mailtpl`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'tpl id', AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `uits_message`
--
ALTER TABLE `uits_message`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '消息ID';
--
-- 使用表AUTO_INCREMENT `uits_node`
--
ALTER TABLE `uits_node`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;
--
-- 使用表AUTO_INCREMENT `uits_role`
--
ALTER TABLE `uits_role`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `uits_usergroup`
--
ALTER TABLE `uits_usergroup`
  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT COMMENT '用户组ID', AUTO_INCREMENT=10;
