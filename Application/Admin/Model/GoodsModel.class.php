<?php
/**
 * @Author 黑马程序员-传智播客旗下高端教育品牌 [itcast.cn]
 * @Date    2017-02-26 10:14:55
 * @Version 1.0.0
 * @Description 商品管理的模型
 */

//声明命名空间
namespace Admin\Model;
//引入父类元素
use Think\Model;
//声明类并且继承父类
class GoodsModel extends Model{

	//商品添加保存方法
	public function addGoods($post){
		//.......
		//判断是否有文件需要上传处理
		if($_FILES['goods_big_logo']['error'] == '0'){
			//上传类的实例化操作
			$upload = new \Think\Upload();
			//开始上传
			$info = $upload -> uploadOne($_FILES['goods_big_logo']);
			//判断上传结果
			if($info){
				//表示上传成功
				$post['goods_big_logo'] = $upload -> rootPath . $info['savepath'] . $info['savename'];
				//实例化类
				$image = new \Think\Image();
				//打开图片
				$image -> open($post['goods_big_logo']);
				//制作缩略图
				$image -> thumb(50,50);
				//保存图片
				$thumbSavePath = $upload -> rootPath . $info['savepath'] . 'thumb_' . $info['savename'];
				$image -> save($thumbSavePath);
				//补全数据的字段数据
				$post['goods_small_logo'] = $thumbSavePath;
			}
		}
		return $this -> add($post);
	}

	//保存商品相册图片
	public function savePics($goods_id){
		//判断是否有文件上传成功
		$flag = false;
		foreach ($_FILES['goods_pic']['error'] as $key => $value) {
			//判断
			if($value == '0'){
				$flag = true;
				break;
			}
		}
		//如果有文件，则实例化上传，并且做上传处理
		if($flag){
			$upload = new \Think\Upload();
			//上传
			$info = $upload -> upload();
			//判断上传结果
			if($info){
				//成功，继续后续的处理
				$data = array();	//初始化空数组
				$image = new \Think\Image();
				foreach ($info as $k => $v) {
					//补全数据
					$data[$k]['goods_id'] = $goods_id;
					//原图
					$data[$k]['pics_ori'] = $upload -> rootPath . $v['savepath'] . $v['savename'];
					//开始制作缩略图
					$image -> open($data[$k]['pics_ori']);
					//大图800*800
					$image -> thumb(800,800);
					$bigThumbPath = $upload -> rootPath . $v['savepath'] . 'big_' . $v['savename'];
					$image -> save($bigThumbPath);
					//中图350*350
					$image -> thumb(350,350);
					$midThumbPath = $upload -> rootPath . $v['savepath'] . 'mid_' . $v['savename'];
					$image -> save($midThumbPath);
					//小图50*50
					$image -> thumb(50,50);
					$smaThumbPath = $upload -> rootPath . $v['savepath'] . 'sma_' . $v['savename'];
					$image -> save($smaThumbPath);
					//补全数据
					$data[$k]['pics_big'] = $bigThumbPath;
					$data[$k]['pics_mid'] = $midThumbPath;
					$data[$k]['pics_sma'] = $smaThumbPath;
				}
				//将数据写入到数据表中
				return M('Goodspics')  -> addAll($data);
			}
		}
	}
}
