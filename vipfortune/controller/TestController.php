<?php
include(dirname(dirname(__FILE__)).'/utils/GetStarStatHelper.class.php');
include(dirname(dirname(__FILE__)).'/server/GetMeetServer.class.php');
class TestController extends Controller{
	function indexAction(){
		$config = Config::load ( 'share' ); // 加载配置文件chenweihaha" string(1) "8" string(1) "0
// 		$vipLevel = GetMeetServer::getVip8('chenweihaha',8,0,$config['vip_level'][8],$config['vip_level'][7]);
// 		var_dump($vipLevel.'=====',$account,$playerAry['dollar_total'],$playerAry['vip_8_num'],$config['vip_level'][8],$config['vip_level'][7]);
		
// 		$e=0;
		$prizeName     = end($config ['prize'][4]);//['ename'];     // 奖品名称
		var_dump($prizeName,$prizeName['id']);exit;
		
		$s = $this->getStar(5,3);
		$s1 = $this->replaceStar('1_1_1_1_2_2_0_0',5,1);
		var_dump($s,$s1);
		
		exit;

	}
	public function replaceStar($starStat,$replaceIndex,$stat){
		$ary = explode("_",$starStat);
		$ary[$replaceIndex-1] = $stat ;
		return implode("_", $ary);
	}
	
    private function getStar($viplevel,$beforVipLevel){
    	if ( 0 == $viplevel ) return '0_0_0_0_0_0_0_0';
    	
    	$islighten = ($viplevel - $beforVipLevel) > 0 ? true : false ;
    	/**
    	* 初始化星星的状态
    	**/
    	$starStat = '1_1_1_1_1_1_1_1';
    	$afterStar = $this->afterVipStar($viplevel); //等级后面的星星设置为灰色
    	$beforStar = $this->beforVipStar($viplevel,$beforVipLevel);//获取升级需要点亮的星星
    	if( $islighten  ){
    		//替换掉前面点亮的星星
    		return substr_replace($starStat, $beforStar.$afterStar, $beforVipLevel*2-1, 16); 
    	}
    	return substr_replace($starStat, $afterStar, $viplevel*2-1, 16);
    }
    
    /**
     * 升级vip等级前面星星全部设置为 2 可抽奖状态
     * @param int $viplevel       现在等级
     * @param int $beforVipLevel  上一次等级
     * @return string
     */
    private function beforVipStar($viplevel,$beforVipLevel){
    	$beforStarStat = '';
    	for($i = $beforVipLevel; $i < $viplevel; $i++){
    		$beforStarStat .='_2' ;
    	}
    	return $beforStarStat ;
    }
    
    /**
     * vip等级后面星星全部设置为 0 灰色状态
     * @param 现有等级 $viplevel
     * @return string
     */
    private function afterVipStar($viplevel){
    	$afterStarStat = '';
    	for($i = $viplevel ;$i< 8; $i++){
    		$afterStarStat .='_0' ;
    	}
    	return $afterStarStat ;
    }
}