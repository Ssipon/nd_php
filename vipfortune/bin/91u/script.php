<?php
include(dirname(dirname(__FILE__)).'/config.cfg.php');
//这里面就是你的自己代码
$list = Account91u::getPersonnelList();
$user = Model::load('SysUser');
foreach($list as $item) {
	$aData = array(
		'name'      => $item['UserId']['value'],
		'real_name' => $item['UserName']['value'],
		'dept'      => $item['DepName']['value'],
		'stime'     => time()
	);
	$aRow = $user->getUserByAccount($aData['name']);
	if($aRow) {
		$user->saveUserById($aRow['id'], $aData);
	} else {
		$aData['status'] = 1;
		$user->insertUser($aData);
	}
	unset($aRow);
}
exit('更新成功.');
