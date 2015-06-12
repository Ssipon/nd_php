<?php
/**
 * 公共配置资料
 * @author leicc<355108@nd.com>
 */ 

define('DEVDEBUG',0);//TODO 生产环境需要更改为0，不打印异常信息
define('GAME_ID',3); //英文征服游戏ID
define('OLD_REQUEST','past');     //老玩家请求串
define('NEW_REQUEST','present');  //现有玩家请求串

// 定义数据表为常量
define('DB_TABLE_PREFIX',	   'sipon_vipfortune_');
define('PLAYER_TABLE',		   DB_TABLE_PREFIX.'player');
define('PLAYER_VIPINFO_TABLE', DB_TABLE_PREFIX.'player_vipinfo');
define('LOGIN_LOG_TABLE',	   DB_TABLE_PREFIX.'login_log');
define('LOTTERY_LOG_TABLE',	   DB_TABLE_PREFIX.'lottery_log');
define('GET_LOG_TABLE',		   DB_TABLE_PREFIX.'get_log');
define('CONTENT_TABLE',  	   DB_TABLE_PREFIX.'content');
define('SCORE_TABLE',  		   DB_TABLE_PREFIX.'score');
define('STAT_TABLE',  	       DB_TABLE_PREFIX.'stat');
define('CREDIT_LOG_TABLE',     DB_TABLE_PREFIX.'credit_log');

