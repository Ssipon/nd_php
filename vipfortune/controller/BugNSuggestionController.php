<?php
/**
 * bug&suggestion提交
**/
include(dirname(dirname(__FILE__)).'/utils/DateCheck.class.php');
include(dirname(dirname(__FILE__)).'/server/GetMeetServer.class.php');
class BugNSuggestionController extends Controller {
	
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
		 * 判断请求类型
		 */
		$type = empty ( $_POST ['type']) ? 0 :  $_POST['type'] ;
		if ( !( 0 == $type  || 1 == $type ) ) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'VALIDA_REQUEST' ) );
			$page->display ();
			exit ();
		}
		
		/**
		 * 判断是否有内容提交
		 */
		$content = empty ( $_POST ['content']) ? '' :  $_POST['content'] ;
		if ( empty( $content ) ) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'VALIDA_CENTENT' ));
			$page->display ();
			exit ();
		}
		
		/**
		 * 输入文字字符限制为10到300个字符
		 */
		if ( 10 > strlen( $content ) || 300 < strlen( $content ) ) {
			$page->assign ( "msg",Language::getErrorMsg ( 'ERROR', 'VALIDA_CENTENT_LEN' ) );
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
		 * 判断玩家类型，老玩家需要判断他的流失天数
		 */
		$lockMemcache = Locker::getInst ( 'Memcache' );

		/**
		 * 插入领取奖品记录
		 */
		Model::load ( 'Content' )->insert ( array ( 'account' 	  => $account,
													'type'		  => $type,
													'content'     => $content,
													'ip_add'      => App::ip(),
													'create_time' => date("Y-m-d H:i:s") ) );
			
		$page->assign ( "isSubmit", true); //提交成功
		$page->assign ( "msg", Language::getErrorMsg ( 'SUCCEED', 'SUBMIT' ));
		$page->display ();
	}
	
}