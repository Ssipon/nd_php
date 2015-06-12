<?php  
//游戏的接口  
include(dirname(dirname(__FILE__)).'/utils/DateCheck.class.php');
include(dirname(dirname(__FILE__)).'/utils/GetStarStatHelper.class.php');
include(dirname(dirname(__FILE__)).'/server/GetMeetServer.class.php');
class IndexController extends Controller {
	
	//首页
	public function indexAction() { 
		
		$view = View::getInst('smarty');
		$config = Config::load ( 'share' ); // 加载配置文件
		/**
		 * 获取点赞积分情况
		 */
		$scoreAry = Model::load('Score')->getScoreInfo() ;		
		$avgScore = sprintf("%.1f",$scoreAry['avgScore']);
		if ( $avgScore == 0)$avgScore = 5.0 ; //默认情况为5
		$voteCount = $scoreAry['voteCount'] ;
		$view->assign("avgScore", $avgScore < 4 ? 4.0 : $avgScore);
		$view->assign("voteCount", $voteCount );
		
		/**
		 * 获取领奖
		 */
		$getLogList = Model::Load('GetLog')-> query ( null,null,2,0,10);
		$view->assign("getLogList", $getLogList);
		
		if (!Acl::checkLogin()) {
			$view->assign("islogin", 0);
			$view->assign("dialType", 4 ); //未登录默认的转盘类型
			$view->assign("uesdCredit", 0 );//已使用的积分
			$view->assign("dialCredit", $config['dial_level'][4]['down']); //转盘所需要的积分
			$view->assign("starStat", '1_1_1_1_1_1_1_1');   //默认星星的状态等级
			$view->display('template/index.tpl');
			exit ;
		}
		
		$playerAry = Model::Load('Player')-> query( array('account'=> Acl::$aUser['account'] ,'server_id'=>Acl::$aUser['serverId'] ));
		
		$playerVipInfoAry = Model::Load('PlayerVipInfo')->query(array('account'=> $playerAry['account'],'enable'=>1));
		
		/**
		 * 展示活动前的vip等级
		 */
		$beforvipLevel = empty($playerVipInfoAry) ? 0 : $playerVipInfoAry['vip_level'];
		$view->assign("beforvipLevel", $beforvipLevel); //
		/**
		 * 获取充值记录
		 */
		$creditLogAry = Model::Load('CreditLog')-> query ( array('account'=> Acl::$aUser['account'] ,'server_id'=>Acl::$aUser['serverId'] ));
		
		/**
		 * 获取等级以及距离下一等级的所需要的积分
		 */
		$vipLevel = $playerAry['vip_level'];
		if ($vipLevel < 7) {
			$nextVipCredit = $config['vip_level'][$vipLevel+1] - $config['vip_level'][$vipLevel] ;
			$view->assign("nextVipCredit",$nextVipCredit <= 0 ? 0 : $nextVipCredit);
		}
		/**
		 * 获取星星的状态
		 */
		$starStat = $this->getStarStat($playerAry);
		
		/**
		 * 获取要展示的转盘类型以及该转盘升级到下一级所需要的积分
		 */
		$dialLevel = $playerAry['dial_level'];
		if (0 == $dialLevel) {//如果初始化表当中没有该玩家的VIP等级，则默认为1级VIP积分展示
			$dialCredit = $config['dial_level'][2]['down'];
		}else {
		 	$dialCredit = (4 == $dialLevel) ? 0 : $config['dial_level'][$dialLevel+1]['down'];
		}
		
		/**
		 * 获取玩家达到7级之后的充值量
		 */
		if ( !empty($playerVipInfoAry) && 7 == $playerVipInfoAry['vip_level'] ) {
			$view->assign("vip7Credited", $playerAry['dollar_total']);
		}else if( 7 == $vipLevel) {
			$view->assign("vip7Credited",  $playerAry['dollar_total'] - ( 7999 - $playerVipInfoAry['credit']));
		}
	 	
		//计算剩余的积分
		$RemainCredit = $playerAry['credit_total'] - $playerAry['credit_used'] ;
		$view->assign("dialType", $dialLevel);
		$view->assign("starStat", $starStat);
		$view->assign("dialCredit", $dialCredit);
		$view->assign("remainCredit", $RemainCredit > 0 ? $RemainCredit : 0);
		$view->assign("totalCredit", $playerAry['credit_total'] > 0 ? $playerAry['credit_total'] : 0 );
		$view->assign("uesdCredit", $playerAry['credit_used'] > 0 ? $playerAry['credit_used'] : 0);
		$view->assign("playerAry", $playerAry);
		$view->assign("rankOrder", $playerAry['befor_rank'] - $playerAry['rank']);
		$view->assign("creditLogAry", $creditLogAry);
		$view->assign("islogin", 1);
		$view->display('template/index.tpl');
    } 
    
