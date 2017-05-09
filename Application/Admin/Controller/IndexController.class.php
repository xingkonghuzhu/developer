<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends CommonController {

    public function index(){
        $this -> display();
    }

    public function top(){
        $this -> display();
    }

    public function left(){
        $role_id=session('role_id');
        if ($role_id == '1') {
            $top = M('Auth')->where("auth_pid = 0 and is_nav = '1'")->select();
            $cate=M('Auth')->where("auth_pid != 0 and is_nav = '1'")->select();
        }else{
            $roleInfo = M('Role')->find($role_id);
            $top = M('Auth')->where("auth_pid = 0 and is_nav = '1' and auth_id in({$roleInfo['role_auth_ids']})")->select();
            $cate = M('Auth')->where("auth_pid !=0 and is_nav = '1' and auth_id in({$roleInfo['role_auth_ids']})")->select();
        }

        $this->assign('top',$top);
        $this->assign('cate',$cate);
        $this -> display();
    }

    public function main(){
        if(IS_POST){
            $username=I('post.user_name');
            $data=M('User')->where("user_name = '$username'")->select();
        }else{
            $data=M('User')->select();

        }
        $this->assign('data',$data);
        $this->display();

    }
}