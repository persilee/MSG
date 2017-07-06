<?php
/**
 * Created by Sublime Text.
 * User: Hipr
 * Date: 2015/11/03
 * Time: 16:43
 * 功能包含：系统配置管理模块、功能节点管理、目录管理
 * 修改历史：
 *       日期           修改人             修改功能
 *    2015/12/13       林海宾            将节点维护及菜单维护放入本模块
 */

namespace Admin\Controller;

use Common\Common\LogTools;

class SystemController extends AdminController{

    /*================================================================================================*/
    /*  功能节点管理
    /*      功能清单：
    /*          1-nodeList    ： 节点列表浏览
    /*          2-nodeAdd     ： 节点信息添加
    /*          3-nodeUpdate  ： 节点信息更新
    /*          4-nodeDelete  ： 节点信息删除
    /*          5-nodeSort    ： 节点信息排序
    /*================================================================================================*/
    // 浏览节点列表
    public function nodeList(){
        $style = I('style');
        $level = I('level',0,'intval');
        $Node = M('Node');
        $map = array();
        if(0 != $level){
            $map[] = array('level'=>array('ELT',$level));
        }
        $list = $Node->where($map)->order('sort ASC')->select();
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        if('W' == $style){
            $list = tree($list);
            $this->assign('list',$list);// 赋值数据集
            $this->display('nodeListStyle2'); // 输出模板
        }else{
            $list = levelTree($list);
            $this->assign('list',$list);// 赋值数据集
            $this->assign('level',$level);
            $this->display('nodeListStyle1'); // 输出模板
        }
    }

    // 添加节点
    public function nodeAdd(){
        $pid = I('pid',0,'intval');
        $Node = D('Node');
        $pNode = $Node->where(array('id'=>$pid))->find();
        if(0!=$pid && !$pNode){
            $this->error(L('CONT_SYS_ERROR_PID_NOTEXIST'));
        }
        if(IS_POST){
            $name = I('name');
            $title_en = I('title_en');
            $title_zh = I('title_zh');
            $sort = I('sort',0,'intval');
            $icon = I('icon');
            $catalog_item = I('catalog_item',0,'intval');
            $remark = I('remark');
            // $level = I('level');
            // 输入检查
            if("" == $name || "" == $title_en || "" == $title_zh){
                $this->error(L('CONT_SYS_ERROR_REQUIRED_EMPTY'));
            }
            // 检查同级是否存在同名节点
            $nodeNameArr = $Node->where(array('pid'=>$pid))->getField('name',true);
            if(in_array($name, $nodeNameArr)){
                $this->error(L('CONT_SYS_ERROR_EXIST_NODE'));
            }
            if(0 == $pid){
                $level = 1;
            }else{
                $level = $pNode['level'] + 1; 
            }
            $data = array(
                'name' => $name,
                'title_en' => $title_en,
                'title_zh' => $title_zh,
                'sort' => $sort,
                'icon' => $icon,
                'pid' => $pid,
                'remark' => $remark,
                'level' => $level,
                'status' => 1,
                'catalog_item' => $catalog_item,
                'catalog_show' => 0,
            );
            if ($Node->create($data)) {
                if($nodeId = $Node->add()){
                    $data['id'] = $nodeId;
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }else{
                    $this->error(L('SYSTEM_MESSAGE_ERROR'));
                }
            } else {
                $this->error($Node->getError());
            }
        }else{
            $pid = I('pid',0,'intval');
            $this->assign('pid',$pid);
            if(0 == $pid){
                $nodename = L('TEMP_SYSTEM_NODE_APP');
            }elseif(1 == $pNode['level']){
                $nodename =  L('TEMP_SYSTEM_NODE_CONT');
            }else{
                $nodename =  L('TEMP_SYSTEM_NODE_ACTION');
            }
            $this->assign('nodename',$nodename);
            $this->display('nodeAdd');
        }
    }