    /**
     * 查看数据排行列表
     */
    public function listAction(){
    	$pageSize   = isset($_POST['pageSize']) ? intval($_POST['pageSize']) : 3;
    	$page       = isset($_POST['page']) ? intval($_POST['page']) : 1;
    	$start      = ($page - 1) * $pageSize;

    	$whereAry = array();
    	!empty($_POST['playerName']) && $whereAry['player_name'] = $_POST['playerName'] ;
    	!empty($_POST['serverId'])   && $whereAry['server_id']   = intval($_POST['serverId']) ;
    	!empty($_POST['areaId'])     && $whereAry['area_id'] 	 = intval($_POST['areaId']) ;
    	
    	/**
    	 * 获取总条数
    	 */
    	$total = Model::Load('Player')-> query ( null,null,4);
    	
    	/**
    	 * 获取排行榜
    	 */
    	$orderList = Model::Load('Player')-> query ( $whereAry ,array('credit_used'=>'desc','create_time'=>'asc'),2,$start,$pageSize);
    	
    	foreach ($orderList as $k => $v) {
    		$orderList[$k]['no'] = $k + 1 + $start; 
    	}
    	echo json_encode(array(
    			'total' => intval($total),
    			'page'  => $page,
    			'list'  => $orderList
    	));
    }
    
