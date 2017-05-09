<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2017/4/26
 * Time: 11:49
 */

namespace Admin\Model;
use Think\Model;

class RoleModel extends Model{
    public function saveRoleAuth($role_id,$post){
        //将提交的auth_id 整理成字符串 ‘，’
        $data['role_auth_ids'] = implode(',',$post['auth_id']);
        //存储修改对象的角色ID
        $data['role_id']=$role_id;
        //找出权限id所对应的控制器-方法信息
        $auth=M('Auth')->where("auth_pid != 0 and auth_id in({$data['role_auth_ids']})")->select();
        //定义空白容器 方便存储权限控制器相关信息
        $ac='';
        foreach ($auth as $key => $value){
            $ac.=$value['auth_c'].'-'.$value['auth_a'].',';
        }
        //将存储的控制器信息更新上传到数据库
        $data['role_auth_ac']=rtrim($ac,',');
        return $this->save($data);
    }
}