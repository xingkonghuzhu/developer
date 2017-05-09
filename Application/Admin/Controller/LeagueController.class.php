<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/7
 * Time: 16:35
 */

namespace Admin\Controller;


class LeagueController extends CommonController
{
    public function index(){
        if(IS_POST){
            $post=I('post.');
           $post=array_filter($post);

            $data=M('League')->where($post)->select();
        }else{
            $data=M('League')->select();
        }
        $this->assign('data',$data);
        $this->display('showList');
    }
    public function add(){
        if(IS_POST){
            $post=I('post.');
            $post['le_time']=time();
//            echo "<pre>";
//            print_r($post);
//            die;
            $res=M('League')->add($post);
            if($res){
                $this->success('添加成功',U('index'),3);
            }else{
                $this->error('添加失败');
            }

        }else{
            $this->display();
        }

    }
    public function detail(){
        $get=I('get.le_id');
        $data=M('league')->find($get);
        $this->assign('data',$data);
        $this->display();
    }
}