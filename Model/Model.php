<?php

class Model {
	
	var $ID;
	var $dateAdded;
	var $data = array();
		
	function __construct() {
	
	}
	
	public function insert() {
		return DB::insert($this->table, $this->data);
	}
	
	public function delete() {
		return DB::delete($this->table, $this->ID);
	}
	
	public function setValue($column,$value) {
		$function = '_set'.$column;
		$this->data[$column] = method_exists($this,$function) ? call_user_func_array(array($this,$function),array($value)) : $value;
	}
	
	public function getValue($column) {
		$function = '_get'.$column;
		$value = method_exists($this,$function) ? call_user_func_array(array($this,$function),array($this->data[$column])) : $this->data[$column];
		
		return $value;
	}
	
	public function save() {
		if(method_exists($this,'preSaveCleanUp')) $this->preSaveCleanUp();
		
		if($this->ID) {
			DB::updateByID($this->table, $this->data, $this->ID);
		} else {
			if(!array_key_exists('DateAdded',$this->data)) {
				$this->data['DateAdded'] = time();
			}
			
			$data = $this->data;
			foreach($data as $key=>$val) {
				if(!in_array($key,$this->fields)) {
					unset($data[$key]);
				}
			}
			
			$this->ID = DB::insert($this->table, $data);
		}
		
		if(method_exists($this,'postSaveCleanUp')) $this->postSaveCleanUp();
		return $this->ID;
	}
}

?>