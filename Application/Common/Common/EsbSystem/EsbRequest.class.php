<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/13
 * Time: 18:28
 */

namespace Common\Common\EsbSystem;
use Common\Common\XMLTools;


class EsbRequest
{
    const CHANNEL_POST          = '0';
    const CHANNEL_EMAIL         = '1';
    const CHANNEL_SMS           = '2';
    const CHANNEL_WECHAT        = 'b';
    const CHANNEL_ALIPAY        = 'c';
    const XML_DATA_LEN_DIGITS   = 8;
    private $_socket;
    private $_errorMessage;
    private $_xmlHead = array(
        'RequestID'         => '19',
        'TransactionDate'   => "",
        'TransactionTime'   => "",
        'RequestRefno'      => "",
        'AccountingDate'    => "",
        'ResponseID'        => '15',
        'MessageType'       => 'NELG',
    );
    private $_xmlBody = array(
        'InformChannel'     => '1',
        'ZipCode'           => 'HKBS000',
        'StoreMode'         => '1',
        'TargetAdr'         => '',
        'Content'           => '',
        'InformDate'        => '',
        'StartTime'         => '',
        'Occurtime'         => '0000',
        'EndTime'           => '2400',
    );

    public function __construct()
    {
        $this->_esbConfig = explode(":",C('ESB_CONFIG'))
    }

    /**
     * 建立到服务器的网络连接
     * @access private
     * @return boolean
     */
    private function socket() {
        if(!function_exists("socket_create")) {
            $this->_errorMessage = "Extension sockets must be enabled";
            return false;
        }
        //创建socket资源
        $this->_socket = socket_create(AF_INET, SOCK_STREAM,SOL_TCP);
        if(!$this->_socket) {
            $this->_errorMessage = socket_strerror(socket_last_error());
            return false;
        }
        //socket_set_block($this->_socket);//设置阻塞模式
        //连接服务器
        if(!socket_connect($this->_socket, $this->_esbConfig[0], $this->_esbConfig[1])) {
            //$this->close();
            $this->_errorMessage = socket_strerror(socket_last_error());
            dump($this->_errorMessage);
            // return false;
        }
        //socket_read($this->_socket, 1024);
        return true;
    }

    /**
     * 邮件发送
     */
    public function request($channel,$address,$content){
        if(false === $this->socket()){
            return false;
        }
        $this->init($channel,$address,$content);
        $data = array(
            'HEAD' => $this->_xmlHead,
            'BODY' => $this->_xmlBody,
        );
        dump($data);
        $xmlData = XMLTools::arrayToXml($data,'ROOT');
        $xmlDataLen = strlen($xmlData);
        dump($xmlDataLen);
        $xmlDataLen = sprintf("%0".self::XML_DATA_LEN_DIGITS."d", $xmlDataLen);
        $requestData = $xmlDataLen.$xmlData;
        dump($xmlDataLen);
        dump($requestData);
        //发送内容给服务器
        try{
            if(socket_write($this->_socket, $requestData, strlen($requestData))){
                //读取服务器返回
                //$returnData = trim(socket_read($this->_socket, 1024));
                $returnData = "";
                while ($buf = socket_read($this->_socket, 1024)){
                    $returnData .= $buf;
                }
                if($returnData) {
                    //取得报文头8位的长度标识
                    $returnXmlDataLen = intval(substr($returnData,0,8));
                    $returnDataArr = XMLTools::xmlToArray(substr($returnData,8,$returnXmlDataLen));
                    if('E' == $returnDataArr['HEAD']['ResponseStatus']){
                        $this->_errorMessage = $returnDataArr['HEAD']['ResponseMessage'];
                        return false;
                    }
                }else{
                    $this->_errorMessage = "Error:" . socket_strerror(socket_last_error());
                    return false;
                }
            }
            else{
                $this->_errorMessage = "Error:" . socket_strerror(socket_last_error());
                return false;
            }
        }catch(Exception $e) {
            $this->_errorMessage = "Error:" . $e->getMessage();
            return false;
        }
        $this->close();
        return true;
    }

    /**
     *报文初始化
     */
    private function init($channel,$address,$content){
        //写报文头
        $this->_xmlHead['TransactionDate'] = date('Ymd');
        $this->_xmlHead['TransactionTime'] = date('His');
        $this->_xmlHead['RequestRefno'] = time().rand(100000000,999999999);
        $this->_xmlHead['AccountingDate'] = date('Ymd');
        //写报文体
        $this->_xmlBody['InformChannel'] = $channel;
        $this->_xmlBody['TargetAdr'] = $address;
        $this->_xmlBody['Content'] = $content;
        $this->_xmlBody['InformDate'] = date('Ymd');
        $this->_xmlBody['StartTime'] = date('His');
    }

    /**
     * 关闭socket
     * @access private
     * @return boolean
     */
    private function close() {
        if(isset($this->_socket) && is_object($this->_socket)) {
            //socket_shutdown($this->_socket);
            socket_close($this->_socket);
            return true;
        }
        $this->_errorMessage = "No resource can to be close";
        return false;
    }

    /**
     * 取得错误信息
     */
    public function getError(){
        return $this->_errorMessage;
    }
}
