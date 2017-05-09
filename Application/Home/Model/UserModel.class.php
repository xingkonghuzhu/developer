<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/2
 * Time: 15:43
 */
namespace  Home\Model;
use Think\Model;
class UserModel extends Model{
    protected $_validate = array(
      array('user_name','require','用户名不能为空'),
      array('user_name','','用户名已经存在',0,'unique'),
      array('user_pwd','require','密码不能为空'),
      array('user_pwd','user_pwdr','两次密码输入不一致',0,'confirm'),


    );
}