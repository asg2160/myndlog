<?php

class Model {
	
	var $ID;
	var $dateAdded;
		
	function __construct() {
	
	}
	
	public function insert($valuesArray) {
		return DB::insert($this->table, $valuesArray);
	}
	
	public function delete() {
		return DB::delete($this->table, $this->ID);
	}
}

?>