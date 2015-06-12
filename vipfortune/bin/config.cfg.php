<?php
define('WEBROOT', dirname(dirname(__FILE__)));
if (substr($_SERVER['SERVER_ADDR'], 0, 3) == '192' || substr($_SERVER['SERVER_ADDR'], 0, 3) == '127'){
	define('COREDIR', dirname(dirname(dirname(__FILE__))).'/ndlib/1.0/lib/');
} else {
	define('COREDIR', '/data/wwwroot/ndlib.99.com/1.0/lib/');
}
include(COREDIR.'bootstrap.inc.php'); 
ob_start();
App::init();
/*if(php_sapi_name() != 'cli' && !Acl::checkLogin()) {
	exit('禁止远程调用执行脚本');
}*/