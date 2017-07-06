<?php
namespace Admin\Model;
use Think\Model\RelationModel;

/**
 * Created by Sublime Text.
 * User: Hipr
 * Date: 2015/9/27
 * Time: 13:03
 */

class AddressModel extends RelationModel{
   
    //关联查询
    protected $_link = array(

        // 客户地址关联模型
        'Client'  => array(
            'mapping_type' => self::BELONGS_TO, 
            'class_name' => 'Client',
            'foreign_key' => 'ci_no',
            'as_fields' => 'name:ci_name',
        ),
    );
}