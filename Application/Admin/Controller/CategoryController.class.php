<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/27
 * Time: 11:46
 */

namespace Admin\Controller;


class CategoryController extends CommonController{
   public function showList(){
       $data=M('Type')->select();
       $this->assign('data',$data);

       $this->display();
   }
   public function  add(){
       if(IS_POST){
           $post=I('post.');

           $res=M('Type')->add($post);
           if($res){
               $this->success('商品分类添加成功',U('showList'),3);
           }else{
               $this->error('商品分类添加失败');
           }

       }else{

           $data=M('Type')->select();
           $this->assign('data',$data);
           $this->display();
       }

   }
}