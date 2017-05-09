<?php
return array(
	//'配置项'=>'配置值'

	//针对模版特殊字符串配置
	'TMPL_PARSE_STRING'		=>		array(
				'__HOME__'	=>	'/Public/Home',	//前台自定义特殊字符串
				'__ADMIN__'	=>	'/Public/Admin'	//后台自定义特殊字符串
		),

	//数据库连接配置
	'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'shop',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'root',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'sp_',    // 数据库表前缀
);