<?php
namespace Admin\Model;
use Think\Model\ViewModel;

/**
 * Created by Sublime.
 * User: Hipr
 * Date: 2015/12/08
 * Time: 13:03
 */

class RefuseViewModel extends ViewModel{

    public $viewFields = array(
        'Mailrefuse' => array('mail','time','emp_id','_type'=>'LEFT'),
        'Emp' => array(
            'name' => 'empname',
            '_on'  => 'Mailrefuse.emp_id=Emp.id',
        ),
    );
}