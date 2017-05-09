<?php

//声明命名空间
namespace Admin\Controller;
//引入需要使用的基类元素（类、方法、常量）
use Think\Controller;
class GoodsController extends CommonController{

	//商品添加
	public function add(){
		//判断请求类型
		if(IS_POST){
			//是post请求
			//$post = $_POST;
			$post = I('post.');
//            echo "<pre>";
//            print_r($post);
//            die;
			//针对商品描述特殊处理
			$post['goods_introduce'] = filterXSS($_POST['goods_introduce']);
			//补全时间
			$post['upd_time'] = $post['add_time'] = time();
			//执行数据保存
			//$result = M('Goods') -> add($post);
			$result = D('Goods') ->addGoods($post);
			//判断结果

			if($result){
                D('Goodsattr')->saveAttr($result,$post['attr_vals']);
				$this -> success('商品添加成功',U('showList'),3);
			}else{
				$this -> error('商品添加失败');
			}
		}else{
            $cate=M('Type')->select();
            $this->assign('cate',$cate);

			$this -> display();
		}
	}

	//商品列表 
	public function showList(){
		//获取数据
		$data = M('Goods') -> select();
		//变量分配
		$this -> assign('data',$data);
		$this -> display();
	}

	//相册展示和添加表单 345
	public function photos(){
		//接收商品id
		$goods_id = I('get.goods_id');
		//请求类型判断
		if(IS_POST){
			//说明需要处理表单
			//实例化模型
			$model = D('Goods');
			//调用自定义模型方法处理相册图片的生成
			$result = $model -> savePics($goods_id);
			//判断结果
			if($result){
				$this -> success('相册图片添加成功',U('photos',array('goods_id' => $goods_id)),3);
			}else{
				$this -> error('相册图片添加失败');
			}
		}else{
			//查询
			$data = M('Goodspics') -> where("goods_id = $goods_id") -> select();
			//变量分配
			$this -> assign('data',$data);
			//展示模版
			// print_r($data);
			// die;
			$this -> display();
		}
	}

	//删除相册图片
	public function delPics(){
		//判断是否是ajax请求
		if(IS_AJAX){
			//接收id
			$pics_id = I('get.pics_id');
			//先删除磁盘上的文件
			$data = M('Goodspics') -> find($pics_id);
			unlink($data['pics_ori']);
			unlink($data['pics_big']);
			unlink($data['pics_mid']);
			unlink($data['pics_sma']);
			//删除数据表中记录
			$result = M('Goodspics') -> delete($pics_id);
			//判断删除结果
			if($result){
				echo '0';//成功
			}else{
				echo '1';//失败
			}
		}
	}
	public function getAttr(){

        if(IS_AJAX){

            $typeid=I('get.typeid');
            $data=M('Attribute')->where("type_id = $typeid")->select();
            $this->ajaxReturn($data);
            return $data;
        }else{
            echo "Access Deny";
        }

    }

}