    // 修改节点
    public function nodeUpdate(){
        $id = I('id',0,'intval');
        if(0 == $id){
            $this->error(L('CONT_SYS_ERROR_SPECIFY_RECORD'));
        }
        $Node = M('Node');
        $nodeRecord = $Node->find($id);
        if(!$nodeRecord){
            $this->error(L('CONT_SYS_ERROR_RECORD_NOTEXIST'));
        }
        if(IS_POST){
            $data['id'] = $id;
            $data['name'] = I('name');
            $data['title_en'] = I('title_en');
            $data['title_zh'] = I('title_zh');
            $data['sort'] = I('sort',0,'intval');
            $data['icon'] = I('icon');
            $data['remark'] = I('remark');
            $data['catalog_item'] = I('catalog_item',0,'intval');
            // 输入检查
            if("" == $data['name'] || "" == $data['title_en'] || "" == $data['title_zh']){
                $this->error(L('CONT_SYS_ERROR_REQUIRED_EMPTY'));
            }
            if($Node->create($data)){
                $returnCode = $Node->save();
                if(1 === $returnCode){
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }elseif(0 === $returnCode) {
                    $this->error(L('SYSTEM_MESSAGE_NOT_CHANGE'));
                }else{
                    $this->error(L('SYSTEM_MESSAGE_ERROR'));
                }
            }else{
                $this->error($Node->getError());
            }
        }else{
            if(0 == $nodeRecord['pid']){
                $nodename = L('TEMP_SYSTEM_NODE_APP');
            }elseif(2 == $nodeRecord['level']){
                $nodename =  L('TEMP_SYSTEM_NODE_CONT');
            }else{
                $nodename =  L('TEMP_SYSTEM_NODE_ACTION');
            }
            $this->assign('nodename',$nodename);
            $this->assign('result',$nodeRecord);
            $this->display('nodeUpdate');
        }
    }

    // 删除节点
    public function nodeDelete(){
        $id = I('id',0,'intval');
        if(0 == $id){
            $this->error(L('CONT_SYS_ERROR_SPECIFY_RECORD'));
        }
        $Node = M('Node');
        if($result = $Node->where(array('pid'=>$id))->find()){
            $this->error(L('CONT_SYS_ERROR_HAVE_CHILD_NODE'));
        }
        if($Node->where(array('id'=>$id))->delete()){
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $this->error($Node->getError());
        }
    }

