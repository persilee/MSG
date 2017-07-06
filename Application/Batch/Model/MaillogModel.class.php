<?php
namespace Batch\Model;
use Think\Model\RelationModel;

/**
 * Created by atom.
 * User: per
 * Date: 2017/6/27
 * Time: 13:03
 */

class MaillogModel extends RelationModel{
    //关联查询
    protected $_link = array(

        // 邮件LOG关联模型
        'Emp'  => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Emp',
            'foreign_key' => 'emp_id',
            'as_fields' => 'name:emp_name',
        ),

        'Mailtpl'  => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Mailtpl',
            'foreign_key' => 'mailtpl_id',
            'as_fields' => 'name:mailtpl_name',
        ),

        'Client'  => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Client',
            'foreign_key' => 'ci_no',
            'as_fields' => 'name:ci_name',
        ),
    );
}
