<?php
namespace Admin\Model;
use Think\Model\ViewModel;

/**
 * Created by Sublime.
 * User: Hipr
 * Date: 2015/12/08
 * Time: 13:03
 */

class AppoveViewModel extends ViewModel{

    public $viewFields = array(
        'Appove' => array('date','seq','maker','type','func','reference','time','content','_type'=>'LEFT'),
        'Emp' => array(
            'name' => 'makername',
            '_on'  => 'Appove.maker=Emp.id',
        ),
    );
}