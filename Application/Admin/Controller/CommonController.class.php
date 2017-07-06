<?php
/**
 * Created by Sublime Text.
 * User: Hipr
 * Date: 2015/10/27
 * Time: 16:43
 * 功能包含：公用交易
 * 修改历史：
 *       日期           修改人             修改功能
 *     xxxx/xx/xx      习大大            这是一个示例模板
 */

namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller{

    protected function _initialize(){
        if( !UID ){// 还没登录 提示错误信息
            exit();
        }

        //载入配置项
        //ConfigTools::init();
    }

    /*================================================================================================*/
    /*  工时模块公共交易，不需要验证权限
    /*      功能清单：
    /*          1-mailTplFileUpload          ： 邮件模板文件上传
    /*================================================================================================*/
    // 邮件模板文件上传
//    public function mailTplFileUpload(){
//        $return  = array('status' => 1, 'info' => L('SYSTEM_ACTION_UPLOAD_SUCCESS'), 'data' => '');
//        // 读取上传文件并写表
//        $upload = new \Think\Upload();// 实例化上传类
//        $upload->maxSize   =     2000000 ;// 设置附件上传大小
//        $upload->exts      =     array('html');// 设置附件上传类型
//        $upload->rootPath  =     $_SERVER['DOCUMENT_ROOT'].__ROOT__.'/Uploads/mailTpl/'; // 设置附件上传目录
//        $upload->savePath  =     ''; // 设置附件上传（子）目录
//        $upload->autoSub   =     false;// 上传文件
//        $upload->saveName  =     MD5(NOW_TIME.UID.rand(1000,9999));// 上传文件
//        $info   =   $upload->uploadOne($_FILES['temp_file']);
//        if(!$info) {// 上传错误提示错误信息
//            $return['status'] = 0;
//            $return['info']   = $upload->getError();
//        }else{// 上传成功 获取上传文件信息
//            $return['data'] = $info['savename'];
//        }
//        $this->ajaxreturn($return);
//    }
    //刷新在线情况
    public function refresh(){
        $this->ajaxreturn('success');
    }

}