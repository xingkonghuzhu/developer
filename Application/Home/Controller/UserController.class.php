<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/2
 * Time: 10:50
 */

namespace Home\Controller;


use Think\Controller;

class UserController extends Controller
{
    public function register(){
        if(IS_POST){
            $user=D('User');
            $data=$user->create();
            if($data){
                $data['user_pwd'] = getPwd($data['user_pwd']);
                $data['user_sex'] = '保密';
                $data['user_check'] = '1';
                $data['add_time'] = time();
                $result=$user->add($data);
                if($result){
                    $this->success('成功注册',U('login'),3);
                }else{
                    $this->error('注册失败');
                }
            }else{
                $this->error($user->getError());
            }
        }else{
            $this->display();
        }

    }
    public function login(){
        if(IS_POST){
            $post=I('post.');
            $post['user_pwd'] = getPwd($post['user_pwd']);
            $res=M('user')->where($post)->find();
            if($res){
                session('user_id',$res['user_id']);
                session('user_name',$res['user_name']);
                $this->success('登录成功',U('Index/index'),3);
            }else{
                $this->error('用户名或者密码验证不通过');
            }
        }else{
            $this->display();
        }

    }
    public function loginOut(){
        session(null);
        $this->success('退出登录状态成功',U('login'),3);
    }
    public function _initialize(){
        if(strtolower(ACTION_NAME) == 'login' || strtolower(ACTION_NAME) == 'register'){
            if(session('?user_id')){
                $this->error('当前已是登录状态',U('Index/index'),3);exit;
            }
        }
    }
}