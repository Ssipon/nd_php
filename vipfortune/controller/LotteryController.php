<?php
/**
 *抽奖活动控制器 
**/
include(dirname(dirname(__FILE__)).'/utils/DateCheck.class.php');
include(dirname(dirname(__FILE__)).'/utils/LotteryHelper.class.php');
include(dirname(dirname(__FILE__)).'/server/GetMeetServer.class.php');
class LotteryController extends Controller {
	function exectionAction() {
		$config = Config::load ( 'share' ); // 加载配置文件
		
		/**
		 * 活动时间效验，倘若为非活动日期则直接返回数据报文，并退出程序
		 */
		$actCode = DateCheck::isValid ( $config ['activityDate'] ['start'], $config ['activityDate'] ['end'], $config ['isCheckIp'] );
		if (null != $actCode && 0 != $actCode) {
			if (2 == $actCode) { // 代表活动还未开始
				echo json_encode( array("success"=>-1,  "fail_info"=>Language::getErrorMsg ( 'ERROR', 'NDS_ACTIVITY' )) );
			} else if (1 == $actCode) { // 活动已经结束
				echo json_encode( array("success"=>-1,   "fail_info"=>Language::getErrorMsg ( 'ERROR', 'CLSD_ACTIVITY' )) );
			}
			exit ();
		}
		
		/**
		 * 该活动必须登陆成功后方可参加，如果已经登录响应页面显示信息。
		 */
		if (! Acl::checkLogin ()) {
			echo json_encode( array("success"=>-1, "isLogin"=>false,"fail_info"=> Language::getErrorMsg ( 'ERROR', 'LOGIN_EXCEPTION' )));
			exit ();
		}
		
		// 已登录用户则直接提取用户信息
		$account    = Acl::$aUser ['account'];
		$playerName = Acl::$aUser ['playerName'];
		$serverId   = Acl::$aUser ['serverId'];
		$serverName = Acl::$aUser ['serverName'];
	
		/**
		 * 进行活动加锁
		 */
		$lockMemcache = Locker::getInst ( 'memcache' );
		if ($lockMemcache->lock ( 'sipon_vipfortune_lottery' )) { // 对所有账户进行抽奖加锁，加锁成功则证明符合上述流程条件，可执行抽奖
			
			/**
			 * 查看玩家是否存在
			 */
			$playerAry = Model::load ( 'Player' )->query(array('account' => $account,'server_id'=>$serverId));
			if ( empty($playerAry)) {
				$lockMemcache->unlock ( 'sipon_vipfortune_lottery' );
				echo json_encode( array("success"=>-1,"fail_info"=> Language::getErrorMsg ( 'ERROR', 'LOGIN_EXCEPTION' )));
				exit ();
			}
			
			/**
			* 判断玩家积分是否够
			**/
			$remainCredit = $playerAry['credit_total'] - $playerAry['credit_used'];
			if ( $remainCredit < $config['lottery_credit'] ) {
				$lockMemcache->unlock ( 'sipon_vipfortune_lottery' );
				echo json_encode( array("success"=>-1,"fail_info"=>Language::getErrorMsg ( 'ERROR', 'LIMIT_SCORE' )));
				exit ();
			}
			
			$dialevel = $playerAry['dial_level'] ;
			if ($dialevel > GetMeetServer::dialLevel($playerAry['credit_used'])) {
				$lockMemcache->unlock ( 'sipon_vipfortune_lottery' );
				echo json_encode( array("success"=>-1,"fail_info"=>Language::getErrorMsg ( 'ERROR', 'VALIDA_REQUEST' )));
				exit ();
			}
			
			$prizeId	   = LotteryHelper::exe( $config ['prize'][$dialevel] );
			$prizeName     = $config ['prize'][$dialevel][$prizeId]['ename'];     // 奖品名称
			$prizeAction   = $config ['prize'][$dialevel][$prizeId]['action'];    // 奖品action
			$prizeLimitNum = $config ['prize'][$dialevel][$prizeId]['limit_num']; // 奖品数量限制
			$prizeFlashId  = $config ['prize'][$dialevel][$prizeId]['flashId'];        // 奖品与Flash对应的ID
			
			/**
			 * 如果奖品有个数限制，则进行判断，如果奖品被抽完了，那么赠送最低级别奖品
			 */
			if (!empty($prizeLimitNum)) {
				$lotteryNum = Model::load('GetLog')->query(array( 'prize_id' =>$prizeId ),null ,4);
				if ($lotteryNum >= $prizeLimitNum) {//活动期间奖品限制
					$prizeAry      = end($config ['prize'][$dialevel]); //获取奖品当中概率最低的一个
					$prizeAction   = $prizeAry['action'];    // 奖品action
					//进行保底的奖品数量限制
					$lotteryNum = Model::load('GetLog')->query(array( 'action_id' =>$prizeAction, "DATE_FORMAT(create_time,'%Y-%m-%d')"=> date("Y-m-d")),null ,4);
					if ($lotteryNum >= $config['prize_limit_num']) {
						echo json_encode( array("success"=>-1,"fail_info"=>Language::getErrorMsg ( 'ERROR', 'LIMIT_PRIZE' )));
						exit ();
					}
					
					$prizeId	   = $prizeAry['id'];
					$prizeName     = $prizeAry['ename'];     // 奖品名称
					$prizeLimitNum = $prizeAry['limit_num']; // 奖品数量限制
					$prizeFlashId  = $prizeAry['flashId'];        // 奖品与Flash对应的ID
				}
			}else{//如果没有个数限制，则进行判断领取的礼包今天是否超过了 500个
				$lotteryNum = Model::load('GetLog')->query(array( 'action_id' =>$prizeAction, "DATE_FORMAT(create_time,'%Y-%m-%d')"=> date("Y-m-d")),null ,4);
				if ($lotteryNum >= $config['prize_limit_num']) {
					echo json_encode( array("success"=>-1,"fail_info"=>Language::getErrorMsg ( 'ERROR', 'LIMIT_PRIZE' )));
					exit ();
				}
			}
			
			/**
			 * 插入领取奖品记录
			 */
			$lastId = Model::load('GetLog')->insert(array( 'account' 	 => $account,
														   'player_name' => $playerName,
														   'server_id'   => $serverId,
														   'server_name' => $serverName,
														   'prize_id'    => $prizeId,  
														   'prize_name'  => $prizeName,  
														   'dial_level'  => $dialevel,  
														   'action_id'   => $prizeAction,
														   'ip_add'      => App::ip(),
														   'create_time' => date("Y-m-d H:i:s") ) );
			if ( !empty($lastId) ) {
				/**
				 * 更新用户的使用积分情况
				 */
				$updateId = Model::load ( 'Player' )->update( array('credit_used' => array('credit_used + '.$config['lottery_credit'],Orm::DT_SQL) ),
															  array('account' => $account,'server_id'=>$serverId) );
				/**
				 * 玩家积分扣除成功之后，进行发奖
				 */
				if (!empty($updateId)) {
					/**
					 * 实行真正的赠送礼物的方法
					 */
					App::import('api/ndgame/GameE');
					$order = 'hero'.md5(uniqid()).rand(0,1000);
					$respone = GameE::sendPrizeExNew(GAME_ID, $serverId, $account,$prizeAction,$order);
					Model::load('GetLog')->update( array ( 'book_order' => $order, 'respone' => $respone ) ,array( 'id' => $lastId));
				}
			}
			
			$lockMemcache->unlock ( 'sipon_vipfortune_lottery' );
			echo json_encode( array("success"=>1,'remainCredit' => $remainCredit - $config['lottery_credit'], 'totalCredit' => $playerAry['credit_total'] ,'uesdCredit' => $playerAry['credit_used'] + $config['lottery_credit'], "data"=>array('reward' => $prizeFlashId ,'name'=>$prizeName)));
		} else {
			// 加锁失败，不能进行解锁
			$lockMemcache->unlock ( 'sipon_vipfortune_lottery' );
			echo json_encode( array("success"=>-1,"fail_info"=>Language::getErrorMsg('ERROR', 'NET_EXCEPTION' )));
			exit ();
		}
	}
}