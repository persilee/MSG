<?php
namespace Admin\Model;
use Think\Model\RelationModel;

/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 2015/8/7
 * Time: 13:03
 */

class EmpModel extends RelationModel{

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('login_count',0,self::MODEL_INSERT),
        array('login_switch',1,self::MODEL_INSERT),
    );


    /**
     * 检测用户信息
     * @param  string  $field  输入栏位
     * @param  integer $type   用户名类型 1-用户邮箱，2-用户电话，3-昵称
     * @return integer         错误编号
     */
    public function loginCheck($field, $type = 1){
        $where = array();
        switch ($type) {
            case 1:
                $where['email'] = $field;
                break;
            case 2:
                $where['mobile'] = $field;
                break;
            case 3:
                $where['nickname'] = $field;
                break;
            default:
                return 0; //参数错误
        }
        return $this->where($where)->find();
    }

    /**
     * 记录最后登录时间，登录总次数
     * @param  integer  $id  用户ID
     * @return 
     */
    public function loginRegister($id){
        $where = array();
        $where['id'] = $id;
        $data = $this->where($where)->find();
        $data['last_login_time'] = NOW_TIME;
        $data['login_count'] ++;
        $data['pwd_err_count'] = 0;
        return $this->save($data);
    }
}