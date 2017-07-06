<?php

namespace Common\Common;

class ClientTools {

	const APPOVE_TYPE = 'CI';
	private $returnMsg = "";  //错误信息
	private $_appoveFlag = "";
	private $_clientModel = null;
	private $_appoveModel = null;

	public function __construct()
	{
		$this->_clientModel = D('Client');
		$this->_appoveModel = D('Appove');
		$this->_appoveFlag  = C('CLIENT_APPOVE');
	}

	// 添加客户信息
	public function add($dataArr){
		if(!$this->recordProc($dataArr,'A')){
			return false;
		}
		// 检查客户号是否已经存在
		if($this->_clientModel->where(array('ci_no'=>$dataArr['ci_no']))->count() > 0){
			$this->returnMsg = L('CLIENT_ERROR_CLIENT_EXIST');
			return false;
		}
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$dataArr['ci_no']))->count() > 0){
			$this->returnMsg = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}
		//根据需不需要复核进行不同处理
		if('Y' == $this->_appoveFlag){
			//添加待复核记录
			return $this->addAppove('A','',$dataArr);
		}else{
			$dataArr['create_emp'] = UID;
			if(!$this->_clientModel->add($dataArr)){
				$this->returnMsg = $this->_ClientModel->getError();
				return false;
			}
		}
		LogTools::activeLog($dataArr);
		return true;
	}

	// 修改客户信息
	public function update($dataArr){
		// 检查客户号是否已经存在
		if(!is_array($clientResult = $this->_clientModel->find($dataArr['ci_no']))){
			$this->returnMsg = L('CLIENT_ERROR_CLIENT_EXIST');
			return false;
		}
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$dataArr['ci_no']))->count() > 0){
			$this->returnMsg = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}
		//数据检查
		if(!$this->recordProc($dataArr,'M')){
			return false;
		}
		//根据需不需要复核进行不同处理
		if('Y' == $this->_appoveFlag) {
			//检查是否做过记录修改,并准备待复核数据
			$flag = true;
			foreach ($dataArr as $key => $value) {
				if ($value != $clientResult[$key]) {
					$clientResult[$key] = $value;
					$flag = false;
				}
			}
			if ($flag) {
				$this->returnMsg = L('SYSTEM_MESSAGE_NOT_CHANGE');
				return false;
			}
			//添加待复核记录
			return $this->addAppove('M',$dataArr['ci_no'],$clientResult);
		}else{
			$returnCode = $this->_clientModel->save($dataArr);
			if(0 === $returnCode){
				$this->returnMsg  = L('SYSTEM_MESSAGE_NOT_CHANGE');
				return false;
			}elseif(!$returnCode){
				$this->returnMsg = $this->_clientModel->getError();
				return false;
			}
		}
		LogTools::activeLog($dataArr);
		return true;
	}

	// 删除客户记录
	public function delete($ci_no){
		if("" == $ci_no){
			$this->returnMsg = L('SYSTEM_ERROR_NOT_SPECIFY_RECORD');   //账户未输入
			return false;
		}
		// 检查客户是否存在
		if(!is_array($clientResult = $this->_clientModel->find($ci_no))){
			$this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
			return false;
		}
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('type'=>self::APPOVE_TYPE,'reference'=>$ci_no))->count() > 0){
			$this->returnMsg = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}
		//根据需不需要复核进行不同处理
		if('Y' == $this->_appoveFlag) {
			//添加待复核记录
			return $this->addAppove('D', $ci_no, $ci_no);
		}else{
			return $this->_clientModel->where(array('ci_no'=>$ci_no))->delete();
		}
		LogTools::activeLog($clientResult);
		return true;
	}

	// 输入检查
	private function recordProc($dataArr,$func){
		if(!is_array($dataArr)){
			$this->returnMsg = L('SYSTEM_ERROR_SYSTEM_ERROR'.'(ClientTool)');   //调用错误
			return false;
		}
		// 输入项检查
//		if(!isset($dataArr['ci_no']) || empty($dataArr['ci_no'])){
//			$this->returnMsg = L('TEMP_CLIENT_NO').','.L('SYSTEM_ERROR_MUST_INPUT');   //客户号未输入
//			return false;
//		}else{
//			$dataArr['ci_no'] = trim($dataArr['ci_no']);
////			if(strpos($dataArr['ci_no']," ")){
//			if(!RegexTools::regex('alphanumeric',$dataArr['ci_no'])){
//				$this->returnMsg = L('TEMP_CLIENT_NO').','.L('SYSTEM_ERROR_FORMAT');   //账号格式有误
//				return false;
//			}
//		}
//		if(!isset($dataArr['type']) || empty($dataArr['type'])){
//			$this->returnMsg = L('TEMP_CLIENT_TYPE').','.L('SYSTEM_ERROR_MUST_INPUT');   //客户类型未输入
//			return false;
//		}
//		if(!isset($dataArr['id_type']) || empty($dataArr['id_type'])){
//			$this->returnMsg = L('TEMP_CLIENT_ID_TYPE').','.L('SYSTEM_ERROR_MUST_INPUT');   //证件类型未输入
//			return false;
//		}
		if(!isset($dataArr['id_code']) || empty($dataArr['id_code'])){
			$this->returnMsg = L('TEMP_CLIENT_ID_CODE').','.L('SYSTEM_ERROR_MUST_INPUT');   //证件号码未输入
			return false;
		}
		if(!isset($dataArr['name']) || empty($dataArr['name'])){
			$this->returnMsg = L('TEMP_CLIENT_NAME').','.L('SYSTEM_ERROR_MUST_INPUT');   //名称未输入
			return false;
		}
		if(!isset($dataArr['ot_name']) || empty($dataArr['ot_name'])){
			$this->returnMsg = L('TEMP_CLIENT_OT_NAME').','.L('SYSTEM_ERROR_MUST_INPUT');   //名称未输入
			return false;
		}
		if(!isset($dataArr['tpl_priority_lang']) || empty($dataArr['tpl_priority_lang'])){
			$this->returnMsg = L('TEMP_CLIENT_TPL_PRIORITY_LANG').','.L('SYSTEM_ERROR_MUST_INPUT');   //名称未输入
			return false;
		}
		// 检查Email格式
		if(!empty($dataArr['email'])) {
			$emailArr = explode(';',$dataArr['email']);
			foreach ($emailArr as $value) {
				if (!RegexTools::regex('email',$value)) {
					$this->returnMsg = L('TEMP_CLIENT_EMAIL') . ': ( '.$value.' ) , ' . L('SYSTEM_ERROR_FORMAT');
					return false;
				} else {
					//检查Email是否已经存在
					$where = array(
						'email' => array('LIKE','%'.$value.'%'),
					);
					if ('M' == $func) {
						$where['ci_no'] = array('NEQ', $dataArr['ci_no']);
					}
					if (is_array($repeatEmailArr = $this->_clientModel->where($where)->getField('email',true))) {
						foreach ($repeatEmailArr as $repeatEmail) {
							$pattern1 = "/^" . $value . '/';
							$pattern2 = "/;" . $value . '$/';
							$pattern3 = "/;" . $value . ';/';
							if (regexTools::regex($pattern1, $repeatEmail) ||
								regexTools::regex($pattern2, $repeatEmail) ||
								regexTools::regex($pattern3, $repeatEmail)
							) {
								$this->returnMsg = L('TEMP_CLIENT_EMAIL') . ': ( ' . $value . ' ) , ' . L('SYSTEM_ERROR_COMMON_ALREADY_EXIST');
								return false;
							}
						}
					}
					$where = array(
						'bcc_email' => array('LIKE','%'.$value.'%'),
					);
					if ('M' == $func) {
						$where['ci_no'] = array('NEQ', $dataArr['ci_no']);
					}
					if (is_array($repeatEmailArr = $this->_clientModel->where($where)->getField('bcc_email',true))) {
						foreach ($repeatEmailArr as $repeatEmail) {
							$pattern1 = "/^" . $value . '/';
							$pattern2 = "/;" . $value . '$/';
							$pattern3 = "/;" . $value . ';/';
							if (regexTools::regex($pattern1, $repeatEmail) ||
								regexTools::regex($pattern2, $repeatEmail) ||
								regexTools::regex($pattern3, $repeatEmail)
							) {
								$this->returnMsg = L('TEMP_CLIENT_EMAIL') . ': ( ' . $value . ' ) , ' . L('SYSTEM_ERROR_COMMON_ALREADY_EXIST');
								return false;
							}
						}
					}
				}
			}
		}elseif(1 == $dataArr['auto_email_flag']){
			$this->returnMsg = L('TEMP_CLIENT_EMAIL').','.L('SYSTEM_ERROR_MUST_INPUT');   //未输入
			return false;
		}
		// 检查BCC_Email格式
		if(!empty($dataArr['bcc_email'])) {
			$emailArr = explode(';',$dataArr['bcc_email']);
			foreach ($emailArr as $value) {
				if (!RegexTools::regex('email',$value)) {
					$this->returnMsg = L('TEMP_CLIENT_BCC_EMAIL') . ': ( '.$value.' ) , ' . L('SYSTEM_ERROR_FORMAT');
					return false;
				} else {
					//拆分邮件地址,如果是内部(即后缀是参数设置的,则不做重复性检查)
					$mailSeparateArr = explode('@',$value);
					$mailSeparateSuffix = '@'.$mailSeparateArr[1];
					if($mailSeparateSuffix != C('MAIL_SUFFIX')) {
						//检查Email是否已经存在
						$where = array(
							'email' => array('LIKE', '%' . $value . '%'),
						);
						if ('M' == $func) {
							$where['ci_no'] = array('NEQ', $dataArr['ci_no']);
						}
						if (is_array($repeatEmailArr = $this->_clientModel->where($where)->getField('email',true))) {
							foreach ($repeatEmailArr as $repeatEmail) {
								$pattern1 = "/^" . $value . '/';
								$pattern2 = "/;" . $value . '$/';
								$pattern3 = "/;" . $value . ';/';
								if (regexTools::regex($pattern1, $repeatEmail) ||
									regexTools::regex($pattern2, $repeatEmail) ||
									regexTools::regex($pattern3, $repeatEmail)
								) {
									$this->returnMsg = L('TEMP_CLIENT_EMAIL') . ': ( ' . $value . ' ) , ' . L('SYSTEM_ERROR_COMMON_ALREADY_EXIST');
									return false;
								}
							}
						}
						$where = array(
							'bcc_email' => array('LIKE', '%' . $value . '%'),
						);
						if ('M' == $func) {
							$where['ci_no'] = array('NEQ', $dataArr['ci_no']);
						}
						if (is_array($repeatEmailArr = $this->_clientModel->where($where)->getField('bcc_email',true))) {
							foreach ($repeatEmailArr as $repeatEmail) {
								$pattern1 = "/^" . $value . '/';
								$pattern2 = "/;" . $value . '$/';
								$pattern3 = "/;" . $value . ';/';
								if (regexTools::regex($pattern1, $repeatEmail) ||
									regexTools::regex($pattern2, $repeatEmail) ||
									regexTools::regex($pattern3, $repeatEmail)
								) {
									$this->returnMsg = L('TEMP_CLIENT_EMAIL') . ': ( ' . $value . ' ) , ' . L('SYSTEM_ERROR_COMMON_ALREADY_EXIST');
									return false;
								}
							}
						}
					}
				}
			}
		}
		//检查输入的利率模板是否存在
		if(!empty($this->_data['inst_rate_mailtpl'])){
			$Mailtpl = M('Mailtpl');
			if($Mailtpl->where(array('id'=>$this->_data['inst_rate_mailtpl'],'type'=>'INTEREST'))->count() < 1){
				$this->returnMsg = L('TEMP_CLIENT_INTEREST_RATE_EMAILTPL').','.L('SYSTEM_ERROR_RECORD_NOT_EXIST');   //未输入
				return false;
			}
		}
//		if(isset($dataArr['cust_group']) && !empty($dataArr['cust_group'])){
//			if(!array_key_exists($dataArr['cust_group'], C('CUSTOMER_GROUP'))){
//				$this->returnMsg = L('TEMP_CLIENT_CUSTOMER_GROUP').','.L('SYSTEM_ERROR_BEYOND_OPTION');
//				return false;
//			}
//		}
//		if(isset($dataArr['credit_rete']) && !empty($dataArr['credit_rete'])){
//			if(!array_key_exists($dataArr['credit_rete'], C('CUSTOMER_CREDIT_RATE'))){
//				$this->returnMsg = L('TEMP_CLIENT_CUSTOMER_CREDIT_RATE').','.L('SYSTEM_ERROR_BEYOND_OPTION');
//				return false;
//			}
//		}
//		if(isset($dataArr['education']) && !empty($dataArr['education'])){
//			if(!array_key_exists($dataArr['education'], C('CUSTOMER_EDUCATION'))){
//				$this->returnMsg = L('TEMP_CLIENT_CUSTOMER_EDUCATION').','.L('SYSTEM_ERROR_BEYOND_OPTION');
//				return false;
//			}
//		}
//		if(isset($dataArr['area']) && !empty($dataArr['area'])){
//			if(!array_key_exists($dataArr['area'], C('CUSTOMER_AREA'))){
//				$this->returnMsg = L('TEMP_CLIENT_CUSTOMER_AREA').','.L('SYSTEM_ERROR_BEYOND_OPTION');
//				return false;
//			}
//		}
//		if(isset($dataArr['sex']) && !empty($dataArr['sex'])){
//			if(!array_key_exists($dataArr['sex'], L('TEMP_EMP_EMP_SEX_TEXT'))){
//				$this->returnMsg = L('TEMP_EMP_EMP_SEX').','.L('SYSTEM_ERROR_BEYOND_OPTION');
//				return false;
//			}
//		}
		return true;
	}

	//浮动利率维护
	public function rateFloatUpdate($ci_no,$rateFloatArr){
		if(!is_array($clientResult = $this->_clientModel->find($ci_no))){
			$this->returnMsg = L('SYSTEM_MESSAGE_SCREEN_ERR');
			return false;
		}
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('reference'=>$ci_no))->count() > 0){
			$this->returnMsg = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}
		//检查输入值的正确性
		foreach ($rateFloatArr as $tenor => $ccyArr){
			foreach ($ccyArr as $ccy => $value){
				if('' == $value['value']){
					$rateFloatArr[$tenor][$ccy]['value'] = 0;
				}else{
					if(!RegexTools::amountCheck($value['value'],8,3,true)){
						$this->returnMsg = L('CLIENT_ERROR_INTEREST_FORMAT_ERR')." , Tenor( ".$tenor." ) ,Currency( ".$ccy.' )';
						return false;
					}
				}
				if(empty($value['is_rate'])){
					$rateFloatArr[$tenor][$ccy]['is_rate'] = 0;
				}else{
					$rateFloatArr[$tenor][$ccy]['is_rate'] = 1;
				}
			}
		}
		//检查是否做过修改
		$rateFloatStr = json_encode($rateFloatArr);
		if($rateFloatStr == $clientResult['rate_float']){
			$this->returnMsg = L('SYSTEM_MESSAGE_NOT_CHANGE');
			return false;
		}
		//添加待复核记录
		//根据需不需要复核进行不同处理
		$clientResult['rate_float'] = $rateFloatStr;
		if('Y' == $this->_appoveFlag) {
			return $this->addAppove('M', $ci_no, $clientResult);
		}else {
			if($returnCode = $this->_clientModel->where(array('ci_no'=>$ci_no))->setField('rate_float',$rateFloatStr)){
				//检查利差是否大于参数设置
				$this->rateSpreadCheckMail($clientResult);
				return true;
			}elseif(false ===  $returnCode){
				$this->returnMsg = $this->_clientModel->getError();
				return false;
			}else{
				$this->returnMsg = L('SYSTEM_MESSAGE_NOT_CHANGE');
				return false;
			}
		}
		LogTools::activeLog($clientResult);
		return true;
	}

	//浮动汇率维护
	public function exRateFloatUpdate($ci_no,$exRateFloatArr){
		if(!is_array($clientResult = $this->_clientModel->find($ci_no))){
			$this->returnMsg = L('SYSTEM_MESSAGE_SCREEN_ERR');
			return false;
		}
		//检查当前客户号待复核记录是否已经存在
		if($this->_appoveModel->where(array('reference'=>$ci_no))->count() > 0){
			$this->returnMsg = L('SYSTEM_ERROR_APPOVE_ALREADY_EXIST');
			return false;
		}

		//检查输入值的正确性
		foreach ($exRateFloatArr as $exchangeCcy => $targetCcyArr){
			foreach ($targetCcyArr as $targetCcy => $value){
				if('' == $value['value']){
					$exRateFloatArr[$exchangeCcy][$targetCcy]['value'] = 0;
				}else{
					if(!RegexTools::amountCheck($value['value'],8,3,true)){
						$this->returnMsg = L('CLIENT_ERROR_EXRATE_FORMAT_ERR')." , Exchange Currency( ".$exchangeCcy." ) , Target Currency( ".$targetCcy.' )';
						return false;
					}
				}
				if(empty($value['is_exRate'])){
					$exRateFloatArr[$exchangeCcy][$targetCcy]['is_exRate'] = 0;
				}else{
					$exRateFloatArr[$exchangeCcy][$targetCcy]['is_exRate'] = 1;
				}
			}
		}

		//检查是否做过修改
		$exRateFloatStr = json_encode($exRateFloatArr);
		if($exRateFloatStr == $clientResult['ex_rate_float']){
			$this->returnMsg = L('SYSTEM_MESSAGE_NOT_CHANGE');
			return false;
		}
		//添加待复核记录
		//根据需不需要复核进行不同处理
		$clientResult['ex_rate_float'] = $exRateFloatStr;
		if('Y' == $this->_appoveFlag) {
			return $this->addAppove('M', $ci_no, $clientResult);
		}else {
			if($returnCode = $this->_clientModel->where(array('ci_no'=>$ci_no))->setField('ex_rate_float',$exRateFloatStr)){
				//检查利差是否大于参数设置
				$this->rateSpreadCheckMail($clientResult);
				return true;
			}elseif(false ===  $returnCode){
				$this->returnMsg = $this->_clientModel->getError();
				return false;
			}else{
				$this->returnMsg = L('SYSTEM_MESSAGE_NOT_CHANGE');
				return false;
			}
		}
		LogTools::activeLog($clientResult);
		return true;
	}

	//记录复核
	public function appove($date,$seq){
		if(!is_array($appResult = $this->_appoveModel->where(array('date'=>$date,'seq'=>$seq))->find())){
			$this->returnMsg = L('SYSTEM_ERROR_RECORD_NOT_EXIST'); //记录不存在
			return false;
		}
		$this->_clientModel->startTrans();
		switch ($appResult['func']){
			case 'A':
				$data = json_decode($appResult['content'],true);
				if(false === $this->recordProc($data,'A')){
					return false;
				}
				$data['create_emp'] = $appResult['maker'];
				if(!$this->_clientModel->add($data)){
					$this->_clientModel->rollback();
					$this->returnMsg = $this->_ClientModel->getError();
					return false;
				}
				break;
			case 'M':
				$data = json_decode($appResult['content'],true);
				if(false === $this->recordProc($data,'M')){
					return false;
				}
				$oldFloatRate = $this->_clientModel->where(array('ci_no'=>$appResult['reference']))->getField('rate_float');
				if(false === $this->_clientModel->save($data)){
					$this->_clientModel->rollback();
					$this->returnMsg = $this->_clientModel->getError();
					return false;
				}
				break;
			case 'D':
				if(!$this->_clientModel->relation(true)->delete(json_decode($appResult['content'],true))){
					$this->_clientModel->rollback();
					$this->returnMsg = $this->_clientModel->getError();
					return false;
				}
				break;
			default :
				$this->returnMsg = L('SYSTEM_ERROR_SYSTEM_ERROR');   //调用错误
				return false;
				break;
		}
		if(!$this->_appoveModel->where(array('date'=>$date,'seq'=>$seq))->delete()){
			$this->_clientModel->rollback();
			$this->returnMsg = $this->_appoveModel->getError();
			return false;
		}
		$this->_clientModel->commit();
		if('M' == $appResult['func'] && $oldFloatRate != $data['rate_float']){
			$this->rateSpreadCheckMail($data);
		}
		LogTools::activeLog($appResult);
		return true;
	}

	//添加待复核记录
	private function addAppove($func,$ci_no,$data){
		//添加待复核记录
		$today = date_to_int('-',date('Y-m-d'));
		//取得当前日期最大记录顺序号
		$seq = $this->_appoveModel->where(array('date'=>$today))->max('seq');
		$appoveData = array(
			'date'	 	=> $today,
			'seq'   	=> ++$seq,
			'maker' 	=> UID,
			'type'      => self::APPOVE_TYPE,
			'func'      => $func,
			'reference' => $ci_no,
			'time'      => NOW_TIME,
			'content'   => json_encode($data),
		);
		if(!$this->_appoveModel->add($appoveData)){
			$this->returnMsg = $this->_appoveModel->getError();
			return false;
		}
	}

	//利率差大于参数设置的检查邮件
	private function rateSpreadCheckMail($ClientResult){
		$instRateSpreadCheckMail = new InstRateSpreadCheckMail();
		$instRateSpreadCheckMail->check($ClientResult['ci_no']);
	}

	//汇率差大于参数设置的检查邮件
	private function exRateSpreadCheckMail($ClientResult){
		$exRateSpreadCheckMail = new ExRateSpreadCheckMail();
		$exRateSpreadCheckMail->check($ClientResult['ci_no']);
	}

	// 取得验证错误信息
	public function getError(){
		return $this->returnMsg;
	}

}
