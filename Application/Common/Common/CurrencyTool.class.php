<?php

namespace Common\Common;
use Admin\Model\CurrencyModel;

class CurrencyTool {

	private $returnMsg = "";  //错误信息

	// 添加市场信息
	public function add($currencyArr){
		if(!$this->recordProc($currencyArr,'A')){
			return false;
		}
		LogTools::activeLog($currencyArr);
		return true;
	}

	// 修改市场信息
	public function update($currencyArr){
		if(!$this->recordProc($currencyArr,'M')){
			return false;
		}
		LogTools::activeLog($currencyArr);
		return true;
	}

	// 删除市场记录
	public function delete($id){
		if("" == $id){
			$this->returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');   //ID未输入
			return false;
		}
		$Currency = M('currency');
		//检查记录是否存在
		if(!is_array($ccyResult = $Currency->find($id))){
			$this->_returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST');
			return false;
		}
		if(!$Currency->delete($id)){
			$this->returnMsg = $Currency->getError();
			return false;
		}
		$ccyArr = $Currency->where(array('status'=>1))->getField('id,name_en,name_zh,sign',true);
		S('ccyArr',$ccyArr,3600*24);
		LogTools::activeLog($ccyResult);
		return true;
	}

	// 输入检查
	private function recordProc($currencyArr,$func){
		if(!is_array($currencyArr)){
			$this->returnMsg = L('SYSTEM_ERROR_SYSTEM_ERROR'.'(CurrencyTool)');   //调用错误
			return false;
		}
		// 输入项检查
		if(!isset($currencyArr['id']) || empty($currencyArr['id'])){
			$this->returnMsg = L('TEMP_SYSTEM_CCY_ID').','.L('SYSTEM_ERROR_MUST_INPUT');   //ID未输入
			return false;
		}else{
			$currencyArr['id'] = strtoupper($currencyArr['id']);
		}
		if(!isset($currencyArr['name_en']) || empty($currencyArr['name_en'])){
			$this->returnMsg = L('TEMP_SYSTEM_CCY_NAME_EN').','.L('SYSTEM_ERROR_MUST_INPUT');   //名称未输入
			return false;
		}
		if(!isset($currencyArr['name_zh']) || empty($currencyArr['name_zh'])){
			$this->returnMsg = L('TEMP_SYSTEM_CCY_NAME_ZH').','.L('SYSTEM_ERROR_MUST_INPUT');   //名称未输入
			return false;
		}
		if(!isset($currencyArr['sign']) || empty($currencyArr['sign'])){
			$this->returnMsg = L('TEMP_SYSTEM_CCY_SIGN').','.L('SYSTEM_ERROR_MUST_INPUT');   //名称未输入
			return false;
		}
		if(isset($currencyArr['sort'])){
			$currencyArr['sort'] = intval($currencyArr['sort']);
		}
		if(!isset($currencyArr['status'])){
			$currencyArr['status'] = 1;
		}
		$Currency = new CurrencyModel();
		if('A' == $func){
			if($Currency->where(array('id'=>$currencyArr['id']))->count() > 0){
				$this->returnMsg = L('CONT_SYS_ERROR_CCY_ID_EXIST');
				return false;
			}
		}
		if(!$Currency->create($currencyArr)){
			$this->returnMsg = L($Currency->getError());
			return false;
		}
		if('A' == $func){
			if(!$Currency->add()){
				$this->returnMsg = $Currency->getError();
				return false;
			}
		}else{
			$returnCode = $Currency->save();
			if(0 === $returnCode){
				$this->returnMsg  = L('SYSTEM_MESSAGE_NOT_CHANGE');
				return false;
			}elseif(!$returnCode){
				$this->returnMsg = $Currency->getError();
				return false;
			}
		}
		$ccyArr = $Currency->where(array('status'=>1))->order('sort')->getField('id,name_en,name_zh,sign,status',true);
		S('CCY_ARRAY_DATA',$ccyArr,3600*24);
		return true;
	}

	// 取得验证错误信息
	public function getError(){
		return $this->returnMsg;
	}

	//取得当前最新货币数组
	public function getCcyArr($status=''){
		$map = array();
		if("" != $status){
			$map['status'] = $status;
		}
		$ccyArr = S('CCY_ARRAY_DATA');
		if(!$ccyArr){
			$currency = M('Currency');
			$ccyArr = $currency->where($map)->order('sort')->getField('id,name_en,name_zh,sign,status',true);
			S('CCY_ARRAY_DATA',$ccyArr,3600*24);
		}
		foreach ($ccyArr as $key => $value) {
            $ccyArr[$key]['name'] = 'en-us' == LANG_SET ? $value['name_en'] : $value['name_zh'] ;
            unset($ccyArr[$key]['name_en']);
            unset($ccyArr[$key]['name_zh']);
        }
        return $ccyArr;
	}

}
