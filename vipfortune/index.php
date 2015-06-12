<?php
// ini_set('display_errors', 1);
// error_reporting(2047);
// 控制是否始于开发模式便于SQL输出调试   
define('WEBROOT', dirname(__FILE__));
if (substr($_SERVER['SERVER_ADDR'], 0, 3) == '192' || substr($_SERVER['SERVER_ADDR'], 0, 3) == '127'){
	define('COREDIR', dirname(dirname(__FILE__)).'/newlib/');
} else {
	define('COREDIR', '/data/wwwroot/event.co.91.com/webroot/newlib/');
}
include(COREDIR.'bootstrap.inc.php'); 
try {
	ob_start();
	App::run();
} catch (Exception $e) {
	View::getInst('smarty')->assign('code', $e->getCode());
    View::getInst('smarty')->assign('msg', $e->getMessage());
    View::getInst('smarty')->display('error/index.tpl', 0);
}
