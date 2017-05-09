<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/26
 * Time: 15:08
 */

namespace Admin\Controller;


use Think\Controller;

class CommonController extends Controller{
    //首先继承父类的构造方法
    public function __construct(){
        parent::__construct();
        //增加初步判断是否登录
        if(!session('mg_id')){
//            $this->error('请先登录',U('Public/login'),3);exit;
            $url=U('Public/login');
            echo "<script>top.location.href='$url';</script>";exit;
        }
        //确认登录人的角色信息  相关权限   获取当前想要访问的控制器或者方法  跟登录的角色权限信息比对  确认是否越权操作
        if(session('role_id') != 1){
            $role_id = session('role_id');
            $roleInfo = M('Role')->find($role_id);
            $ac = strtolower(CONTROLLER_NAME.'-'.ACTION_NAME);
            $authlist=strtolower($roleInfo['role_auth_ac'].',Index-index,Index-left,Index-top,Index-main');
            if(strpos($authlist,$ac) === false){
                $this->error('you have no power ,please change user',U('Index/index'),3);exit;
            }
        }
    }
}

