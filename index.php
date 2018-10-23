<?php
if (!session_id()) {
	session_start();
}
date_default_timezone_set('Asia/Shanghai');
define('PATH','./');
define('APP','application');
define('CONTROLLER' , 'controller');
define('VIEW' , 'view');
define('HTTP_HOST', isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'');

include PATH . 'system/laysen.php';
spl_autoload_register('\laysen\main::load');
\laysen\main::run();

