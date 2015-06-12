<?php
class GetLog extends Model {
	private $_tablename = GET_LOG_TABLE;
	/**
	 * 根据帐号获得玩家最后登陆时间
	 * @param $account
	 * @return unknown_type
	 */
	public function getInfobyId($id){
		$this->reader->clear();
		$this->reader->addTable($this->_tablename);
		$this->reader->addWhere('id', $id);
		$this->reader->addOrderBy('create_time', 'desc');
		$list = $this->reader->getRow();  
		return $list;
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
		if (!empty($where))  $this->reader->makeWhere($where);
		if (!empty($order))  $this->reader->makeOrder($order);
		switch($type){
			case 1:$list = $this->reader->getRow();break;
			case 2:$list = $this->reader->getList(null,$start,$end);break; 
			case 3:$list = $this->reader->getValue();break;
			case 4:$list = $this->reader->addField('*', 'rowcount',  Orm::OP_COUNT)->getValue();break;
		} 
		return $list;
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