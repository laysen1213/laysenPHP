<?php
return [
	// 路由配置
	'route' => [
		'uri_1' => 'blog',
		'uri_2' => 'index',
		'uri_3' => 'index',
	],
	'mysql' => [
		'host' => 'localhost',
		'name' => 'project',
		'password' => '8B5665784254D8AF2F18E314C4CEAA05',
		'dbname' => 'laysen',
		'port' => 3306,
		'dbprefix' => 'pt_'
	],
	'redis' => [
		'host' => '127.0.0.1',
		'port' => 6379,
		'password' => 'laysen326716'
	],
	'memcache' => [
		'host' => '127.0.0.1',
		'port' => 11211,
		'password' => ''
	],
	'mongo' => [
		'user' => '',
		'apassword' => '',
		'host' => 'localhost',
		'port' => 27017,
		'db' => 'test'
	],
];
