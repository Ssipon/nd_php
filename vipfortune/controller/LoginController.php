<?php
include (dirname ( dirname ( __FILE__ ) ) . '/utils/DateCheck.class.php');
include (dirname ( dirname ( __FILE__ ) ) . '/server/GetMeetServer.class.php');
class LoginController extends Controller {
	public function loginAction() {
		$page = View::getInst ( 'json' ); // 响应数据类型配置
		$config = Config::load ( 'share' ); // 加载配置文件
		
		/**
		 * 活动时间效验，倘若为非活动日期则直接返回数据报文，并退出程序
		 */
		$actCode = DateCheck::isValid ( $config ['activityDate'] ['start'], $config ['activityDate'] ['end'] );
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
		 * 对参数进行效验
		 */
		$account = isset ( $_POST ['account'] ) ? $_POST ['account'] : '';
		$pwd = isset ( $_POST ['pwd'] ) ? $_POST ['pwd'] : '';
		if (empty ( $account ) || empty ( $pwd )) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'VLIACCOUNT_EMPTY' ) );
			$page->display ();
			exit ();
		}
		
		/**
		 * 效验是否选择服务器信息
		 */
		$serverId = isset ( $_POST ['server_id'] ) ? intval($_POST ['server_id']) : '';
		if (empty ( $serverId )) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'CHOSE_SERVER' ) );
			$page->display ();
			exit ();
		}
		
		/**
		 * 效验是否选择大区信息
		 */
		$areaId = isset ( $_POST ['area_id'] ) ? intval($_POST ['area_id']) : '';
		if (empty ( $areaId )) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'CHOSE_SERVER' ) );
			$page->display ();
			exit ();
		}
		
		/**
		 * 效验验证码
		 */
		$vcode = isset ( $_POST ['vcode'] ) ? $_POST ['vcode'] : '';
		$validcode = new CaptchaValide ( 60, 22 );
		
		if (! $validcode->checkWord ( $vcode )) {
			$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'VALIDE_CODE' ) );
			$page->display ();
			exit ();
		}
		
		// 进行海外账号效验
		App::import ( 'api/ndgame/AccountEn' );
		
		// 判断如果是本地IP 直接将登陆标志设置为 1 ，其他环境则 进行账号效验
		$loginCode = CheckIPLocalHost::ipLocalHost () ? 1 : AccountEn::check91enNew ( $_POST ['account'], $_POST ['pwd'] );
		if (isset ( $loginCode ) && 1 == $loginCode) {
			// 设置登陆成功的响应报文信息
			$msg = Language::getErrorMsg ( 'SUCCEED', 'VALIDE_ACCOUNT' );
			
			// 账号验证通过后，检查英文账号在区服中的信息.
			App::import ( 'api/ndgame/GameE' );
			$accountAry = GameE::getPlayerInfoMore( GAME_ID, $serverId, $account );
			if ( !is_array( $accountAry )) {
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'EMPET_ACCOUNT' ) );
				$page->display ();
				exit ();
			}
			
			// 提取用户的角色信息，用于记录用户参与活动
			$playerName = $accountAry['name']; // 角色名称
			
			/**
			 * 获取玩家的商城VIP
			 */
			$vipLevel = AccountEn::getVipByAccount( $account, 0, GAME_ID );// 获取VIP等级
			
			/**
			 * 获取玩家活动期间的充值金额,活动的开启时间到当前时间
			 */
			$allMoneyStr = GameE::getNewCards ( GAME_ID, $serverId, $account , $config['credit_time']);

			//未能取到充值接口的数据，则返回异常
			if (empty( $allMoneyStr )) {
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'LOGIN_EXCEPTION' ) );
				$page->display ();
				exit ();
			}
			
			$allMoneyAry = explode(',',$allMoneyStr);
			if (!is_array( $allMoneyAry )) {
				$page->assign ( "msg", Language::getErrorMsg ( 'ERROR', 'LOGIN_EXCEPTION' ) );
				$page->display ();
				exit ();
			}
			
			$Money799  = $allMoneyAry[0] ;
			$Money1599 = $allMoneyAry[1] ;
			$Money199  = $allMoneyAry[2] ;
			$Money99   = $allMoneyAry[3] ;
			$Money499  = $allMoneyAry[4] ;
			$Money2999 = $allMoneyAry[5] ;
			$Money5999 = $allMoneyAry[6] ;
			
			/**
			 * 计算玩家所获得的积分
			 */
			$creditTotal = $Money799 * 5 + $Money1599 * 10 + $Money2999 * 20 + $Money5999 * 40 ;
			
			/**
			 * 玩家所获得的充值金额
			 */
			$dollarTotal = $Money99 * 0.99 + $Money199 * 1.99 + $Money499 * 4.99 + $Money799 * 7.99 + $Money1599 * 15.99 + $Money2999 * 29.99 + $Money5999 * 59.99;

			/**
			 * 记录充值卡的记录
			 */
			$creditAry = Model::Load('CreditLog')-> query ( array('account'=> $account ,'server_id'=>$serverId ));
			if( empty( $creditAry ) ){
				$id = Model::Load('CreditLog')->insert(array(   'account'	  	  => $account,
																'player_name'	  => $playerName,
																'server_Id'	      => $serverId,
																'server_name'	  => GameE::getNameByServerId(GAME_ID,$serverId),
																'credit_5999'	  => $Money5999,
																'credit_2999'	  => $Money2999,
																'credit_1599'	  => $Money1599,
																'credit_799'	  => $Money799,
																'create_time'	  => date("Y-m-d H:i:s")
														));
			} else{
				$id = Model::Load('CreditLog')->update( array ( 'credit_5999'	  => $Money5999,
																'credit_2999'	  => $Money2999,
																'credit_1599'	  => $Money1599,
																'credit_799'	  => $Money799 ),
														array ( 'account'	      => $account, 'server_id' => $serverId  ));
			} 

			/**
			 * 记录玩家记录
			 */
			$playerAry = Model::Load('Player')-> query ( array('account'=> $account ,'server_id'=>$serverId ));
			$updateTime = date("Y-m-d H:i:s"); //用户获取排行时间
			if( empty( $playerAry ) ){
				
				/**
				 * 获取使用积分排名
				 */
				$rankAry = Model::Load('Player')-> getRank(0,$updateTime);
				$rank = (0 == $rankAry['rank']) ? 1 : ($rankAry['rank'] + 1);
				
				//登陆成功之后记录用户信息日志记录
				$id = Model::Load('Player')->insert(array(  'account'	  	  => $account,
															'player_name'	  => $playerName,
															'server_Id'	      => $serverId,
															'server_name'	  => GameE::getNameByServerId(GAME_ID,$serverId),
															'area_id'	      => $areaId,
															'area_name'	      => GameE::getNameByAreaId(GAME_ID,$areaId),
															'credit_total'	  => $creditTotal,
															'dollar_total'	  => intval($dollarTotal),
															'vip_level'	      => $vipLevel <= 0 ? 0 : $vipLevel,
															'rank'			  => $rank,
															'befor_rank'	  => $rank,
															'ip_add'	  	  => App::ip(),
															'update_time'	  => $updateTime,
															'create_time'	  => $updateTime ));
			} else{
				
				/**
				 * 获取使用积分排名
				 */
				$rankAry = Model::Load('Player')-> getRank($playerAry['credit_used'],$playerAry['create_time']);
				//更新最后登录时间以及离开天数
				$id = Model::Load('Player') -> update ( array ( 'credit_total'	  => $creditTotal,
																'dollar_total'	  => intval($dollarTotal),
																'rank'	 		  => $rankAry['rank']+1,
																'befor_rank'	  => $playerAry['rank'],
																'update_time'	  => $updateTime,
																'vip_level'  	  => $vipLevel <= 0 ? 0 : $vipLevel),
														array ( 'account'	      => $account, 'server_id' => $serverId  ));
			} 
			
			/**
			 * 记录登陆日志
			 */
			$loginLogAry = Model::Load('LoginLog')->query( array('account'=> $account ,"DATE_FORMAT(create_time,'%Y-%m-%d')"=> date("Y-m-d") ));
			
			if( empty( $loginLogAry ) ){
				//登陆成功之后记录用户信息日志记录
				Model::Load('LoginLog')->insert(array('account'     => $account,
													   'ip_add'	     => App::ip(),
													   'create_time' => date("Y-m-d H:i:s")));
				 
				$playerVipInfoAry = Model::Load('PlayerVipInfo')-> query ( array('account'=> $account ));
				 
				$vipCount    = $vipLevel    > 0 ? 1 : 0 ;           //vip总账号数
				$CreditCount = $dollarTotal > 0 ? 1 : 0 ;			//充值总账号数
				$upVipCount  = $vipLevel > $playerVipInfoAry['vip_level'] ? 1 : 0 ; //VIP升级总账号数
				
				/**
				 * 记录登陆日志
				 */
				$statAry = Model::Load('Stat')->query( array("DATE_FORMAT(create_time,'%Y-%m-%d')"=> date("Y-m-d") ));
				if( empty( $statAry ) ){
					//登陆成功之后记录用户信息日志记录
					 Model::Load('Stat')->insert(array('login_count' 		  => 1, 
					 								   'credit_account_count' => $CreditCount ,
					 								   'vip_account_count' 	  => $vipCount ,
					 								   'create_time' 		  => date("Y-m-d H:i:s")));
				}else {
					 Model::Load('Stat')->update(array('login_count'      => array('login_count + 1', Orm::DT_SQL),
					 								   'credit_account_count' => array('credit_account_count + '.$CreditCount, Orm::DT_SQL),
					 								   'vip_account_count'    => array('vip_account_count + '.$vipCount, Orm::DT_SQL)),
												 array("DATE_FORMAT(create_time,'%Y-%m-%d')"=> date("Y-m-d")));
					
				}
			} 	
			

			//登录信息过期时间
			$expire = $config ['activityDate'] ['end'] - time();
			// 进行cookie 设置
			Acl::setLogin ( array ( 'account' 	 	 => $account,
									'serverId' 		 => $serverId,
									'serverName' 	 => GameE::getNameByServerId ( GAME_ID, $serverId ),
									'playerName' 	 => $playerName, // 角色名称
									), $_POST['saveLogin']?$expire:0 ); // 玩家等级
		} else {
			$msg = Language::getErrorMsg ( 'ERROR', 'VALIDE_ACCOUNT' );
		}
		// 响应登陆的表示符，用于页面判断登陆结果
		$page->assign ( "code", $loginCode );
		$page->assign ( "msg", $msg );
		$page->display ();
	}
	
	// 效验是否登陆
	public function checkLoginAction() {
		$page = View::getInst ( 'Json' ); // 响应数据类型配置
		$isLogin = false;
		if (Acl::checkLogin ())
			$isLogin = true;
		$page->assign ( 'isLogin', $isLogin );
		$page->display ();
	}
		
	// 退出登陆
	public function logoutAction() {
		setcookie('ACTIVITY_ACCOUNT',null,time()-1,'/','.91.com');
		setcookie('ACTIVITY_ACCOUNT',null,time()-1,'/','.99.com');
		Acl::logout ();
	}
}