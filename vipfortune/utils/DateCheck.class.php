<?php
class DateCheck {
	/**
	 * 查看系统时间是否为有效的活动日期
	 * @param boolean $ischeckIP 是否对IP进行检测，true 检测，false 不检测，默认不检测
	 * @param string  $startDate 活动开始时间
	 * @param string  $endDate   活动结束时间
	 * @return  2  为当前活动还未开始，1  为活动已经结束
	 */
	public static function isValid( $startDate , $endDate , $ischeckIP = false ){
		
		if ($ischeckIP) {
			if ( !in_array( App::ip(), array("127.0.0.1",'192.168.62.32','218.5.2.219','220.250.21.82')))
		    return 2; 
		}
		
		$now = strtotime ( "now" ); 
		if ( $startDate > $now ) {
			return 2 ;
		}else if ( $now > $endDate) {
			return 1 ;
		}else{
			return 0 ;
		}
	}
	
	/**
	 * 查看系统时间是否在此时间之后
	 * @param  boolean $ischeckIP 是否对IP进行检测，true 检测，false 不检测，默认不检测
	 * @param  string  $time      检测时间
	 * @return boolean true       当前时间大于$time
	 */
	public static function isAfter( $time , $ischeckIP = false ){
		
		if ($ischeckIP) {
			if ( !in_array( App::ip(), array('218.5.2.219','220.250.21.82')))
		    return false ;
		}
		
		$now = strtotime ( "now" ); 
		if ( $time < $now ) 
			return true ;
		 else 
			return false ;
		 
	}
	/**
	 * 查看系统时间是否为有效的活动时间
	 * @param  boolean $ischeckIP 是否对IP进行检测，true 检测，false 不检测，默认不检测
	 * @param  string  $startTiem 活动开始时间
	 * @param  string  $endTime   活动结束时间
	 * @return boolean  true 为有效的活动时间，false 为无效的活动时间
	 */
	public static function isValidTime( $startTime , $endTime , $ischeckIP = false ){
		
		if ($ischeckIP) {
			if ( !in_array( App::ip(), array('218.5.2.219','220.250.21.82')))
		    return false;  
		}
		$time = time();
		if ( $startTime < $time && $time < $endTime)  
			return true ;
		return false ;
	}	 
	
}