<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/27
 * Time: 14:21
 */

namespace Admin\Controller;

class AttributeController extends CommonController{
    public function showList(){
        $data=M('Attribute')->alias('a')->join('left join sp_type as b on a.type_id = b.type_id')->select();
        $this->assign('data',$data);
//        echo "<pre>";
//        print_r($data);
//        die;
        $this->display();
    }
    public  function  add(){
        if(IS_POST){
            $post=I('post.');
            $res=M('Attribute')->add($post);
            if($res){
                $this->success('商品属性添加成功',U('showList'),3);
            }else{
                $this->error('商品属性添加失败');
            }
        }else{
            $cate=M('Type')->select();
            $this->assign('cate',$cate);
            $this->display();

        }
    }
}