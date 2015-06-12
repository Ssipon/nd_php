<?php
class LoginLog extends Model {
	private $_tablename = LOGIN_LOG_TABLE;
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
		foreach($where as $k=>$v){
			if(is_array($v)){
				array_unshift($v, $k);
				call_user_func_array(array($this->reader, 'addWhere'), $v);
			}else{
				$this->reader->addWhere($k,$v);
			}
		}
		if(!empty($order)){
			foreach($order as $k=>$v){
				$this->reader->addOrderBy($k,$v);
			}
		}
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