<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/2
 * Time: 10:50
 */

namespace Home\Controller;


use Think\Controller;

class GoodsController extends Controller
{
    public function showList(){
        $data=M('Goods')->where("is_del = '0'")->select();
        $this->assign('data',$data);
        $this->display();
    }
    public function  detail(){
        $goods_id=I('get.goods_id');
        $goodsInfo=M('Goods')->find($goods_id);
        $this->assign('goodsInfo',$goodsInfo);

        $pics=M('Goodspics')->where("goods_id = $goods_id")->select();
        $this->assign('pics',$pics);

        $single=M('Goodsattr')->field('t2.attr_name,t1.attr_value')->alias('t1')->join('left join sp_attribute as t2 on t1.attr_id = t2.attr_id')->where("t2.attr_sel = '0' and  t1.goods_id = $goods_id")->select();
        $this->assign('single',$single);

        $attrch=M('Goodsattr')->field('t2.attr_name,t1.attr_value')->alias('t1')->join('left join sp_attribute as t2 on t1.attr_id = t2.attr_id')->where("t2.attr_sel = '1' and  t1.goods_id = $goods_id")->select();
        foreach ($attrch as $key => $value){
            $attrch[$key]['attr_value'] = explode(',',$value['attr_value']);
        }

        $this->assign('attrch',$attrch);
        $this->display();
    }
}