    // 节点排序
    public function nodeSort(){
        $ids = I('ids',0,'intval');
        $sort = I('sort',0,'intval');
        $Node = M('Node');
        $i = 0;
        while(null != $ids[$i]){
            $where = array(
                'id' => $ids[$i],
            );
            $Node->where($where)->setField('sort',$sort[$i]);
            $i++;
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }

    /*================================================================================================*/
    /*  目录管理（即更新可以用于目录的功能节点）
    /*      功能清单：
    /*          1-catalogItem  ： 目录项更新 - 定制功能节点中哪些可以用于目录项的
    /*          2-catalog      ： 目录定制   - 定制当前的目录项（是目录项的子集）
    /*================================================================================================*/
    // 目录项更新
    public function catalogItem(){
        $catalog = I('catalog',0,'intval');
        $Node = M('Node');
        $nodeIdArr = $Node->getField('id',true);
        $data = array();
        foreach ($catalog as $key => $value) {
            if(0 == $value){
                $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
            }elseif(!in_array($value, $nodeIdArr)){
                $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
            }
            $data[] = $value;
        }
        // 启动事务
        $Node->startTrans();
        // 删除原有目录项
        if(false === $Node->where(array('catalog_item'=>1))->save(array('catalog_item'=>0))){
            $Node->rollback();
            $this->error($Node->getError());
        }
        // 添加新目录项
        if(!empty($data)){
            if(!$Node->where(array('id'=>array('in',$data)))->save(array('catalog_item'=>1))){
                $this->error($Node->getError());
            }
        }
        $Node->commit();
        LogTools::activeLog($data);
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),U('nodeList'));
    }

    // 目录定制
    public function catalog(){
        $Node = M('Node');
        $nodeResult = $Node->field(array('id','title_en','title_zh','pid','level','catalog_show'))->where(array('catalog_item'=>1))->order('sort')->select();
        if(IS_POST){
            $nodeIdArr = array();
            foreach ($nodeResult as $key => $value) {
                $nodeIdArr[] = $value['id'];
            }
            $catalogNode = I('catalogNode',0,'intval');
            $data = array();
            foreach ($catalogNode as $key => $value) {
                if(0 == $value){
                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
                }elseif(!in_array($value, $nodeIdArr)){
                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
                }
                $data[] = $value;
            }
            // 启动事务
            $Node->startTrans();
            // 删除原有目录标记
            if(false === $Node->where(array('catalog_show'=>1))->save(array('catalog_show'=>0))){
                $Node->rollback();
                $this->error($Node->getError());
            }
            // 添加新访问权限记录
            if(!empty($data)){
                if(!$Node->where(array('id'=>array('in',$data)))->save(array('catalog_show'=>1))){
                    $this->error($Node->getError());
                }
            }
            $Node->commit();
            LogTools::activeLog($data);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),U('nodeList'));
        }else{
            foreach($nodeResult as $key => $value){
                $nodeResult[$key]['title'] = 'en-us' == LANG_SET ? $value['title_en'] : $value['title_zh'] ;
            }
            $nodeTree = tree($nodeResult);
            $this->assign('result',$nodeTree);
            $this->display('catalog');
        }
    }
    /*================================================================================================*/
    /*  系统配置管理模块
    /*      功能清单：
    /*          01-configList     ： 浏览系统配置列表
    /*          02-configAdd      ： 后台配置项添加
    /*          03-configUpdate   ： 后台配置项更新
    /*          04-configDelete   ： 后台配置项删除
    /*          05-configSet      ： 配置（对所有项进行整体配置）
    /*          06-configSort     ： 后台配置项排序
    /*================================================================================================*/
    //浏览系统配置列表
    public function configList(){
        $list = $this->lists ( 'Config', '', '`sort` ASC' );
        //转换LIST中的类型type栏位为说明 
        $typeArray = array(
            'type'=>L('CONFIG_TYPE_LIST_TEXT'),
        );
        status_to_string($list,$typeArray);
        //转换LIST中的类型group栏位为说明 
        $groupArray = array(
            'grouping'=>L('CONFIG_GROUP_LIST_TEXT'),
        );
        status_to_string($list,$groupArray);
        foreach ($list as $key => $value) {
            if('en-us' == LANG_SET){
                $list[$key]['title'] = $value['title_en'];
            }else{
                $list[$key]['title'] = $value['title_zh'];
            }
        }
        $this->assign('list',$list);
        // 记录当前列表页的cookie
        Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
        $this->display('configList');
    }


    //后台配置添加
    public function configAdd(){
        if(IS_POST){
            // 类型取值
            // '0' => '数字',
            // '1' => '字符',
            // '2' => '文本',
            // '3' => '数组',
            // '4' => '枚举',
            $name = I('name');
            $type = I('type');
            $extra = I('extra');
            $title_en = I('title_en');
            $title_zh = I('title_zh');
            $grouping = I('grouping');
            $value = I('value');
            $remark_en = I('remark_en');
            $remark_zh = I('remark_zh');
            $sort = I('sort',0,'intval');
            // 输入检查
            if('' == $name){
                $this->error(L('CONT_SYS_ERROR_CONF_NAME_MUST_INPUT'));
            }else{
                if(!ereg( "^[A-Z_]+$",$name)){
                    $this->error(L('CONT_SYS_ERROR_CONFIG_NAME'));
                }
            }
            if('' == $type){
                $this->error(L('CONT_SYS_ERROR_CONF_TYPE_MUST_INPUT'));
            }
            if('' == $title_en || '' == $title_zh){
                $this->error(L('CONT_SYS_ERROR_CONF_TITLE_MUST_INPUT'));
            }
            if('' == $grouping){
                $this->error(L('CONT_SYS_ERROR_CONF_GROUP_MUST_INPUT'));
            }elseif(!array_key_exists($grouping,C('CONFIG_GROUP_LIST'))){
                $this->error(L('CONT_SYS_ERROR_CONF_GROUP_INPUT_ERR'));
            }
            if('' == $value){
                $this->error(L('CONT_SYS_ERROR_CONF_VALUE_MUST_INPUT'));
            }
            switch ($type) {
                case '0':
                    if(!is_numeric($value)){
                        $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_NUM'));
                    }
                    break;
                case '1':
                    if(!ereg( "^[A-Za-z0-9]+$",$value)){
                        $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_ENG'));
                    }
                    break;
                case '2':
                    # ...
                    break;
                case '3':
                    # ...
                    break;
                case '4':
                    if('' == $extra){
                        $this->error(L('CONT_SYS_ERROR_ENUM_MUST_INPUT'));
                    }
                    $selectArr = parse_config_attr($extra);
                    if(!array_key_exists($value, $selectArr)){
                        $this->error(L('CONT_SYS_ERROR_ENUM_BEYOND_OPT'));
                    }
                    break;
                default:
                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
                    break;
            }
            $Config = D('Config');
            if($Config->where(array('name'=>$name))->count() > 0){
                $this->error(L('TEMP_SYSTEM_CONF_NAME').','.L('SYSTEM_ERROR_RECORD_EXIST'));
            }
            $data = array(
                'name' => $name,
                'title_en' => $title_en,
                'title_zh' => $title_zh,
                'grouping' => $grouping,
                'type' => $type,
                'extra' => $extra,
                'value' => $value,
                'remark_en' => $remark_en,
                'remark_zh' => $remark_zh,
                'sort' => $sort,
                'create_time' => NOW_TIME,
                'update_time' => NOW_TIME,
            );
            if($Config->create($data)){
                if($confId = $Config->add()){
                    S ( 'DB_CONFIG_DATA', null );
                    $data['id'] = $confId;
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }else{
                    $this->error($Config->getError());
                }
            }else{
                $this->error($Config->getError());
            }
        }else{
            $this->assign('func','A');
            $this->display('configUpdate'); // 输出模板
        }
    }

    //后台配置更新
    public function configUpdate(){
        $id = I('id',0,'intval');
        $Config = M('Config');
        if(0 == $id){
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
        }elseif(!is_array($result = $Config->find($id))){
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
        }
        if(IS_POST){
            $name = I('name');
            $type = I('type');
            $extra = I('extra');
            $title_en = I('title_en');
            $title_zh = I('title_zh');
            $grouping = I('grouping');
            $value = I('value');
            $remark_en = I('remark_en');
            $remark_zh = I('remark_zh');
            $sort = I('sort',0,'intval');
            // 输入检查
            if('' == $name){
                $this->error(L('CONT_SYS_ERROR_CONF_NAME_MUST_INPUT'));
            }else{
                if(!ereg( "^[A-Z_]+$",$name)){
                    $this->error(L('CONT_SYS_ERROR_CONFIG_NAME'));
                }
            }
            if('' == $type){
                $this->error(L('CONT_SYS_ERROR_CONF_TYPE_MUST_INPUT'));
            }
            if('' == $title_en || '' == $title_zh){
                $this->error(L('CONT_SYS_ERROR_CONF_TITLE_MUST_INPUT'));
            }
            if('' == $grouping){
                $this->error(L('CONT_SYS_ERROR_CONF_GROUP_MUST_INPUT'));
            }elseif(!array_key_exists($grouping,C('CONFIG_GROUP_LIST'))){
                $this->error(L('CONT_SYS_ERROR_CONF_GROUP_INPUT_ERR'));
            }
            if('' == $value){
                $this->error(L('CONT_SYS_ERROR_CONF_VALUE_MUST_INPUT'));
            }
            switch ($type) {
                case '0':
                    if(!is_numeric($value)){
                        $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_NUM'));
                    }
                    break;
                case '1':
                    if(!ereg( "^[A-Za-z0-9]+$",$value)){
                        $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_ENG'));
                    }
                    break;
                case '2':
                    # ...
                    break;
                case '3':
                    # ...
                    break;
                case '4':
                    if('' == $extra){
                        $this->error(L('CONT_SYS_ERROR_ENUM_MUST_INPUT'));
                    }
                    $selectArr = parse_config_attr($extra);
                    if(!array_key_exists($value, $selectArr)){
                        $this->error(L('CONT_SYS_ERROR_ENUM_BEYOND_OPT'));
                    }
                    break;
                default:
                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1003');
                    break;
            }
            $Config = D('Config');
            if($Config->where(array('id'=>array('NEQ',$id),'name'=>$name))->count() > 0){
                $this->error(L('TEMP_SYSTEM_CONF_NAME').','.L('SYSTEM_ERROR_RECORD_EXIST'));
            }
            $data = array(
                'id' => $id,
                'name' => $name,
                'title_en' => $title_en,
                'title_zh' => $title_zh,
                'grouping' => $grouping,
                'type' => $type,
                'extra' => $extra,
                'value' => $value,
                'remark_en' => $remark_en,
                'remark_zh' => $remark_zh,
                'sort' => $sort,
                'update_time' => NOW_TIME,
            );
            if($Config->create($data)){
                if($Config->save()){
                    S ( 'DB_CONFIG_DATA', null );
                    LogTools::activeLog($data);
                    $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
                }else{
                    $this->error($Config->getError());
                }
            }else{
                $this->error($Config->getError());
            }
        }else{
            $this->assign('result',$result);
            $this->display('configUpdate'); // 输出模板
        }
    }

    // 删除配置项
    public function configDelete(){
        $id = I('id',0,'intval');
        $Config = M('Config');
        if(0 == $id){
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
        }elseif(!is_array($result = $Config->find($id))){
            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
        }
        if($Config->delete($id)){
            S ( 'DB_CONFIG_DATA', null );
            LogTools::activeLog($result);
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'), Cookie('__forward__'));
        }else{
            $this->error($Config->getError());
        }
    }

    //配置
    public function configSet(){
        if(IS_POST){
            $item = I('config');
            $Config = D('Config');
            $Config->startTrans();
            foreach ($item as $key => $value) {
                if(!is_array($result = $Config->where(array('name'=>$key))->find())){
                    $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1001');
                }
                if($value != $result['value']){
                    switch ($result['type']) {
                        case '0':
                            if(!is_numeric($value)){
                                $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_NUM'));
                            }
                            break;
                        case '1':
                            if(!ereg( "^[A-Za-z0-9]+$",$value)){
                                $this->error(L('CONT_SYS_ERROR_CONF_VAL_MUST_ENG'));
                            }
                            break;
                        case '2':
                        case '3':
                            break;
                        case '4':
                            $selectArr = parse_config_attr($result['extra']);
                            if(!array_key_exists($value, $selectArr)){
                                $this->error(L('CONT_SYS_ERROR_ENUM_BEYOND_OPT'));
                            }
                            break;
                        default:
                            $this->error(L('SYSTEM_MESSAGE_SCREEN_ERR').'1002');
                            break;
                    }
                    $data = array(
                        'id' => $result['id'],
                        'value' => $value,
                        'update_time' => NOW_TIME,
                    );
                    if(!$Config->save($data)){
                        $Config->rollback();
                    }
                }
            }
            $Config->commit();
            LogTools::activeLog($data);
            S ( 'DB_CONFIG_DATA', null );
            $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
        }else{
            $grouping = I('grouping');
            // 如果没有输入，则取分组配置中的首个元素的KEY值（没有找到更好的方法）
            if("" == $grouping){
                $groupingArr = C('CONFIG_GROUP_LIST');
                foreach ($groupingArr as $key => $value){
                    $grouping = $key;
                    break;
                }
            }
            $Config = M('Config');
            $list = $Config->where(array('grouping'=>$grouping))->order('sort')->select();
            foreach ($list as $key => $value) {
                if('en-us' == LANG_SET){
                    $list[$key]['title'] = $value['title_en'];
                    $list[$key]['remark'] = $value['remark_en'];
                }else{
                    $list[$key]['title'] = $value['title_zh'];
                    $list[$key]['remark'] = $value['remark_zh'];
                }
            }
            // 记录当前列表页的cookie
            Cookie ( '__forward__', $_SERVER ['REQUEST_URI'] );
            $this->assign('grouping',$grouping);
            $this->assign('list',$list);
            $this->display('configSet');
        }
    }

    //配置项排序
    public function configSort(){
        $ids = I('ids',0,'intval');
        $sort = I('sort',0,'intval');
        $Config = D('Config');
        $i = 0;
        while(null != $ids[$i]){
            $where = array(
                'id' => $ids[$i],
            );
            $Config->where($where)->setField('sort',$sort[$i]);
            $i++;
        }
        $this->success(L('SYSTEM_MESSAGE_SUCCESS'),Cookie('__forward__'));
    }
}