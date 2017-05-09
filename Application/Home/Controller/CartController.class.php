<?php
/**
 * @Author 黑马程序员-传智播客旗下高端教育品牌 [itcast.cn]
 * @Date    2017-03-04 10:48:41
 * @Version 1.0.0
 * @Description 购物车控制器
 */

namespace Home\Controller;
use Think\Controller;
use Tools\Cart;	//引入购物车类元素
class CartController extends Controller{

	//总计的信息
	private $_total = '';

	public function __construct(){
		parent::__construct();
		$cart = new Cart();
		$this -> _total = $cart -> getNumberPrice();
	}

	//测试方法
	public function test(){
		$cart = new Cart();
		dump($cart);
	}

	//商品添加操作
	public function add(){
		if(IS_AJAX){
			//接收id
			$goods_id = I('post.goods_id');
			//查询数据表获取商品的名称和价格
			$info = M('Goods') -> field('goods_name,goods_price') -> find($goods_id);
			//M('Goods') -> where("goods_id = $goods_id") -> getField('goods_name,goods_price,go');
			//array('goods_id'=>'10','goods_name'=>'诺基亚','goods_price'=>'1750','goods_buy_number'=>'1','goods_total_price'=>1750);
			$goods = array(
					'goods_id'				=>	$goods_id,
					'goods_name'			=>	$info['goods_name'],
					'goods_price'			=>	$info['goods_price'] * 0.5,
					'goods_buy_number'		=>	$_POST['amount'],
					'goods_total_price'		=>	$_POST['amount'] * $info['goods_price'] * 0.5
					);
			//实例化购物车类
			$cart = new Cart();
			//添加
			$cart -> add($goods);
			//获取总价和数量
			$data = $cart -> getNumberPrice();
			//输出json
			$this -> ajaxReturn($data);
		}
	}

	//展示购物车
	public function flow1(){
		//查询信息
		$cart = new Cart();
		//获取购物车信息的数据
		$data = $cart -> getCartInfo();
		//获取全部的key（商品id）
		$goods_ids = implode(',',array_keys($data));
		//查询商品信息
		$goods = M('Goods') -> field('goods_id,goods_small_logo') -> where("goods_id in ($goods_ids)") -> select();
		//嵌套循环遍历
		foreach ($data as $key => $value) {
			#key表示商品goods_id,value里面的goods_id
			foreach ($goods as $k => $v) {
				//判断关联性
				if($key == $v['goods_id']){
					$data[$key]['goods_small_logo'] = ltrim($v['goods_small_logo'],'.');
				}
			}
		}
		//获取商品的总的价格
		$total = $cart -> getNumberPrice();
		//变量分配
		$this -> assign('data',$data);
		$this -> assign('total',$total);
		//展示模版
		$this -> display();
	}

	//购物车商品的删除操作
	public function del(){
		//实例化购物车类
		$cart = new Cart();
		//执行删除
		$goods_id = I('post.goods_id');
		$cart -> del($goods_id);
		//问：没有返回值怎么判断是否成功？可以拿商品id去商品信息中比对
		$allGoods = $cart -> getCartInfo();
		if(array_key_exists($goods_id, $allGoods)){
			//如果存在则说明没有删除成功
			$result = array(
					'errorCode'		=>	'1',
					'errorText'		=>	'删除失败'
				);
		}else{
			//不存在，则表示删除成功
			$total = $cart -> getNumberPrice();
			$result = array(
					'errorCode'		=>	'0',
					'errorText'		=>	$total['price']
				);
		}
		//输出数据
		$this -> ajaxReturn($result);
	}

	//更改商品的数量
	public function change_number(){
		if(IS_AJAX){
			$get = I('get.');
			//实例化
			$cart = new Cart();
			//修改数量，返回商品的小计价格
			$goods_total_price = $cart -> changeNumber($get['amount'],$get['goods_id']);
			//获取总计的价格
			//$price = $this -> _total['price']
			$total = $cart -> getNumberPrice();
			//组成数据
			$data = array(
						'goods_total_price'			=> $goods_total_price,
						'price'						=> $total['price']
				);
			//输出
			$this -> ajaxReturn($data);
		}
	}

	//订单确认页面
	public function flow2(){
		//此处需要登录判断
		if(!session('?user_id')){
			//让用户去登录
			$this -> error('请先登录...',U('User/login',array('tc' => 'Cart','ta' => 'flow2')),3);exit;
		}
		//结算页面的数据展示
		$cart = new Cart();
		//查询购物车的信息
		$data = $cart -> getCartInfo();
		//拼凑主键
		$goods_ids = implode(',', array_keys($data));
		//查询购物车中全部的商品的缩略图
		$thumbs = M('Goods') -> field('goods_id,goods_small_logo') -> where("goods_id in ($goods_ids)") -> select();
		//嵌套循环关联数组
		foreach ($data as $key => $value) {
			#key表示商品id
			foreach ($thumbs as $k => $v) {
				//合并数据的条件
				if($key == $v['goods_id']){
					$data[$key]['goods_small_logo'] = ltrim($v['goods_small_logo'],'.');
				}
			}
		}
		//获取总价和数量
		$total = $cart -> getNumberPrice();
		//变量分配
		$this -> assign('data',$data);
		$this -> assign('total',$total);
		//展示模版
		$this -> display();
	}
}
