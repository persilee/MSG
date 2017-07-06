<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/9/22
 * Time: 10:32
 */


/**
 * 取得部门树列表
 *
 * @return list
 */
function get_deptTree() {
    //从缓存取得部门树
    $deptTree = S('deptTree');
    //没有取到，则浏览部门表取得列表数据
    if(null == $deptTree){
        $Dept = M('Dept');
        // $deptList = $Dept->order('sort ASC')->select();
        $deptList = $Dept->order('sort ASC')->field(array('id','name','pid'))->select();
        $deptTree = levelTree($deptList);
        //设置部门缓存列表
        S('deptTree',$deptTree,24*3600);
    }
    //返回部门树
    return $deptTree;
}

/**
 * 删除部门树列表，通常用于新增或者修改了部门信息之后调用
 *
 */
function delete_deptTree(){
    S('deptTree',null);
}

/**
 * 取得职位树列表
 *
 * @return list
 */
function get_roleTree() {
    //从缓存取得部门树
    // $postTree = S('postTree');
    //没有取到，则浏览部门表取得列表数据
    // if(null == $postTree){
        $Role = M('Role');
        $roleList = $Role->order('sort ASC')->field(array('id','name','pid'))->select();
        $roleTree = levelTree($roleList);
        //设置部门缓存列表
        // S('postTree',$postTree,24*3600);
    // }
    //返回职位树
    return $roleTree;
}