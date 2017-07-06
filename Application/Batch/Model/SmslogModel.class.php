<?php
namespace Batch\Model;
use Think\Model\RelationModel;

/**
 * Created by atom.
 * User: per
 * Date: 2017/6/14
 * Time: 11:03
 */

class SmslogModel extends RelationModel{

    //关联查询
    protected $_link = array(

        // 邮件LOG关联模型
        'Emp'  => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Emp',
            'foreign_key' => 'emp_id',
            'as_fields' => 'name:emp_name',
        ),

        'Smstpl'  => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Smstpl',
            'foreign_key' => 'smstpl_id',
            'as_fields' => 'name:smstpl_name',
        ),

        'Client'  => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Client',
            'foreign_key' => 'ci_no',
            'as_fields' => 'name:ci_name',
        ),
    );
}
