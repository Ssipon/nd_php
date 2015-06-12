<?php
/**
 * 获取星星的等级状态
 */
class GetStarStatHelper{
	
    public function getStar($viplevel,$beforVipLevel){
    	
    	if ( 0 == $viplevel || ($viplevel - $beforVipLevel) < 0 ) return array(0,0,0,0,0,0,0,0);
    	
    	$islighten = ($viplevel - $beforVipLevel) > 0 ? true : false ;

    	if ($islighten) {
    		$vipStar   = self::vipStar($beforVipLevel);
	    	$beforStar = self::beforVipStar($viplevel,$beforVipLevel);  //获取升级需要点亮的星星
	    	$afterStar = self::afterVipStar($viplevel); 				//等级后面的星星设置为灰色
	    	return array_merge($vipStar,$beforStar,$afterStar);
    	}else{
	    	$vipStar = self::vipStar($viplevel); 						//当前等级
	    	$beforStar = self::afterVipStar($viplevel);                 //获取升级需要点亮的星星
	    	return array_merge($vipStar,$beforStar);
    	}
    }

    /**
     * 替换星星状态
     * @param str $starStat 星星状态
     * @param int $replace  替换位置
     * @param str $stat     替换状态
     * @return string 
     */
    public function replaceStar($starStat,$replaceIndex,$stat){
    	$ary = explode("_",$starStat);
		$ary[$replaceIndex - 1] = $stat ;
		return implode("_", $ary);
    }
    
    /**
     * vip等级设置状态为亮起
     * @param int $viplevel       现在等级
     * @param int $beforVipLevel  上一次等级
     * @return string
     */
    private function vipStar($viplevel){
    	$vipStar = array();
    	for($i = 0; $i < $viplevel; $i++){
    		$vipStar[$i]  ='1' ;
    	}
    	return $vipStar ;
    }
    
    /**
     * 升级vip等级前面星星全部设置为 2 可抽奖状态
     * @param int $viplevel       现在等级
     * @param int $beforVipLevel  上一次等级
     * @return string
     */
    private function beforVipStar($viplevel,$beforVipLevel){
    	$beforStarStat = array();
    	for($i = $beforVipLevel; $i < $viplevel; $i++){
    		$beforStarStat[$i]  ='2' ;
    	}
    	return $beforStarStat ;
    }
    
    /**
     * vip等级后面星星全部设置为 0 灰色状态
     * @param 现有等级 $viplevel
     * @return string
     */
    private static function afterVipStar($viplevel){
    	$afterStarStat = array();
    	for($i = $viplevel ;$i < 8; $i++){
    		$afterStarStat[$i] = '0' ;
    	}
    	return $afterStarStat ;
    }
}