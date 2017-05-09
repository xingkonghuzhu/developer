<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends CommonController{
	public function showList(){
		$data = M('Role') -> select();
		$this->assign('data',$data);
		$this->display();
	}
	public function setAuth(){
        $role_id = I('get.role_id');
        if(IS_POST){
            $post=I('post.');
           $result=D('Role')->saveRoleAuth($role_id,$post);
            if ($result){
                $this->success('权限分配成功',U('showlist'),3);
            }else{
                $this->error('权限分配失败');
            }
        }else {
            $top = M('Auth')->where("auth_pid = 0")->select();
            $cate = M('Auth')->where("auth_pid != 0")->select();
            $this->assign('top', $top);
            $this->assign('cate', $cate);
            //查询用户角色ID 获取其角色所有权限信息

            $roleInfo = M('Role')->find($role_id);
            $this->assign('roleInfo', $roleInfo);
////        echo "<pre>";
////        print_r($top);
////        print_r($cate);
//          die;
            $this->display();
        }
    }
}