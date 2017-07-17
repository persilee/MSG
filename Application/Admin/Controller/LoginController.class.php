<?php

namespace Admin\Controller;
use Think\Controller;
use Common\Common\ReportTools;
use Common\Common\ConfigTools;
use Common\Common\DaySessReportTools;
use Common\Common\DayErrorReportTools;
use Common\Common\AutoDataBackup;
use Common\Common\AutoInterestRateMail;
use Common\Common\AutoInstRateSpreadCheckMail;
use Common\Common\AutoDaySessReport;
use Common\Common\AutoTaskTools;
use Common\Common\AutoDayErrorReport;
use Common\Common\AutoMonthSessReport;
use Common\Common\AutoMonthErrorReport;
use Common\Common\AutoQuarterSessReport;
use Common\Common\AutoQuarterErrorReport;
use Common\Common\AutoYearSessReport;
use Common\Common\AutoYearErrorReport;
use Common\Common\ExRateTools;
use Common\Common\EsbSystem\EsbRequest;
use Common\Common\EsbSystem\EsbEmail;
use Common\Common\ExRateSpreadCheckMail;


class LoginController extends Controller {
  protected function _initialize(){
      //载入配置项
      ConfigTools::init();
    }
    public function hello(){

      echo 'hello,thinkphp!';
    }
    public function exRateSpreadCheckMail(){
      $exRateSpreadCheckMail = new ExRateSpreadCheckMail();
  		$exRateSpreadCheckMail->check('11');
      $this->display();
    }
    public function APItest(){

      $this->display();
    }

    public function ebsSendTest(){
      $esbRequest = new EsbRequest();
      // $esbEmail = new EsbEmail();
      // $esbEmail->send('abc','persilee@foxmail.com');
      $esbRequest->request(EsbRequest::CHANNEL_EMAIL,'persilee@foxmail.com','aaaa');
      $this->display();
    }
    //自动对客利率邮件发送处理
    public function autoInstRateMail(){
      $autoInterestRateMail = new AutoInterestRateMail();
      $autoInterestRateMail->exec();
      $this->display();
    }
    public function autoDaySessReport(){
      $autoDaySessReport = new AutoDaySessReport();
      $autoDaySessReport->exec();
      $this->display();
    }
    public function autoDayErrorReport(){
      $autoDayErrorReport = new AutoDayErrorReport();
      $autoDayErrorReport->exec();
      $this->display();
    }
    public function autoMonthSessReport(){
      $autoMonthSessReport = new AutoMonthSessReport();
      $autoMonthSessReport->exec();
      $this->display();
    }
    public function autoMonthErrorReport(){
      $autoMonthErrorReport = new AutoMonthErrorReport();
      $autoMonthErrorReport->exec();
      $this->display();
    }
    public function autoQuarterSessReport(){
      $autoQuarterSessReport = new AutoQuarterSessReport();
      $autoQuarterSessReport->exec();
      $this->display();
    }
    public function autoQuarterErrorReport(){
      $autoQuarterErrorReport = new AutoQuarterErrorReport();
      $autoQuarterErrorReport->exec();
      $this->display();
    }
    public function autoYearSessReport(){
      $autoYearSessReport = new AutoYearSessReport();
      $autoYearSessReport->exec();
      $this->display();
    }
    public function autoYearErrorReport(){
      $autoYearErrorReport = new AutoYearErrorReport();
      $autoYearErrorReport->exec();
      $this->display();
    }
    public function testReport(){
      // $autoTaskTools = new AutoTaskTools();
      // $autoTaskTools->checkTask('AutoDaySessReport');
      // $date = array(
      //   date_to_int('-',date('Y-m-01', strtotime('-1 month'))),
      //   date_to_int('-',date('Y-m-t', strtotime('-1 month'))),
      // );

      $reportTools = new ReportTools();
      $reportTools->saveReportList('AutoDaySessReport');


      $BeginDate = date('Y-m-01', strtotime(date("Y-m-d")));
      $date = array(
        date_to_int('-',$BeginDate),
        date_to_int('-',date('Y-m-d', strtotime("$BeginDate +1 month -1 day"))),
      );
      $month = date('Y-m-01', strtotime('-1 month'));
      $month1 = date('Y-m-t', strtotime('-1 month'));
      dump($month);
      dump($month1);
      $mytime = date("Y-01-01", strtotime("-1 year"));
      dump($mytime);
      $monthEndDays = cal_days_in_month(CAL_GREGORIAN, 12, date("Y", strtotime("-1 year")));
      $year = date("Y-12-".$monthEndDays, strtotime("-1 year"));
      echo 'year:' . $year;
      $monthDays = cal_days_in_month(CAL_GREGORIAN, date('m',strtotime('-1 month')), date('Y'));
      dump($monthDays);
      echo 'n:' . date('n');
      $season = ceil((date('n'))/3)-1;
      echo '<br>上季度起始时间:<br>';
      echo date('Y-m-d H:i:s', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))),"\n";
      echo date('Y-m-d H:i:s', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))),"\n";
      //获取第几季度
      // $season = ceil((date('n'))/3);
      // echo date('m', mktime(0, 0, 0,$season*3-3+1,1,date('Y'))),"\n";
      // echo date('Y-m-d', mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'))),"\n";
      dump($season);
      // $daySessReportTools = new DaySessReportTools();
      // $daySessReportTools->getDateList($date);
      // $daySessReportTools->daySessExcelGen($date);
      //
      // $dayErrorReportTools = new DayErrorReportTools();
      // $dayErrorReportTools->dayErrorExcelGen($date);
      // $daySessReportTools->getDateList($date);
      $str = stristr('DaySuccessReport_UID_2017-06-29.xlsx','_',true);
      dump($str);
      $this->display();
    }
    public function login()
    {
      $data['status']  = 1;
      $data['content'] = 'content';
      $this->ajaxReturn($data);
    }

    public function jump()
    {
      $posts = D('posts'); //实例化posts对象
      $data['title'] = 'cat';
      $data['content'] = '猫，属于猫科动物，分家猫、野猫，是全世界家庭中较为广泛的宠物。';
      $result = $posts->add($data);
      if($result){
          //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
          $this->success('新增成功','Login/login');
      } else {
          //错误页面的默认跳转页面是返回前一页，通常不需要设置
          $this->error('新增失败');
      }
    }

    public function variable()
    {
      $data['title'] = I('get.title');
      $data['content'] = I('get.content');
      $this->assign($data);
      dump($data);
      $this->display();
    }

    public function getPosts(){
      $posts = D('posts'); //实例化posts对象

      $posts->where('id=1')->find();

      dump($posts);
    }

    public function savePosts(){
      $posts = D('posts'); //实例化posts对象
      $data['title'] = 'dog';
      $data['content'] = '狗，（拉丁文:Canis lupus familiaris,英文名称dog）中文亦称“犬”，狗属于食肉目，分布于世界各地。狗与马、牛、羊、猪、鸡并称“六畜”。有科学家认为狗是由早期人类从灰狼...';
      $posts->where('id=6')->save($data);
    }

    public function deletePosts(){
      $posts = D('posts'); //实例化posts对象

      $posts->delete(4);
    }

}
