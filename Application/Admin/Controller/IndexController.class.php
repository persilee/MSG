<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/8/7
 * Time: 10:39
 * 功能包含：后台入口模块
 * 修改历史：
 *       日期           修改人             修改功能
 *     xxxx/xx/xx      习大大            这是一个示例模板
 */

namespace Admin\Controller;
use Think\Controller;
use Think\Verify;
use Common\Common\RbacCommon;
use Common\Common\LogTools;
use Common\Common\ConfigTools;

class IndexController extends Controller {

    /*================================================================================================*/
    /*  后台入口模块
    /*      功能清单：
    /*          01-login        ： 登录系统后台
    /*          02-verify       ： 登录验证码生成
    /*          03-index        ： 登录界面跳转
    /*          04-logout       ： 退出系统
    /*================================================================================================*/
    //用户登录
    public function login(){
        //载入配置项
        ConfigTools::init();
        if(IS_POST){
            //获取输入内容
            $user = I('user');
            $password = I('password');
            $verifycode = I('verify');

            //输入检查
            if("" == $user){
                // $this->error("请输入用户名！");
                $this->error(L('INDEX_ERROR_PLS_INPUT_USER'));
            }elseif("" == $password){
                // $this->error("请输入密码！");
                $this->error(L('INDEX_ERROR_PLS_INPUT_PWD'));
            }

            //检测验证码
            if('0' != C('VERIFY_SWITCH')){
                if("" == $verifycode){
                    $this->error(L('INDEX_ERROR_PLS_INPUT_VERIFY'));
                }else{
                    $verify = new Verify();
                    if(!$verify->check($verifycode,'')){
                       // $this->error('验证码输入错误！');
                        $this->error(L('INDEX_ERROR_VERIFY_ERROR'));
                    }
                }
            }

            //登录验证
            $Emp = D('Emp');
            //检查是用的什么类型进行登录：1-用户邮件；2-电话号码；3-昵称
            if(!is_array($empRecord = $Emp->loginCheck($user,1))){
                if(!is_array($empRecord = $Emp->loginCheck($user,2))){
                    if(!is_array($empRecord = $Emp->loginCheck($user,3))){
                        $this->error(L('INDEX_ERROR_USER_NOTEXIST'));
                    }
                }
            }

            //检查登录状态
            if(0 == $empRecord['login_switch']){
                //登记用户活动LOG
                //LogTools::activeLog('Login system failure(login lock)');
                // $this->error('您已被禁止登录，请联系管理员！');
                $this->error(L('INDEX_ERROR_LOGIN_LOCK'));
            }

            //验证密码
            if(!(system_md5($password) === $empRecord['password'])){
                //登记用户活动LOG
                //LogTools::activeLog('Login system failure(password error)');
                $errCount = $empRecord['pwd_err_count'] + 1;
                $pwdErrData = array(
                    'id' => $empRecord['id'],
                    'pwd_err_count' => $empRecord['pwd_err_count'] + 1,
                );
                if($errCount > 2){
                    $pwdErrData['login_switch'] = 0;
                }
                $Emp->save($pwdErrData);
                $this->error(L('INDEX_ERROR_PWD_ERROR'));
            }

            //检查用户状态
            // switch($empRecord['status']){
            //     case 'R':
            //         $this->error('您已离职，不可登录系统！');
            //         break;
            //     default:
            //         continue;
            // }
            if('R' == $empRecord['status']){
                $this->error(L('INDEX_ERROR_IS_LEAVE'));
            }

            //记录SESSION登录状态
            $auth = array(
                'uid' => $empRecord['id'],
                'email' => $empRecord['email'],
                'name' => $empRecord['name'],
                'nikename' => $empRecord['nikename'],
                'system_sign' => C('SYSTEM_SIGN'),  //用于保证同一台主机多服务不会免密直接登录，但登录另一服务后前一服务会被退出
                'last_login_time' => NOW_TIME,
            );
            session('user_auth', $auth);
            session('user_auth_sign', data_auth_sign($auth));
            session(C('USER_AUTH_KEY'),$empRecord['id']);
            session('pwd',$empRecord['password']);  //用于检查初始密码是否修改
            session('pwd_change_date',$empRecord['pwd_change_date']);//用于检查密码最后修改日期

            //记录登记当前登录时间为最后登录时间，累计登录总次数（该调用段不做报错处理）
            $Emp->loginRegister($empRecord['id']);
            //权限验证session保存
            RbacCommon::saveAccessList();
            //记录登录LOG
            LogTools::activeLog('Login system');
            //跟转至首页
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), U('Admin/home'));
        } else {
            //如果SESSION中已经记录了登录状态，则直接跳转至主页，否则进入登录页
            if(is_login()){
                $this->redirect('Admin/home');
            }else{
                $this->display();
            }
        }
    }

    //登录验证码生成
    public function verify(){
        $verifyconfig = array(
            'imageH'   =>     40,
            'length'   =>     4,
            'fontSize' =>     20,
            'useCurve' =>     false,
            'useNoise' =>     true,
        );
        $Verify = new Verify($verifyconfig);
        $Verify->entry();
    }

    //初始化进入登录界面
    public function index(){
        $this->redirect('Index/login');
    }


    //退出登录，销毁SESSION
    public function logout(){
        //记录登录LOG
        LogTools::activeLog('Logout system');
        session('[destroy]'); // 销毁session
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'), U('Index/login'));
    }
}
