<?php
/**
 * 领取VIP礼包
**/
include(dirname(dirname(__FILE__)).'/utils/DateCheck.class.php');
include(dirname(dirname(__FILE__)).'/server/GetMeetServer.class.php');
class GetController extends Controller {
	
	function excAction() {
		$page = View::getInst ( 'Json' ); // 响应数据类型配置
		$config = Config::load ( 'share' ); // 加载配置文件

		/**
		 * 活动时间效验，倘若为非活动日期则直接返回数据报文，并退出程序
		 */
		$actCode = DateCheck::isValid ( $config ['activityDate'] ['start'], $config ['activityDate'] ['end'], $config ['isCheckIp'] );
		if (null != $actCode && 0 != $actCode) {
			if (2 == $actCode) { // 代表活动还未开始
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'NDS_ACTIVITY' ) );
			} else if (1 == $actCode) { // 活动已经结束
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'CLSD_ACTIVITY' ) );
			}
			$page->display ();
			exit ();
		}
		
		/**
		 * 该活动必须登陆成功后方可参加，如果已经登录响应页面显示信息。
		 */
		if (! Acl::checkLogin ()) {
			$page->assign ( "isLogin", false );
			$page->display ();
			exit ();
		}
		
		// 已登录用户则直接提取用户信息
		$account 		= Acl::$aUser ['account'];
		$serverId 		= Acl::$aUser ['serverId'];
		$serverName		= Acl::$aUser ['serverName'];
		$playerName 	= Acl::$aUser ['playerName']; 			//角色名称
		
		/**
		 * 点击的星星数
		 */
		$starLevel = empty ( $_POST ['starLevel']) ? 0 : intval( $_POST ['starLevel'] );
		if ( empty( $starLevel ) ) {
			$page->assign ( "msg",  Language::getErrorMsg ( 'ERROR', 'VALIDA_REQUEST' ));
			$page->display ();
			exit ();
		}
		
		/**
		 * 判断玩家类型，老玩家需要判断他的流失天数
		 */
		$lockMemcache = Locker::getInst ( 'Memcache' );
		//老玩家锁机制
		if ($lockMemcache->lock ( 'sipon_vipfortune_get' )) {
			
			/**
			 * 获取最新的玩家数据信息
			 */
			$playerAry = Model::load('Player')->query(array('account' => $account ,'server_id'=>$serverId ));
			if ( empty( $playerAry ) ) {
				$lockMemcache->unlock ( 'sipon_vipfortune_get' );
				$page->assign ( "msg",  Language::getErrorMsg ( 'ERROR', 'LOGIN_EXCEPTION' ));
				$page->display ();
				exit ();
			}
			
			$playerVipInfoAry = Model::Load('PlayerVipInfo')->query(array('account'=> $account,'enable'=>1));
			$beforVipLevel    = empty($playerVipInfoAry) ? 0 : $playerVipInfoAry['vip_level'] ;
			$vipLevel 		  = $playerAry['vip_level'];

			/**
			 * 领取的VIP等级不能小于初始化等级
			 */
			if ( $vipLevel <= $beforVipLevel ) {
				$lockMemcache->unlock ( 'sipon_vipfortune_get' );
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'NOT_MEET' ) );
				$page->display ();
				exit ();
			}

			/**
			 * 判断领取的奖品星级不能小于初始化VIP等级
			 */
			if ( $starLevel <= $beforVipLevel ) {
				$lockMemcache->unlock ( 'sipon_vipfortune_get' );
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'NOT_MEET' ) );
				$page->display ();
				exit ();
			}
			
			/**
			 * 7级以下玩家判断是否领取过礼包
			 */
			if ( $starLevel < 8) {
				$lockMemcache->unlock ( 'sipon_vipfortune_get' );
				$playerAry = Model::load('GetLog')->query(array('account' => $account ,'server_id'=>$serverId,'star_level'=>$starLevel ));
				if (!empty($playerAry)) {
					$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'GOT_PRIZE' ) );
					$page->display ();
					exit ();
				}
			}else{
				//TODO 领取8星奖品，判断用户是否在达到了 7级以后，充值了2000$美金
				if (8 != GetMeetServer::getVip8($account,$playerAry['dollar_total'],$playerAry['vip_8_num'],$config['vip_level'][8],$config['vip_level'][7])) {
					$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'LIMIT_VIP8' ) );
					$page->display ();
					exit ();
				}
			}
			
			/**
			 * 限制奖品数量
			 */
			$prizeNum = Model::load('GetLog')->query(array( 'action_id' =>$config['star_prize'][$starLevel]['action'], "DATE_FORMAT(create_time,'%Y-%m-%d')"=> date("Y-m-d")),null ,4);
			if ($prizeNum >= $config['prize_limit_num']) {
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'LIMIT_PRIZE' ) );
				$page->display ();
				exit ();
			}
			
			/**
			 * 更新玩家信息表中的vip_8领取次数，vip_8_num
			 */
			if (8 == $starLevel ) {
				$updateId =	Model::load('Player')->update(array('vip_8_num' => array('vip_8_num + 1',Orm::DT_SQL)) ,
													      array( 'account'  => $account , 'server_id'=>$serverId));
			}
			
			//未达到vip_8砖石级的时候，则进行发奖，达到vip_8钻石级的时候，需要先进行发奖次数+1成功后，发奖
			if ( $starLevel != 8 || ( 8 == $starLevel && !empty($updateId)) ) {
				/**
				 * 插入领取奖品记录
				 */
				$lastId = Model::load('GetLog')->insert(array(  'account' 	  => $account,
																'player_name' => $playerName,
																'server_id'   => $serverId,
																'server_name' => $serverName,
																'star_level'  => $starLevel,  
																'prize_name'  => $config['star_prize'][$starLevel]['ename'],  
																'action_id'   => $config['star_prize'][$starLevel]['action'],
																'ip_add'      => App::ip(),
																'create_time' => date("Y-m-d H:i:s") ) );
				if ( !empty($lastId) ) {
					/**
					 * 实行真正的赠送礼物的方法
					 */
					App::import('api/ndgame/GameE');
					$order = 'hero'.md5(uniqid()).rand(0,1000);
					$respone = GameE::sendPrizeExNew(GAME_ID, $serverId, $account,$config['star_prize'][$starLevel]['action'],$order);
				}
				
				Model::load('GetLog')->update(array('book_order' => $order, 
													'respone'    => $respone), 
											  array('id' => $lastId));
			}
			
			
			
			//手动解锁				
			$lockMemcache->unlock ( 'sipon_vipfortune_get' );
		}
		$page->assign ( "getCode", true);
		$page->assign ( "msg", str_replace( "[X]" ,  $config['star_prize'][$starLevel]['ename'] ,Language::getErrorMsg ( 'SUCCEED', 'GET_FUNDS' )));
		$page->display ();
	}
	
}