date_default_timezone_set('America/Los_Angeles');
return array(
	'datezone'  => 'America/Los_Angeles',
	'php' 		=> '/usr/bin/php',
	'language'	=> 'zh-cn',
	'bootstrap' => array(
		'class' => 'index',
		'method'=> 'index'
	),
    'crypt'     => '5*cKem&2',
	'dir'		=> array(
		'cache'	=> WEBROOT.'/cache/',
		'lib'   => COREDIR,
		'bin'	=> WEBROOT.'/bin/'
	),
	'openCache' => true,   //是否开启Model cache
	'log'	=> array(
		'file'  => array(
			'lv'   => 1,
			'path' => 'log/'
		)
	 ),
    'cookie' => array(
        'sCId'  => '__sipon_vipfortune__', //cookie——id,李小龙vip冲级活动
        'crypt' => 'think',    //加密算法
        'key'   => '5*cKem&2', //加密key
        'cache' => 'file'      //cache 类型，存放密码校验码
     ),
    'ndapi' => array(
        '91u' => 'http://nderp.99.com/Asmx/Reming.asmx?wsdl',
     ),
	 'url'   => array(
		'baseurl' => 'http://'.$_SERVER['HTTP_HOST'],
		'static'  => array(
			'http://'.$_SERVER['HTTP_HOST'].'/static/'
	 ),
		'upload'  => array(
			'http://'.$_SERVER['HTTP_HOST']
		)
	),
	'loader' => array(
        'lib'   => COREDIR,
        'utils' => COREDIR.'utils/',
        'nd'    => COREDIR.'api/nd/'
    ) ,
		
	'activityDate' => array(  //TODO 活动时间配置
        'start' =>strtotime('2014-11-03 00:00:01'),   // 活动开始时间 
        'end'   =>strtotime('2014-11-16 23:59:59')    // 活动结束时间
    ) ,
	'credit_time' => '20141103',

	'vip_level' => array(  //TODO vip等级设置，VIP7级（包括7）为历史累计，8级为7级之后，再冲2000
        '1' => 59 ,    
        '2' => 199 ,
        '3' => 399 ,
        '4' => 999 ,
        '5' => 1999 ,
        '6' => 3999 ,
        '7' => 7999 , 
        '8' => 2000   // 2000是在达到VIP7级之后，再充值两千 
    ) ,
    
  'dial_level' => array(  //TODO 转盘等级设置
        '1' => array( 'down' => 0,   'up' => 100 ),    
        '2' => array( 'down' => 110, 'up' => 500 ),
        '3' => array( 'down' => 510, 'up' => 1000),
        '4' => array( 'down' => 1010,'up' => ''  )
    ) ,

	'star_prize' => array( //星级奖品概率配置
			'1'  => array(id=>'1',  'ename'=>'VIP1 Honor Pack',      'action'=>'562154'),
			'2'  => array(id=>'2',  'ename'=>'a Dragon Ball',        'action'=>'500690'),
			'3'  => array(id=>'3',  'ename'=>'VIP3 Honor Pack',      'action'=>'562146'),
			'4'  => array(id=>'4',  'ename'=>'VIP4 Honor Pack',      'action'=>'562147'),
			'5'  => array(id=>'5',  'ename'=>'VIP5 Honor Pack',      'action'=>'562148'),
			'6'  => array(id=>'6',  'ename'=>'VIP6 Honor Pack',      'action'=>'562149'),
			'7'  => array(id=>'7',  'ename'=>'VIP7 Honor Pack',      'action'=>'562150'),
			'8'  => array(id=>'8',  'ename'=>'Super VIP Honor Pack', 'action'=>'562151')
	),
	
		
	/**
	 * 差值概率，前一个v减当前v为概率值
	 * id    奖品序列号
	 * cname 中文名称
	 * ename 英文名称
	 * v     发奖概率
	 * action 发奖action
	 */
	'prize' => array( //转盘奖品概率配置
	    '1' => array(
					'1'  => array(id=>'1' , flashId=>'1',   'cname'=>'iPad Mini', 	  'ename'=>'iPad Mini',        		  'v'=>0,   'action'=>'0几率'),
					'2'  => array(id=>'2' , flashId=>'5',   'cname'=>'赤炼石+6',		  'ename'=>'a +6 Stone',			  'v'=>0.2, 'action'=>'509361', 'limit_num'=>1, 'desc'=>'活动期间最多1个'),
					'3'  => array(id=>'3' , flashId=>'11',  'cname'=>'金刚坚钻', 		  'ename'=>'a Tough Drill',     	  'v'=>0.5, 'action'=>'500615', 'limit_num'=>1, 'desc'=>'活动期间最多1个'),
					'4'  => array(id=>'4' , flashId=>'7',   'cname'=>'李小龙t-shirt2',  'ename'=>'a Lee-Long T-shirt',	  'v'=>1,   'action'=>'562145', 'limit_num'=>10,'desc'=>'活动期间最多10个'),
					'5'  => array(id=>'5' , flashId=>'2',   'cname'=>'300天石',		  'ename'=>'300 CPs',			      'v'=>2,  	'action'=>'500508'),
					'6'  => array(id=>'6' , flashId=>'10',  'cname'=>'固化石*1',		  'ename'=>'a Permanent Stone',		  'v'=>2.5, 'action'=>'500919'),
					'7'  => array(id=>'7' , flashId=>'12',  'cname'=>'赤炼石+2，马匹+2',  'ename'=>'a +2 Stone & a +2 Horse', 'v'=>11.5,'action'=>'509688'),
					'8'  => array(id=>'8' , flashId=>'9',   'cname'=>'经验保护丹*2',	  'ename'=>'Exp Care Pill x 2',		  'v'=>21,  'action'=>'562136'),
					'9'  => array(id=>'9' , flashId=>'6',   'cname'=>'回气丹*1', 	 	  'ename'=>'a Vital Pill',       	  'v'=>31,  'action'=>'509223'),
					'10' => array(id=>'10', flashId=>'4',   'cname'=>'150W金币',	 	  'ename'=>'1,500,000 Silver',		  'v'=>54,  'action'=>'509671'),
					'11' => array(id=>'11', flashId=>'8',   'cname'=>'流星卷*2',	 	  'ename'=>'Meteor Scroll x 2',		  'v'=>77,  'action'=>'509673'),
					'12' => array(id=>'12', flashId=>'3',   'cname'=>'聚神丹*2', 	 	  'ename'=>'Exp Ball x 2',       	  'v'=>100, 'action'=>'509672'),
        ),    
	    '2' => array( 
					'13' => array(id=>'13', flashId=>'1',  'cname'=>'iPad4',     	  'ename'=>'an iPad4',        	  		        'v'=>0,    'action'=>'0几率'),
		     		'14' => array(id=>'14', flashId=>'8',  'cname'=>'练气+1',	     	  'ename'=>' a Chi Booster (+1)',               'v'=>0,    'action'=>'0几率',  ),
					'15' => array(id=>'15', flashId=>'12', 'cname'=>'赤练石+8',	      'ename'=>'a +8 Stone',	 					'v'=>0,    'action'=>'0几率', ),
					'16' => array(id=>'16', flashId=>'7',  'cname'=>'赤炼石+6',	      'ename'=>'a +6 Stone',			 			'v'=>0.2,  'action'=>'509361', 'limit_num'=>2, 'desc'=>'活动期间最多2个'),
					'17' => array(id=>'17', flashId=>'6',  'cname'=>'金刚坚钻', 	      'ename'=>'a Tough Drill',     				'v'=>0.5,  'action'=>'500615', 'limit_num'=>2, 'desc'=>'活动期间最多2个'),
		     	    '18' => array(id=>'18', flashId=>'5',  'cname'=>'李小龙双打火机',      'ename'=>'a Lee-Long Lighter',	 	        'v'=>1,    'action'=>'562161', 'limit_num'=>3, 'desc'=>'活动期间限定3个'),
	    		    '19' => array(id=>'19', flashId=>'13', 'cname'=>'500天石',	      'ename'=>'500 CPs',			      		    'v'=>2,    'action'=>'509274'),
					'20' => array(id=>'20', flashId=>'14', 'cname'=>'固化石，龙珠',       'ename'=>'a Permanent Stone & a Dragon Ball', 'v'=>3,    'action'=>'509691'),
					'21' => array(id=>'21', flashId=>'2',  'cname'=>'护心丹*5', 	      'ename'=>'Protection Pill x 5',       	    'v'=>8,    'action'=>'562138'),
					'22' => array(id=>'22', flashId=>'4',  'cname'=>'回气丹*2',	      'ename'=>'Vital Pill x 2',		 			'v'=>15,   'action'=>'562137'),
					'23' => array(id=>'23', flashId=>'3',  'cname'=>'赤练石+3',         'ename'=>'a +3 Stone', 					    'v'=>25,   'action'=>'500629'),
					'24' => array(id=>'24', flashId=>'10', 'cname'=>'200W金币',        'ename'=>'2,000,000 Silver',		 		    'v'=>50,   'action'=>'509685'),
					'25' => array(id=>'25', flashId=>'9',  'cname'=>'经验球*3',         'ename'=>'Exp Ball x 3',		  			    'v'=>75,   'action'=>'509476'),
					'26' => array(id=>'26', flashId=>'11', 'cname'=>'流星卷*3',	      'ename'=>'Meteor Scroll x 3',       	        'v'=>100,  'action'=>'509687'),
        ),  
	    '3' => array( 
					'27' => array(id=>'27', flashId=>'1',  'cname'=>'iPhone 6', 	  'ename'=>'an iPhone 6',        		   'v'=>0,    'action'=>'0几率'),
					'28' => array(id=>'28', flashId=>'12', 'cname'=>'练气+1',		 	  'ename'=>' a Chi Booster (+1)',		   'v'=>0,    'action'=>'0几率', ),
					'29' => array(id=>'29', flashId=>'5',  'cname'=>'自创+1',		  	  'ename'=>'a Jiang Hu Epic Ball (+1)',	   'v'=>0,    'action'=>'0几率', ),
					'30' => array(id=>'30', flashId=>'7',  'cname'=>'赤炼石+6', 		  'ename'=>'a +6 Stone',     	   		   'v'=>0.2,  'action'=>'509361', 'limit_num'=>3,'desc'=>'活动期间限定3个'),
					'31' => array(id=>'31', flashId=>'6',  'cname'=>'金刚坚钻',		  'ename'=>'a Tough Drill',		   		   'v'=>0.5,  'action'=>'500615', 'limit_num'=>3,'desc'=>'活动期间限定3个'),
					'32' => array(id=>'32', flashId=>'8',  'cname'=>'李小龙双节棍',	 	  'ename'=>'a Lee-Long Nunchakus',	  	   'v'=>1,    'action'=>'562160', 'limit_num'=>3,'desc'=>'活动期间最多3个'),
					'33' => array(id=>'33', flashId=>'14', 'cname'=>'700天石',		  'ename'=>'700 CPs',			      	   'v'=>2,    'action'=>'562140'),
					'34' => array(id=>'34', flashId=>'13', 'cname'=>'固化石*2',		  'ename'=>'Permanent Stone x 2',		   'v'=>3,    'action'=>'509620'),
					'35' => array(id=>'35', flashId=>'3',  'cname'=>'赤炼石+4', 		  'ename'=>'a +4 Stone',       	 		   'v'=>5,    'action'=>'509224'),
					'36' => array(id=>'36', flashId=>'4',  'cname'=>'夜叉技能书',	 	  'ename'=>'a Night Devil',		       	   'v'=>8,    'action'=>'560015'),
					'37' => array(id=>'37', flashId=>'10', 'cname'=>'250W金币',	 	  'ename'=>'2,500,000 Silver',		       'v'=>31,   'action'=>'560091'),
					'38' => array(id=>'38', flashId=>'11', 'cname'=>'大爆丹*1',	 	  'ename'=>'a Senior Training Pill',	   'v'=>54,   'action'=>'561664'),
					'39' => array(id=>'39', flashId=>'9',  'cname'=>'流星卷*4', 		  'ename'=>'Meteor Scroll x 4',       	   'v'=>77,   'action'=>'509679'),
					'40' => array(id=>'40', flashId=>'2',  'cname'=>'抽奖券*2',  	 	  'ename'=>'Lottery Ticket x 2', 		   'v'=>100,  'action'=>'562153'),
        ),           
	    '4' => array( 
					'41' => array(id=>'41', flashId=>'1',  'cname'=>'iPhone 6 Plus',  'ename'=>'an iPhone 6 Plus',        	    'v'=>0,    'action'=>'0几率'),
					'42' => array(id=>'42', flashId=>'12', 'cname'=>'练气+2',		  	  'ename'=>'a Chi Booster (+2)',		 	'v'=>0,    'action'=>'0几率' ),
					'43' => array(id=>'43', flashId=>'5',  'cname'=>'自创+2',		      'ename'=>'a Jiang Hu Epic Ball (+2)',	    'v'=>0,    'action'=>'0几率' ),
					'44' => array(id=>'44', flashId=>'8',  'cname'=>'李小龙雕塑',		  'ename'=>'a Lee-Long Statue',	 			'v'=>0.1,  'action'=>'562162', 'limit_num'=>1, 'desc'=>'活动期间最多1个'),
					'45' => array(id=>'45', flashId=>'7',  'cname'=>'赤炼石+6', 		  'ename'=>'a +6 Stone',     	 		    'v'=>0.3,  'action'=>'509361', 'limit_num'=>3, 'desc'=>'活动期间最多3个'),
					'46' => array(id=>'46', flashId=>'6',  'cname'=>'金刚坚钻',		  'ename'=>'a Tough Drill',		   		    'v'=>0.6,  'action'=>'500615', 'limit_num'=>3, 'desc'=>'活动期间最多3个'),
					'47' => array(id=>'47', flashId=>'2',  'cname'=>'极品玄元*1',	      'ename'=>'a Super Tortoise Gem',		    'v'=>1.6,  'action'=>'500614'),
					'48' => array(id=>'48', flashId=>'13', 'cname'=>'赤炼石+5',	      'ename'=>'a +5 Stone',		 			'v'=>2.6,  'action'=>'508204'),
					'49' => array(id=>'49', flashId=>'14', 'cname'=>'1000天石', 		  'ename'=>'1000 CPs',       	 		    'v'=>3.6,  'action'=>'509710'),
					'50' => array(id=>'50', flashId=>'3',  'cname'=>'固化石*3',		  'ename'=>'Permanent Stone x 3',		    'v'=>4.6,  'action'=>'562175'),
					'51' => array(id=>'51', flashId=>'4',  'cname'=>'小抽奖券礼包*3',     'ename'=>'Small Lottery Ticket Pack x 3', 'v'=>28,   'action'=>'562173'),
					'52' => array(id=>'52', flashId=>'10', 'cname'=>'500W金币',	 	  'ename'=>'5,000,000 Silver',		        'v'=>52,   'action'=>'500616'),
					'53' => array(id=>'53', flashId=>'11', 'cname'=>'大爆丹*2',	 	  'ename'=>'Senior Training Pill x 2',      'v'=>76,   'action'=>'562174'),
					'54' => array(id=>'54', flashId=>'9',  'cname'=>'流星卷*5', 		  'ename'=>'Meteor Scroll x 5',       	    'v'=>100,  'action'=>'560160'),
          ) 
	),
		
	'isCheckIp'       => false ,// 是否添加IP过滤，上线前需要更改为 false（不过滤IP） ,测试阶段 更改为 true 
	'lottery_credit'  => 10, // 抽奖的最低积分
	'prize_limit_num' => 500 // 抽奖的最低积分
);
