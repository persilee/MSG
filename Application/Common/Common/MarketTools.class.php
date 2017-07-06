<?php

namespace Common\Common;

class MarketTools {

	const APPOVE_TYPE = 'MK';
	private $returnMsg = "";  //错误信息
	private $_appoveFlag = "";
	private $_marketModel = null;
	private $_appoveModel = null;

	public function __construct()
	{
		$this->_marketModel = D('Market');
	}

	// 添加市场资讯
	public function add($dataArr){
		if (!$dataArr['seq']) {
			$dataArr['seq'] = 0;
		}
		$market = M('market');
		// 检查记录号是否已经存在
		if($market->where(array('seq'=>$dataArr['seq']))->count() > 0){
			$this->returnMsg = L('CLIENT_ERROR_MARKET_EXIST');
			return false;
		}
		//取得当前日期最大SEQ
		$seq = $market->where(array('date'=>$dataArr['date']))->Max('seq');
		$dataArr['update_emp'] = UID;
		// $dataArr['seq'] = ++$seq;
		if($market->add($dataArr)){
			LogTools::activeLog($dataArr);
			return true;
		}else{
				$this->returnMsg = $market->getError();
				return false;
		}
	}

	// 修改市场资讯
	public function update($dataArr){
		// 检查客户号是否已经存在
		if(!is_array($marketResult = $this->_marketModel->find($dataArr['seq']))){
			$this->returnMsg = L('CLIENT_ERROR_CLIENT_EXIST');
			return false;
		}
		$returnCode = $this->_marketModel->save($dataArr);
		if(0 === $returnCode){
			$this->returnMsg  = L('SYSTEM_MESSAGE_NOT_CHANGE');
			return false;
		}elseif(!$returnCode){
			$this->returnMsg = $this->_clientModel->getError();
			return false;
		}
		LogTools::activeLog($dataArr);
		return true;
	}

	// 删除市场资讯
	public function delete($seq){
		if("" == $seq){
			$this->returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');   //账户未输入
			return false;
		}
		// 检查市场资讯是否存在
		if(!is_array($marketResult = $this->_marketModel->find($seq))){
			$this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
			return false;
		}
		return $this->_marketModel->where(array('seq'=>$seq))->delete();
		LogTools::activeLog($marketResult);
		return true;
	}

	// 取得验证错误信息
	public function getError(){
		return $this->returnMsg;
	}

}
