<?php
namespace Common\Common;

/**
 * Created by Sublime.
 * User: Hipr
 * Date: 2015/10/25
 * Time: 10:19
 */

class Catalog{
    /**
     * 生成目录文件
     * @parm string $app 应用名称
     */
    public function generation($app = 'admin'){
        // 根据个人权限取得有权限的节点ID数组
        $Access = M('Access');
        $AccessNodeIdArr = $Access->where(array('role_id'=>array('in',get_current_roleid())))->getField('node_id',true);
        if($AccessNodeIdArr) {
            // 读取节点信息中在目录中显示的内容
            $Node = M('Node');
            $appID = $Node->where(array('name' => $app, 'level' => 1))->getField('id');

            $fieldArray = array(
                'id',
                'name',
                'title_en',
                'title_zh',
                'icon',
                'pid',
            );
            $nodeResule = $Node->where(array('catalog_item' => 1, 'catalog_show' => 1, 'id' => array('in', $AccessNodeIdArr)))->order('sort')->field($fieldArray)->select();
            // $nodeArr = array();
            foreach ($nodeResule as $key => $value) {
                $nodeResule[$key]['title'] = 'en-us' == LANG_SET ? $value['title_en'] : $value['title_zh'];
            }
            return tree(getChildTree($nodeResule), $appID);
        }else{
            return array();
        }
    }
}
