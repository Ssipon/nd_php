<?php
/**
 * Cache规则的独立配置
 * @author leicc<355108@nd.com>
 */

if (substr($_SERVER['SERVER_ADDR'], 0, 3) == '192' || substr($_SERVER['SERVER_ADDR'], 0, 3) == '127'){
		return array(
			    'cache'		=> array(
				'File'      => 'db/',
				'Memcache'  => array(
					array('host'=>'192.168.62.131', 'port'=>11211)
				),
			)
		);
}else{
		return array(
			    'cache'		=> array(
				'File'      => 'db/',
				'Memcache'  => array(
					array('host'=>'121.207.254.202', 'port'=>11211)
				),
			)
		);
}