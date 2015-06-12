<?php
/**
 * 获取用户的领取礼包的档次 
 * @author sipon
 *
 */
class GetMeetServer {
	
	/**
	 * 使用积分来获取转盘等级
	 * @param int $uesdCredit 使用积分
	 * @return int
	 */
	public function dialLevel($uesdCredit) {
		if ( $uesdCredit <= 100){
			return 1 ;
		}else if (110  <= $uesdCredit && $uesdCredit <= 500){
			return 2 ;
		}else if (510  <= $uesdCredit && $uesdCredit <= 1000){
			return 3 ;
		}else if (1010 <= $uesdCredit){
			return 4 ;
		}
	}

	/**
	 * 获取VIP等级，查看是否为8级
	 * @param string $account     账号信息
	 * @param  int   $dollarTotal 玩家活动期间的总充值
	 * @param  int   $vip8Num	  vip8已领取的次数
	 * @param  int   $vip8Credit  vip8所需要的充值
	 * @param  int   $vip7Credit  vip7所需要的充值
	 * @return int   返回当前的等级，至少到达VIP7
	 */
	public function getVip8( $account,$dollarTotal, $vip8Num = 0, $vip8Credit = 2000, $vip7Credit = 7999 ) {
			$playerVipInfoAry = Model::Load('PlayerVipInfo')->query(array('account'=> $account));
			
			if ( !empty($playerVipInfoAry) && 7 == $playerVipInfoAry['vip_level'] ) {
				/**
				 * 历史已经7级的，直接判断活动期间有充值2000就可以
				 */
				if ($dollarTotal - $vip8Credit * $vip8Num >= $vip8Credit )
					return 8 ;
			}else {
				/**
				 * 玩家活动期间充值金额达到 7999 减去 活动前玩家的累积充值金额 然后每再充值2000就能领取钻石奖励
				 */
				if ($dollarTotal - ( $vip7Credit - $playerVipInfoAry['credit']) - $vip8Credit * $vip8Num >= $vip8Credit )
					return 8 ;
			} 
			return 7;
				
	}
}