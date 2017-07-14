<?php
namespace Batch\Controller;
use Think\Controller;
use Common\Common\AutoInterestRateUpload;
use Common\Common\AutoExRateUpload;
use Common\Common\ConfigTools;
use Common\Common\AutoDataBackup;
use Common\Common\AutoInterestRateMail;
use Common\Common\AutoExRateMail;
use Common\Common\AutoInstRateSpreadCheckMail;
use Common\Common\AutoDaySessReport;
use Common\Common\AutoDayErrorReport;
use Common\Common\AutoMonthSessReport;
use Common\Common\AutoMonthErrorReport;
use Common\Common\AutoQuarterSessReport;
use Common\Common\AutoQuarterErrorReport;
use Common\Common\AutoYearSessReport;
use Common\Common\AutoYearErrorReport;

class IndexController extends Controller {

	public function __construct()
	{
		//载入配置项
		ConfigTools::init();
	}

	//自动对客利率邮件发送处理
	public function autoInstRateMail(){
		$autoInterestRateMail = new AutoInterestRateMail();
		$autoInterestRateMail->exec();
		exit;
	}

	//自动对客汇率邮件发送处理
	public function autoExRateMail(){
		$autoExRateMail = new AutoExRateMail();
		$autoExRateMail->exec();
		exit;
	}

	//数据库自动备份
	public function autoDataBackup(){
		$autoDataBackup = new AutoDataBackup();
		$autoDataBackup->exec();
		exit;
	}

	//利率文件自动上传
	public function autoInstRateUpload(){
		$autoInterestRateUpload = new AutoInterestRateUpload();
		$autoInterestRateUpload->exec();
		exit;
	}

	//汇率文件自动上传
	public function autoExRateUpload(){
		$autoExRateUpload = new AutoExRateUpload();
		$autoExRateUpload->exec();
		exit;
	}

	public function autoDaySessReport(){
		$autoDaySessReport = new AutoDaySessReport();
		$autoDaySessReport->exec();
		exit;
	}
	public function autoDayErrorReport(){
		$autoDayErrorReport = new AutoDayErrorReport();
		$autoDayErrorReport->exec();
		exit;
	}
	public function autoMonthSessReport(){
		$autoMonthSessReport = new AutoMonthSessReport();
		$autoMonthSessReport->exec();
		exit;
	}
	public function autoMonthErrorReport(){
		$autoMonthErrorReport = new AutoMonthErrorReport();
		$autoMonthErrorReport->exec();
		exit;
	}
	public function autoQuarterSessReport(){
		$autoQuarterSessReport = new AutoQuarterSessReport();
		$autoQuarterSessReport->exec();
		exit;
	}
	public function autoQuarterErrorReport(){
		$autoQuarterErrorReport = new AutoQuarterErrorReport();
		$autoQuarterErrorReport->exec();
		exit;
	}
	public function autoYearSessReport(){
		$autoYearSessReport = new AutoYearSessReport();
		$autoYearSessReport->exec();
		exit;
	}
	public function autoYearErrorReport(){
		$autoYearErrorReport = new AutoYearErrorReport();
		$autoYearErrorReport->exec();
		exit;
	}
}
