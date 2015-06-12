<?php
if (substr($_SERVER['SERVER_ADDR'], 0, 3) == '192' || substr($_SERVER['SERVER_ADDR'], 0, 3) == '127')
{ 
	return array(
		'reader'	=>	array(
			'options'	 => array( 
	            PDO::ATTR_PERSISTENT	=>false,
	            PDO::ATTR_TIMEOUT		=>3,
	            PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
	        ),
			'params'	=> array(
	            'dbname'	=> 'cozf',
	            'driver'	=> 'pdo',
	            'dsn'		=> 'mysql:host=127.0.0.1;port=3306;dbname=cozf',
	            'user'		=> 'root',
	            'password'	=> '123456'
	        )
		),
		'writer' => 'reader'
	);
}else{ 
	return array(
		'reader'	=>	array(
			'options'	 => array( 
	            PDO::ATTR_PERSISTENT	=>false,
	            PDO::ATTR_TIMEOUT		=>3,
	            PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'
	        ),
			'params'	=> array(
	            'dbname'	=> 'cozf',
	            'driver'	=> 'pdo',
	            'dsn'		=> 'mysql:host=en.activity.com;port=3306;dbname=cozf',
	            'user'		=> 'activity',
	            'password'	=> 'H6wPhazWuFH3Wasm'
	        )
		),
		'writer' => 'reader'
	);
}