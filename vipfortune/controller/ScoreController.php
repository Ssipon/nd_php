<?php
/**
 * 领取忠诚礼包
**/
include(dirname(dirname(__FILE__)).'/utils/DateCheck.class.php');
include(dirname(dirname(__FILE__)).'/server/GetMeetServer.class.php');
class ScoreController extends Controller {
	
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
		$areaId 		= Acl::$aUser ['areaId'];
		$areaName 		= Acl::$aUser ['areaName'];
		$playerName 	= Acl::$aUser ['playerName']; 			//角色名称
		
		/**
		 * 判断前台所传送的prizeId 是否与登陆是一致
		 */
		$star = empty ( $_POST ['star']) ? 0 : intval( $_POST ['star'] );
		if ( empty( $star ) || 0 > $star || 5 < $star ) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'VALIDA_REQUEST' ) );
			$page->display ();
			exit ();
		}
		
		/**
		 * 判断玩家类型，老玩家需要判断他的流失天数
		 */
		$lockMemcache = Locker::getInst ( 'Memcache' );
		if ($lockMemcache->lock ( 'zf_grandgift_score' )) {
			
			/**
			 * 查看该玩家是否已经投过票
			 */
			$getAry = Model::load ( 'Score' ) -> query ( array ( 'account' => $account ));
			
			if ( !empty( $getAry ) ) {
				$lockMemcache->unlock ( 'zf_grandgift_score' ) ;
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'VOTED' ) );
				$page->display ();
				exit ();
			}
			
			/**
			 * 记录玩家通票日志
			 */
			Model::load ( 'Score' )->insert(array  ('account' 	  => $account,
													'star'   	  => $star,
													'ip_add'      => App::ip(),
													'create_time' => date("Y-m-d H:i:s") ) );
			//手动解锁				
			$lockMemcache->unlock ( 'zf_grandgift_score' );
		}
		
		/**
		 * 获取点赞积分情况
		 */
		$scoreAry = Model::load('Score')->getScoreInfo() ;
		$avgScore = sprintf("%.1f",$scoreAry['avgScore']); 
		$voteCount = $scoreAry['voteCount'] ; 
		$page->assign("avgScore", $avgScore < 4 ? 4 : $avgScore);
		$page->assign("voteCount", $voteCount );
		$page->assign ( "succeed", true);
		$page->assign ( "msg", Language::getErrorMsg ( 'SUCCEED', 'VOTED' ));
		$page->display ();
	}
	
}