<?php
class Player extends Model {
	private $_tablename = PLAYER_TABLE;
	/**
	 * 获取玩家排名
	 */
	public function getRank($usedCredit,$updateTime){
		$this->reader->clear();
		$this->reader->addTable($this->_tablename );
		$this->reader->addWhere('(');
		$this->reader->addWhere('credit_used',$usedCredit);
		$this->reader->addWhere('UNIX_TIMESTAMP(create_time)',"UNIX_TIMESTAMP('".$updateTime."')",Orm::OP_LT,Orm::DT_SQL);
		$this->reader->addWhere(')');
		$this->reader->addWhere('credit_used',$usedCredit,Orm::OP_GT,Orm::DT_AUTO,Orm::OP_OR);
		return $this->reader->addField('*', 'rank',  Orm::OP_COUNT)->getRow();break;
	}
	/**
	 * 
	 * @param unknown $where
	 * @param string $order
	 * @param number $type
	 * @param number $start
	 * @param number $end
	 * @return string
	 */
	public function query($where,$order=null,$type=1,$start=0,$end=0){
		$this->reader->clear();
		$this->reader->addTable($this->_tablename);
		if(!empty($where))$this->reader->makeWhere($where) ;
		if(!empty($order))$this->reader->makeOrder($order) ; 
		switch($type){
			case 1:$list = $this->reader->getRow();break;
			case 2:$list =  $this->reader->getList(null,$start,$end);break; 
			case 3:$list = $this->reader->getValue();break;
			case 4:$list = $this->reader->addField('*', 'rowcount',  Orm::OP_COUNT)->getValue();break;
		} 
		return $list;
	}

	/**
	 * 获取团员等级分布情况
	 * @param unknown $where
	 * @param string $order
	 * @param number $type
	 * @param number $start
	 * @param number $end
	 * @return string
	 */
	public function getMemberLevelCount($groupId,$metempsychosis,$level ){
		$this->reader->clear();
		$this->reader->addTable($this->_tablename );
		$this->reader->addWhere('group_id',$groupId);
		$this->reader->addWhere('(');
		$this->reader->addWhere('metempsychosis',$metempsychosis);
		$this->reader->addWhere('level',$level,Orm::OP_GE);
		$this->reader->addWhere(')');
		$this->reader->addWhere('metempsychosis',$metempsychosis,Orm::OP_GT,Orm::DT_AUTO,Orm::OP_OR);
		return $this->reader->addField('*', 'memberCount',  Orm::OP_COUNT)->getValue();break;
	}
	
	/**
	 * 插入
	 * @param unknown $value
	 * @return number
	 */
	public function insert($value){
		$this->reader->clear();
		$this->reader->addTable($this->_tablename);
		foreach($value as $k=>$v){
			if(is_array($v)){
				array_unshift($v, $k);
				call_user_func_array(array($this->reader, 'addValue'), $v);
			}else{
				$this->reader->addValue($k,$v);
			}
		}
		$this->reader->insert();
		return $this->reader->lastInsertId(); 
	}
	/**
	 *
	 * @param unknown $data
	 * @param unknown $where
	 * @return number
	 */
	public function update($data,$where){
		$this->reader->clear();
		$this->reader->addTable($this->_tablename);
		foreach($data as $k=>$v){
			if(is_array($v)){
				array_unshift($v, $k);
				call_user_func_array(array($this->reader, 'addValue'), $v);
			}else{
				$this->reader->addValue($k,$v);
			}
		}
		foreach($where as $k=>$v){
			if(is_array($v)){
				array_unshift($v, $k);
				call_user_func_array(array($this->reader, 'addWhere'), $v);
			}else{
				$this->reader->addWhere($k,$v);
			}
		}
		$list = $this->reader->update();
		return $list;
	}
	 
}