<?php
namespace Admin\Controller;
use Think\Controller;
class AuthController extends CommonController{
	public function showlist(){
		$data = M('Auth') -> select();
        $data=getTree($data);
		$this->assign('data',$data);
		$this->display();
	}
	public function add(){
        if(IS_POST){
            $post=I('post.');
            $res=M('Auth')->add($post);
            if($res){
                $this->success('添加成功',U('showList'),3);
            }else{
                $this->error('添加失败');
            }
        }else{
            $top=M('Auth')->where('auth_pid = 0')->select();
            $this->assign('top',$top);
            $this->display();
        }
    }
}