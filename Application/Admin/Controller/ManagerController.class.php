<?php
namespace Admin\Controller;
use Think\Controller;
class ManagerController extends CommonController{
	public function showlist(){
		$data = M('Manager') -> select();
		$this->assign('data',$data);
		$this->display();
	}
}