    /**
     * 升级
     */
    public function levelUpAction(){
    	
    	$config = Config::load ( 'share' ); // 加载配置文件
		
		/**
		 * 活动时间效验，倘若为非活动日期则直接返回数据报文，并退出程序
		 */
		$actCode = DateCheck::isValid ( $config ['activityDate'] ['start'], $config ['activityDate'] ['end'], $config ['isCheckIp'] );
		if (null != $actCode && 0 != $actCode) {
			if (2 == $actCode) { // 代表活动还未开始
				echo json_encode( array("success"=>-1, "fail_info"=>Language::getErrorMsg ( 'ERROR', 'NDS_ACTIVITY' )) );
			} else if (1 == $actCode) { // 活动已经结束
				echo json_encode( array("success"=>-1, "fail_info"=>Language::getErrorMsg ( 'ERROR', 'CLSD_ACTIVITY' )) );
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
		$playerName = Acl::$aUser ['account'];
		$serverId   = Acl::$aUser ['serverId'];
		$serverName = Acl::$aUser ['serverName'];
	
		/**
		 * 进行活动加锁
		 */
		$lockMemcache = Locker::getInst ( 'memcache' );
		if ($lockMemcache->lock ( 'sipon_vipfortune_index_levelup' )) { // 对所有账户进行抽奖加锁，加锁成功则证明符合上述流程条件，可执行抽奖
			
			/**
			 * 查看用户积分是否够用
			 */
			$playerAry = Model::load ( 'Player' )->query(array('account' => $account,'server_id'=>$serverId));
			if ( empty($playerAry)) {
				$lockMemcache->unlock ( 'sipon_vipfortune_index_levelup' );
				echo json_encode( array("success"=>-1,"fail_info"=> Language::getErrorMsg ( 'ERROR', 'LOGIN_EXCEPTION' )));
				exit ();
			}
			
			/**
			* 判断积分是否够升级
			**/
			$dialLevel  = GetMeetServer::dialLevel($playerAry['credit_used']);
			
			/**
			 * 进行转盘升级更新
			 */
			Model::Load('Player')->update(array('dial_level' => $dialLevel),
										  array("account"=> $account ,'server_id' =>$serverId));
		
			$lockMemcache->unlock ( 'sipon_vipfortune_index_levelup' );
			echo json_encode( array("success"=>1, "data"=>array('type' => $dialLevel ,'current'=>$playerAry['credit_used'],'total'=>$config['dial_level'][$dialLevel]),'fail_info' =>str_replace( "[X]" ,$dialLevel,Language::getErrorMsg('SUCCEED', 'UP_LEVEL' ))));
		} else {
			// 加锁失败，不能进行解锁
			$lockMemcache->unlock ( 'sipon_vipfortune_lottery' );
			echo json_encode( array("success"=>-1,"fail_info"=>Language::getErrorMsg('ERROR', 'NET_EXCEPTION' )));
			exit ();
		}
    }

    /**
     * 初始化转盘
     */
    public function initDailAction(){
    	$config = Config::load ( 'share' ); // 加载配置文件
    	
    	/**
    	 * 未登录的时候，初始化给最高等级
    	 */
    	if (! Acl::checkLogin ()) {
			echo json_encode( array( 'type' => 4 ,'current'=>0,'total'=>$config['dial_level'][4]['down'] ));
    		exit ();
    	}
    	
    	$playerAry = Model::Load('Player')-> query( array('account'=> Acl::$aUser['account'] ,'server_id'=>Acl::$aUser['serverId'] ));
    	
    	/**
    	 * 获取要展示的转盘类型以及该转盘升级到下一级所需要的积分
    	 */
    	$dialLevel = $playerAry['dial_level'];
		if (0 == $dialLevel) {//如果初始化表当中没有该玩家的VIP等级，则默认为1级VIP积分展示
			$dialCredit = $config['dial_level'][2]['down'];
		}else {
		 	$dialCredit = (4 == $dialLevel) ? 0 : $config['dial_level'][$dialLevel+1]['down'];
		}
		
		echo json_encode( array( 'type' => $playerAry['dial_level'] ,'current'=>$playerAry['credit_used'],'total'=>$dialCredit ));
    }
    
    /**
     * 初始化星星
     */
    public function initStarAction(){
    	$config = Config::load ( 'share' ); // 加载配置文件
    	
    	/**
    	 * 未登录的时候，初始化星星全亮
    	 */
    	if (! Acl::checkLogin ()) {
			echo json_encode( array( 'info' => '1_1_1_1_1_1_1_1'));
    		exit ();
    	}
    	
    	$playerAry = Model::Load('Player')-> query( array('account'=> Acl::$aUser['account'] ,'server_id'=>Acl::$aUser['serverId'] ));
    	$starStat = $this->getStarStat($playerAry);
    	
		echo json_encode( array( 'info' => $starStat));
    }
    
    /**
     * 获取星星状态
     * @param str $playerAry 玩家信息
     * @return string '1_1_1_1_1_1_1_1' ,0为灰色，1为点亮，2为光晕
     */
    public function getStarStat($playerAry){
    	$config = Config::load ( 'share' ); // 加载配置文件
    	
    	if (empty($playerAry)) return '1_1_1_1_1_1_1_1';
    	
    	$vipLevel = $playerAry['vip_level'];
    	if ( 7 == $playerAry['vip_level']) { //如果当前等级达到了7级，查看下玩家的等级是否能领取砖石8级
    		$vipLevel = GetMeetServer::getVip8($playerAry['account'],$playerAry['dollar_total'],$playerAry['vip_8_num'],$config['vip_level'][8],$config['vip_level'][7]);
    	}
    	
    	/**
    	 * 查找玩家初始化的vip等级
    	 */
    	$playerVipInfoAry = Model::Load('PlayerVipInfo')->query(array('account'=> $playerAry['account'],'enable'=>1));
    	$beforVipLevel = empty($playerVipInfoAry) ? 0 : $playerVipInfoAry['vip_level'] ;
    	
    	/**
    	 * 获取星星等级展示
    	 */
    	$starStat = implode('_',GetStarStatHelper::getStar($vipLevel,$beforVipLevel));
    	
    	/**
    	 * 过滤掉已经领取的行星状态
    	*/
    	$getLog = Model::Load('GetLog')-> query( array('account'=> $playerAry['account'] ,'server_id'=>$playerAry['server_id'] ),null,2);
    	if ( (stripos($starStat,'0') || stripos($starStat,'0') == 0 ) && !empty($getLog)) {
    		foreach ($getLog as $key => $value){
    			//TODO 判断如果是 8级VIP,则不进行领取次数判断，而进行的充值金额判断
    			if( 8 == $value['star_level'] ) continue ;
    			
    			$starStat = GetStarStatHelper::replaceStar($starStat, $value['star_level'], 1);
    		}
    	}
    	
		return $starStat ;
    }
    
    
    
}