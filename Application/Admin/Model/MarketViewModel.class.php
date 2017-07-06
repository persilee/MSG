<?php
namespace Admin\Model;
use Think\Model\ViewModel;

/**
 * Created by PhpStorm.
 * User: per
 * Date: 2017/6/7
 * Time: 13:03
 */

class MarketViewModel extends ViewModel{
  public $viewFields = array(
      'Market' => array('date','seq','title_en','title_zh_s','title_zh_t','update_emp','en_content','zh_s_content','zh_t_content','time','_type'=>'LEFT'),
      'Emp' => array(
          'name' => 'inputname',
          '_on'  => 'Market.update_emp=Emp.id',
      ),
  );
}
