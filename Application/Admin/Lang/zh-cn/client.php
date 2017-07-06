<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * ThinkPHP English language package
 */
return array(
    /* application language package */

    'TEMP_CLIENT_BASE_INF'              => "基本信息",
    'TEMP_CLIENT_ADDR_INF'              => "地址信息",
    'TEMP_CLIENT_NO'                    => "客户编号",
    'TEMP_CLIENT_TYPE'                  => "客户类型",
    'TEMP_CLIENT_ID_TYPE'               => "证件类型",
    'TEMP_CLIENT_ID_CODE'               => "证件号码",
    'TEMP_CLIENT_NAME'                  => "公司名称",
    'TEMP_CLIENT_OT_NAME'               => "相关负责人名称",
    'TEMP_CLIENT_REMARK'                => "备注",
    'TEMP_CLIENT_AUTO_EMAIL'            => "是否自动发利率邮件",
    'TEMP_CLIENT_AUTO_EX_EMAIL'         => "是否自动发汇率邮件",
    'TEMP_CLIENT_AUTO_PHONE'            => "是否自动发短信",
    'TEMP_CLIENT_TPL_PRIORITY_LANG'     => "模板语言优先级",
    'TEMP_CLIENT_EMAIL'                 => "客户邮箱",
    'TEMP_CLIENT_BCC_EMAIL'             => "暗送邮箱",
    'TEMP_CLIENT_CUSTOMER_GROUP'        => "客户组",
    'TEMP_CLIENT_CUSTOMER_EDUCATION'    => "学历",
    'TEMP_CLIENT_CUSTOMER_AREA'         => "所在区域",
    'TEMP_CLIENT_CUSTOMER_CREDIT_RATE'  => "信用等级",
    'TEMP_CLIENT_PHONE'                 => "电话",

    'TEMP_CLIENT_RATE_TENOR'            => "期限",
    'TEMP_CLIENT_EXRATE_TARATE'         => "货币",
    'TEMP_CLIENT_EXCHANGE_CCY'          => "兑换货币",
    'TEMP_CLIENT_TARGET_CCY'            => "目标货币",
    'TEMP_CLIENT_RATE_DATE'             => "日期",
    'TEMP_CLIENT_RATE'                  => "利率",
    'TEMP_CLIENT_EXRATE'                => "汇率",
    'TEMP_CLIENT_RATE_SPREAD'           => "利率差",
    'TEMP_CLIENT_EXRATE_SPREAD'         => "汇率差",
    'TEMP_CLIENT_GEN_AFTER_APPOVE'      => "Generated after appove ", //这个是写入数据库的,最好都用英文
    'TEMP_CLIENT_EX_RATE_EMAILTPL'      => "汇率邮件模板",
    'TEMP_CLIENT_MARKET_EMAILTPL'       => "市场资讯模板",
    'TEMP_CLIENT_EMAIL_TYPE'            => "邮件类型",
    'TEMP_CLIENT_EMAIL_SEPARATED'       => "多个邮件地址以';'分隔",
    'TEMP_CLIENT_INTEREST_RATE_EMAILTPL' => "利率邮件模板",
    'TEMP_CLIENT_START_DATE'            => "起始日期",
    'TEMP_CLIENT_END_DATE'              => "截止日期",
    'TEMP_CLIENT_MAILS_SEND_TIME'       => "邮件自动发送时间",
    'TEMP_CLIENT_MAIL_SEND_TIME'        => "利率邮件自动发送时间",
    'TEMP_CLIENT_EX_MAIL_SEND_TIME'     => "汇率邮件自动发送时间",
    'TEMP_CLIENT_NEGATIVE_RATE_ALLOW'   => "是否允许负利率",

    'TEMP_CLIENT_MARKET_TITLE'          => "标题",
    'TEMP_CLIENT_MARKET_TIME'           => "时间",
    'TEMP_CLIENT_MARKET_INPUT_USER'     => "录入人",
    'TEMP_CLIENT_MARKET_ACTION'         => "操作",
    'TEMP_CLIENT_MARKET_EN_TITLE'       => "标题（英文）",
    'TEMP_CLIENT_MARKET_CN_TITLE'       => "标题（简体中文）",
    'TEMP_CLIENT_MARKET_HK_TITLE'       => "标题（繁体中文）",
    'TEMP_CLIENT_MARKET_EN_CONTENT'     => "内容（英文）",
    'TEMP_CLIENT_MARKET_CN_CONTENT'     => "内容（简体中文）",
    'TEMP_CLIENT_MARKET_HK_CONTENT'     => "内容（繁体中文）",

    'CLIENT_ERROR_MARKET_EXIST'         => "市场资讯记录已存在",
    'CLIENT_ERROR_CLIENT_EXIST'         => "客户记录已经存在",
    'CLIENT_ERROR_CLIENT_NOTEXIST'      => "客户记录不存在",
    'CLIENT_ERROR_ADDR_ASS_AC'          => "该地址已经关联账户",
    'CLIENT_ERROR_ADDR_INP_SEQ'         => "请顺序输入地址",
    'CLIENT_ERROR_ADDR_INP_ERR'         => "地址输入有误",
    'CLIENT_ERROR_ACCOUNT_NOTEXIST'     => "账户记录不存在",

    'CLIENT_ERROR_INTEREST_NOT_INPUT'   => "利率信息没有输入",
    'CLIENT_ERROR_EXRATE_NOT_INPUT'     => "汇率信息没有输入",
    'CLIENT_ERROR_EXRATE_NOT_REPEAT'    => "同种货币不需维护",
    'CLIENT_ERROR_INTEREST_FORMAT_ERR'  => "利率格式错误",
    'CLIENT_ERROR_EXRATE_FORMAT_ERR'    => "汇率格式错误",
    'CLIENT_ERROR_INTEREST_NOT_UPD'     => "您没有修改相应利率信息",
    'CLIENT_ERROR_EXRATE_NOT_UPD'       => "您没有修改相应汇率信息",
    'CLIENT_ERROR_SPREAD_TOO_MUCH'      => "与前次利率值相差过大",
    'CLIENT_ERROR_EX_SPREAD_TOO_MUCH'   => "与前次汇率值相差过大",
    'CLIENT_ERROR_END_LESS_START'       => "截止日期小于开始日",
    'CLIENT_ERROR_INST_RATE_NOT_UP'     => "利率未上传",
    'CLIENT_ERROR_EX_RATE_NOT_UP'       => "汇率未上传",
    'CLIENT_EMAIL_NOT_BELONG_CLIENT'    => "Email地址不归属于当前客户",

    //===================释义、状态释义======================//
    'CLIENT_AUTO_SEND_FLAG_TEXT'        => array('0' => '禁用','1' => '启用'),
    'CLIENT_NEGATIVE_RATE_ALLOW_TEXT'   => array('0' => '禁用','1' => '启用'),
    'CLIENT_TPL_LANGUAGE'               => array('en' => '英文','zh_s' => '中文(简体)','zh_t'=>'中文(繁体)'),
    'CLIENT_TPL_PRIORITY_TEXT'          => array(
                                        'en-zh_s-zh_t' => '英文 - 中文(简体) - 中文(繁体)',
                                        'en-zh_t-zh_s' => '英文 - 中文(繁体) - 中文(简体)',
                                        'zh_s-en-zh_t' => '中文(简体) - 英文 - 中文(繁体)',
                                        'zh_s-zh_t-en' => '中文(简体) - 中文(繁体) - 英文',
                                        'zh_t-en-zh_s' => '中文(繁体) - 英文 - 中文(简体)',
                                        'zh_t-zh_s-en' => '中文(繁体) - 中文(简体) - 英文'),
);
