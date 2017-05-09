<?php

namespace Admin\Controller;
use Think\Controller;
class PublicController extends Controller{

	public function login(){

		if (IS_POST) {
			$post = I('post.');
			$post['mg_pwd'] = getPwd($post['mg_pwd']);
		
			$info = M('Manager') -> where($post) -> find();
//			echo "<pre>";
//			print_r($post);
//			print_r($info);
//			die;
			if($info){ 
				session('mg_id',$info['mg_id']);
				session('mg_name',$info['mg_name']);
				session('mg_time',$info['mg_time']);
				session('role_id',$info['role_id']);
				
				M('Manager')->save(array('mg_id' => $info['mg_id'],'mg_time' => time()));
				$this->success('登录成功...',U('Index/index'),3);
			}else{
				$this->error('用户名或密码错误');
			}

		}else{
            session_destroy();
			//展示模版
			$this -> display();
		}
		
	}
}