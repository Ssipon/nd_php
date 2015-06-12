<?php
class LotteryHelper{
	
	public static function exe($prizeAry) {
		$rand = mt_rand(1,100);
		$prizeid = -1;
	
		foreach($prizeAry as $key=>$value) {
			if($rand <= $value['v']) {
				$prizeid  = $key;
				break;
			}
		}
		unset($prizeAry,$rand);
		return $prizeid;
	}
}