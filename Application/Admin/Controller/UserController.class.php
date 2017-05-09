<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/5
 * Time: 10:24
 */

namespace Admin\Controller;


class UserController extends CommonController{
    public function index(){
        if(IS_POST){
            $username=I('post.user_name');
            $data=M('User')->where("user_name = '$username'")->select();
        }else{
            $data=M('User')->select();

        }
        $this->assign('data',$data);
        $this->display('showList');
    }
    public function modify(){
        print_r($_GET);

    }
    public  function delOne(){
        print_r($_GET);
    }
}


