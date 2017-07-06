<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/8/7
 * Time: 10:19
 * 功能包含：后台控制模板基础功能
 * 修改历史：
 *       日期           修改人             修改功能
 *     xxxx/xx/xx      习大大            这是一个示例模板
 */

namespace Admin\Controller;
use Common\Common\ConfigTools;
use Think\Controller;
use Common\Common\Page;
use Common\Common\Catalog;
use Common\Common\RbacCommon;

class AdminController extends Controller{

    /*================================================================================================*/
    /*  后台控制模板基础功能 -- 用于所有后台控制器继承
    /*      功能清单：
    /*          1-_initialize    ： 后台控制器初始化 --
    /*                                  动太配置后台参数
    /*                                  检查节点访问权限
    /*                                  生成个人访问目录
    /*                                  组织节点树
    /*          2-home           ： 系统主页跳转
    /*          3-lists          ： 分页浏览
    /*================================================================================================*/
    /**
     * 后台控制器初始化
     */
    protected function _initialize(){
        //载入配置项
        ConfigTools::init();

        // 获取当前用户ID
        define('UID',is_login());
        if( !UID ){// 还没登录 跳转到提示登录页面
            $this->redirect('Index/unLogin');
        }

        if(!RbacCommon::AccessDecision()){
            $this->error(L('SYSTEM_MESSAGE_NO_PERMISSIONS'));
        }

        //取得当前位置
        if(!($nodePlaceTree = S('NodeTree'))){
            $Node = M('Node');
            $seachArray = array(
                'id',
                'name',
                'title_en',
                'title_zh',
                'pid',
                'level',
            );
            $nodeResult = $Node->field($seachArray)->order('sort')->select();
            $nodePlaceTree = sortPlaceTree($nodeResult);
            if(defined(APP_DEBUG) && true !== APP_DEBUG){
                S('NodeTree',3600);//当前测试，不开启
            }
        }
        $place = array();
        if('en-us' == LANG_SET){
            $place[] = $nodePlaceTree[strtoupper(MODULE_NAME)]['child'][strtoupper(CONTROLLER_NAME)]['title_en'];
            $place[] = $nodePlaceTree[strtoupper(MODULE_NAME)]['child'][strtoupper(CONTROLLER_NAME)]['child'][strtoupper(ACTION_NAME)]['title_en'];
        }else{
            $place[] = $nodePlaceTree[strtoupper(MODULE_NAME)]['child'][strtoupper(CONTROLLER_NAME)]['title_zh'];
            $place[] = $nodePlaceTree[strtoupper(MODULE_NAME)]['child'][strtoupper(CONTROLLER_NAME)]['child'][strtoupper(ACTION_NAME)]['title_zh'];
        }
        $this->assign('__PLACE__',$place);
    }

    public function home(){
        //检查初始密码是否修改
        $pwd = session('pwd');
        if(system_md5('123') == $pwd){
            //如果没有修改初始密码,则跳转至初始密码修改界面
            $this->redirect('Account/initPwd');
        }else{
            //检查密码是否用了90天
            $pwd_change_date = session('pwd_change_date');
            if(null == $pwd_change_date || 0 == $pwd_change_date){
                $this->redirect('Account/initPwd');
            }else {
                $pwd_change_date = day_format(session('pwd_change_date'));
                $pwd_change_days = get_days($pwd_change_date, date('Y-m-d'), 1);
                if (90 < $pwd_change_days) {
                    $this->redirect('Account/initPwd');
                }
            }
        }
        //清除菜单项COOKIE
        // cookie('liactive',null);
        // cookie('ulactive',null);
        // 取得个人目录文件，保存cookie
            $Catalog = new Catalog();
            $catalogArr = $Catalog->generation();
            // if(defined(APP_DEBUG) && true !== APP_DEBUG){
            // cookie('__MENU__',$catalogArr);
            // }
        // }
        $this->assign('__MENU__',$catalogArr);

        // 取得头像地址
        $Emp = M('Emp');
        $img_file = $Emp->where(array('id'=>UID))->getField('img_file');
        session('img_url',__ROOT__.'/Uploads/empImg/'.$img_file);

        $this->display('home');
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param string|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param array        $base    基本的查询条件
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @param boolean      $relation 关联模型时使用该参数，如果指定关联的表，则给该栏位赋值关联名称
     * @param array        $condition 给关联模型动态添加condition条件；
     *                                mapping子项表示要添加至哪个mapping；
     *                                where子项指定condition具体条件内容
     * @author 朱亚杰 <xcoolcc@gmail.com>
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$base = '',$field=true,$relation=false,$condition=false){
        $options    =   array();
        // $REQUEST    =   (array)I('request.');
        if(IS_GET){
            $REQUEST    =   (array)I('get.');
        }else{
            $REQUEST    =   (array)I('post.');
        }
        if(is_string($model)){
            $model  =   D($model);
        }
        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);
        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }elseif ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        $options['where'] = array_filter(array_merge( (array)$base, /*$REQUEST,*/ (array)$where ),function($val){
            if($val===''||$val===null){
                return false;
            }else{
                return true;
            }
        });
        if( empty($options['where'])){
            unset($options['where']);
        }

        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        if(is_array($condition)){
            $model->condition($condition);
        }
        $total        =   $model->where($options['where'])->count();
        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 20;
        }

        $page = new Page($total, $listRows, $REQUEST);
        $p = $page->show();
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;
        $model->setProperty('options',$options);
        if(!$relation){
             return $model->field($field)->select();
        }else{
             return $model->field($field)->relation($relation)->select();
        }
    }
}
