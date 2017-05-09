<?php
/**
 * @Author 黑马程序员-传智播客旗下高端教育品牌 [itcast.cn]
 * @Date    2017-02-24 17:11:09
 * @Version 1.0.0
 * @Description 函数库文件
 */

//用于过滤XSS攻击
function filterXSS($string){
    //相对index.php入口文件，引入HTMLPurifier.auto.php核心文件
    require_once './Public/Admin/htmlpurifier/HTMLPurifier.auto.php';
    // 生成配置对象
    $cfg = HTMLPurifier_Config::createDefault();
    // 以下就是配置：
    $cfg -> set('Core.Encoding', 'UTF-8');
    // 设置允许使用的HTML标签
    $cfg -> set('HTML.Allowed','div,b,strong,i,em,a[href|title],ul,ol,li,br,p[style],span[style],img[width|height|alt|src]');
    // 设置允许出现的CSS样式属性
    $cfg -> set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align');
    // 设置a标签上是否允许使用target="_blank"
    $cfg -> set('HTML.TargetBlank', TRUE);
    // 使用配置生成过滤用的对象
    $obj = new HTMLPurifier($cfg);
    // 过滤字符串
    return $obj -> purify($string);
}
function getPwd($pwd){
    $key = substr(sha1('ych'),9,20);
    $pwd = substr(sha1($pwd),19,10);
    return sha1($key.$pwd);
}
function getTree($list,$pid=0,$level=0){
    static $tree= array();
    foreach ($list as $row){
        if ($row['auth_pid'] == $pid){
            $row['level'] = $level;
            $tree[]=$row;
            getTree($list,$row['auth_id'],$level + 1);
        }
    }
    return $tree;
}
