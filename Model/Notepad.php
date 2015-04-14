<?php

class Notepad extends Model {
	
	var $table = 'Notepad';
	var $fields = array('ID','Notes','UserID','DateAdded');
	var $ID;
	var $notes;
	var $userID;
	var $dateAdded;
	
	function __construct($notepadID) {
		if(!$notepadID) return;
		
		$results = DB::selectByID("Notepad", $notepadID);
		$result = $results[0];
		$this->ID = $notepadID;
		$this->notes = $result['Notes'];
		$this->userID = $result['UserID'];
		$this->dateAdded = $result['DateAdded'];
	}
	
	function save($args) {
		
		if(!$args['userID']) return false;
		
		$Notepad = array();				
		$Notepad['ID'] = null;
		$Notepad['Notes'] = $args['notes'];
		$Notepad['UserID'] = $args['userID'];
		$Notepad['DateAdded'] = time();	
		
		if($notepadID = Notepad::exists($Notepad['UserID'])) return $notepadID;
		
		$this->ID = $this->insert($Notepad);
		
		return $this->ID;
	}
	
	public static function exists($userID) {
		if(!$userID) return false;
		
		$result = DB::query("SELECT ID FROM Notepad WHERE UserID = ".$userID);
		return (int)$result[0]['ID'];
	}
	
	public static function updateByID($data, $notepadID) {
		if(empty($data) || !$notepadID) return false;
		return DB::updateByID('Notepad', $data, $notepadID);
	}
	
	public static function getIDByUserID($userID) {
		return Notepad::exists($userID);
	}
} 

?>