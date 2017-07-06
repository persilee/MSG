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

    'TEMP_CLIENT_BASE_INF'              => "Base info. ",
    'TEMP_CLIENT_ADDR_INF'              => "Address info. ",
    'TEMP_CLIENT_NO'                    => "Client No. ",
    'TEMP_CLIENT_TYPE'                  => "Client type ",
    'TEMP_CLIENT_ID_TYPE'               => "Certificate type ",
    'TEMP_CLIENT_ID_CODE'               => "Certificate code ",
    'TEMP_CLIENT_NAME'                  => "Company name ",
    'TEMP_CLIENT_OT_NAME'               => "Related staffs name ",
    'TEMP_CLIENT_REMARK'                => "Remark ",
    'TEMP_CLIENT_AUTO_EMAIL'            => "Auto interest rate email ",
    'TEMP_CLIENT_AUTO_EX_EMAIL'         => "Auto exchange rate email ",
    'TEMP_CLIENT_AUTO_PHONE'            => "Auto SMS ",
    'TEMP_CLIENT_TPL_PRIORITY_LANG'     => "Priority language ",
    'TEMP_CLIENT_EMAIL'                 => "Client email ",
    'TEMP_CLIENT_BCC_EMAIL'             => "Bcc email ",
    'TEMP_CLIENT_CUSTOMER_GROUP'        => "Group ",
    'TEMP_CLIENT_CUSTOMER_EDUCATION'    => "Education ",
    'TEMP_CLIENT_CUSTOMER_AREA'         => "Area ",
    'TEMP_CLIENT_CUSTOMER_CREDIT_RATE'  => "Credit rate ",
    'TEMP_CLIENT_PHONE'                 => "Phone ",

    'TEMP_CLIENT_RATE_TENOR'            => "Tenor ",
    'TEMP_CLIENT_EXRATE_TARATE'         => "Currency ",
    'TEMP_CLIENT_EXCHANGE_CCY'          => "Exchange currency",
    'TEMP_CLIENT_TARGET_CCY'            => "Target currency",
    'TEMP_CLIENT_RATE_DATE'             => "Date ",
    'TEMP_CLIENT_RATE'                  => "Rate ",
    'TEMP_CLIENT_EXRATE'                => "Exchange Rate",
    'TEMP_CLIENT_RATE_SPREAD'           => "Rate spread ",
    'TEMP_CLIENT_EXRATE_SPREAD'         => "Exchange Rate Spread ",
    'TEMP_CLIENT_GEN_AFTER_APPOVE'      => "Generated after appove ",
    'TEMP_CLIENT_EMAIL_SEPARATED'       => "Multiple email separated by ';' ",
    'TEMP_CLIENT_INTEREST_RATE_EMAILTPL' => "Interest rate mail tpl ",
    'TEMP_CLIENT_EX_RATE_EMAILTPL'      => "Exchange rate mail tpl ",
    'TEMP_CLIENT_MARKET_EMAILTPL'       => "Market Intelligence mail tpl",
    'TEMP_CLIENT_EMAIL_TYPE'            => "Mail type",
    'TEMP_CLIENT_START_DATE'            => "Start date ",
    'TEMP_CLIENT_END_DATE'              => "End date ",
    'TEMP_CLIENT_MAILS_SEND_TIME'       => "Mail auto send time ",
    'TEMP_CLIENT_MAIL_SEND_TIME'        => "Interest rate mail auto send time ",
    'TEMP_CLIENT_EX_MAIL_SEND_TIME'     => "Exchange rate mail auto send time ",
    'TEMP_CLIENT_NEGATIVE_RATE_ALLOW'   => "Negative rate allow ",

    'TEMP_CLIENT_MARKET_TITLE'          => "Title",
    'TEMP_CLIENT_MARKET_TIME'           => "Time",
    'TEMP_CLIENT_MARKET_INPUT_USER'     => "Input User",
    'TEMP_CLIENT_MARKET_ACTION'         => "Action",
    'TEMP_CLIENT_MARKET_EN_TITLE'       => "Title(english)",
    'TEMP_CLIENT_MARKET_CN_TITLE'       => "Title(simplified)",
    'TEMP_CLIENT_MARKET_HK_TITLE'       => "Title(traditional)",
    'TEMP_CLIENT_MARKET_EN_CONTENT'     => "Content(english)",
    'TEMP_CLIENT_MARKET_CN_CONTENT'     => "Content (simplified)",
    'TEMP_CLIENT_MARKET_HK_CONTENT'     => "Content (traditional)",

    'CLIENT_ERROR_MARKET_EXIST'         => "Market intelligence record already exist",
    'CLIENT_ERROR_CLIENT_EXIST'         => "Client record already exist ",
    'CLIENT_ERROR_CLIENT_NOTEXIST'      => "Client record does not exist ",
    'CLIENT_ERROR_ADDR_ASS_AC'          => "The address is already associated account ",
    'CLIENT_ERROR_ADDR_INP_SEQ'         => "Please input address sequence ",
    'CLIENT_ERROR_ADDR_INP_ERR'         => "Wrong address input ",
    'CLIENT_ERROR_ACCOUNT_NOTEXIST'     => "Account record does not exist ",

    'CLIENT_ERROR_INTEREST_NOT_INPUT'   => "Interest not input ",
    'CLIENT_ERROR_EXRATE_NOT_INPUT'     => "Exchange rate not input ",
    'CLIENT_ERROR_EXRATE_NOT_REPEAT'    => "The same monetary needs no maintenance",
    'CLIENT_ERROR_INTEREST_FORMAT_ERR'  => "Interest format error ",
    'CLIENT_ERROR_EXRATE_FORMAT_ERR'    => "Exchange rate format error",
    'CLIENT_ERROR_INTEREST_NOT_UPD'     => "Not update ",
    'CLIENT_ERROR_EXRATE_NOT_UPD'       => "Not update ",
    'CLIENT_ERROR_SPREAD_TOO_MUCH'      => "Too much difference ",
    'CLIENT_ERROR_EX_SPREAD_TOO_MUCH'   => "Too much difference ",
    'CLIENT_ERROR_END_LESS_START'       => "The end date less then start date ",
    'CLIENT_ERROR_INST_RATE_NOT_UP'     => "Interest rate didn't upload ",
    'CLIENT_ERROR_EX_RATE_NOT_UP'     => "Exchange rate didn't upload ",
    'CLIENT_EMAIL_NOT_BELONG_CLIENT'    => "Email does not belong to the client ",

    //===================释义、状态释义======================//
    'CLIENT_AUTO_SEND_FLAG_TEXT'        => array('0' => 'Disable','1' => 'Enable'),
    'CLIENT_NEGATIVE_RATE_ALLOW_TEXT'   => array('0' => 'Disable','1' => 'Enable'),
    'CLIENT_TPL_LANGUAGE'               => array('en' => 'English','zh_s' => 'Chinese(Simplified)','zh_t'=>'Chinese(Traditional)'),
    'CLIENT_TPL_PRIORITY_TEXT'          => array(
                                            'en-zh_s-zh_t' => 'English - Chinese(Simplified) - Chinese(Traditional)',
                                            'en-zh_t-zh_s' => 'English - Chinese(Traditional) - Chinese(Simplified)',
                                            'zh_s-en-zh_t' => 'Chinese(Simplified) - English - Chinese(Traditional)',
                                            'zh_s-zh_t-en' => 'Chinese(Simplified) - Chinese(Traditional) - English',
                                            'zh_t-en-zh_s' => 'Chinese(Traditional) - English - Chinese(Simplified)',
                                            'zh_t-zh_s-en' => 'Chinese(Traditional) - Chinese(Simplified) - English'),
);
