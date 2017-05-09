<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/28
 * Time: 16:33
 */

namespace Admin\Model;

use Think\Model;
class GoodsattrModel extends Model{
    public function saveAttr($goods_id,$attr_vals){
        $data=array();
        $i = 0;
        foreach ($attr_vals as $k => $v){
            $data[$i]['goods_id'] = $goods_id;
            $data[$i]['attr_id'] = $k;
            $data[$i]['attr_value'] = implode(',',$v);
            $i++;
        }
        return $this->addAll($data);
    }
}