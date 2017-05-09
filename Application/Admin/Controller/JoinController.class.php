<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/5/5
 * Time: 13:14
 */

namespace Admin\Controller;


class JoinController extends CommonController
{
    public function index()
    {
        if(IS_POST){
            $post=I('post.');
            foreach($post as $k => $v){
                if (!$v) {
                    unset($post[$k]);
                }
            }

            $data=M('Join')->where($post)->select();

        }else{
            $data = M('Join')->select();
        }
        $this->assign('data', $data);
        $this->display('showList');
    }
    public  function detail(){
        if(IS_POST){
            $post=I('post.');

            $res=M('Join')->save($post);

            if($res){
                $this->success('更新数据成功',U('index'),3);
            }else{
                $this->error('更新数据失败');
            }
        }else{
            $get=I('get.');
            $data=M('Join')->find($get);
            $this->assign('data',$data);
            $this->display();
        }


    }

}