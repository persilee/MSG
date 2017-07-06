<?php

/**
 * Created by Atom.
 * User: Per
 * Date: 17/6/30
 * Time: 15:14
 */

namespace Common\Common;
use Common\Common\DownloadTools;

class ReportTools
{
	private $returnMsg = "";
	private $returnflg = true;

	// 新增报表生成记录
	public function saveReportList($code="",$name=""){
		if (empty($code) || empty($name)) {
			$this->returnMsg = L('SYSTEM_ERROR_MUST_INPUT');
			$this->returnflg = false;
		}else{
			$map = array(
				'code'       =>   $code,
			);
			$Report = M('Report');
			$Report_list = M('Report_list');
			$maxSeq = $Report_list->max('seq');
			if (is_array($Report->where($map)->select())) {
				$seq = $Report->where($map)->getField('seq');
				$data = array(
					'date'      =>   date_to_int('-',date('Y-m-d')),
					'seq'       =>   intval(++$maxSeq),
					'report_id' =>   intval($seq),
					'name'      =>   $name,
				);
				dump($data);
				dump($name);
				if (false === $Report_list->add($data)) {
					$this->returnMsg = $Report_list->getError();
					$this->returnflg = false;
				}
			}else{
				$this->returnMsg = $Report->getError();
				$this->returnflg = false;
			}
		}
		dump($this->returnflg);
		return $this->returnflg;
	}

	//下载报表
	public function reportDownload($name)
	{
			if (false == $this->returnflg) {
					return false;
			}
			$downloadTools = new DownloadTools();
			$filename = $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . '/Downloads/' . stristr($name,'_',true) . '/' . $name;
			return $downloadTools->downloadFile($filename,false);
	}

	//删除报表
	public function reportDelete($name)
	{
			if (false == $this->returnflg) {
					return false;
			}
			$filename = $_SERVER['DOCUMENT_ROOT'] . __ROOT__ . '/Downloads/' . stristr($name,'_',true) . '/' . $name;
			if (file_exists($filename)){
				unlink($filename);
			}
			return true;
	}

	// 取得验证错误信息
	public function getError(){
			return $this->returnMsg;
	}

}
