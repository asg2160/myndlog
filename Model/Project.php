<?php

class Project extends Model {
	
	var $table = 'Project';
	var $fields = array('ID','Name','UserID','UserID','DateAdded');
	var $ID;
	var $name;
	var $userID;
	var $dateAdded;
	
	function __construct($projectID) {
		if(!$projectID) return;
		
		$results = DB::selectByID("Project", $projectID);
		$result = $results[0];
		$this->ID = $projectID;
		$this->name = $result['Name'];
		$this->userID = $result['UserID'];
		$this->dateAdded = $result['DateAdded'];
	}
	
	function _setName($value) {
		return $value ? $value : 'Default';
	}
	
	function add($userID,$name) {
		if($projectID = Project::exists($userID,$name)) return $projectID;

		$this->setValue('UserID',$userID);
		$this->setValue('Name',$name);
		$this->save();

		return $this->ID;
	}
	
	function delete($projectID) {
		return DB::delete('Project', $projectID);
	}
	
	public static function updateByID($data, $projectID) {
		if(empty($data) || !$projectID) return false;
		return DB::updateByID('Project', $data, $projectID);
	}
	
	public static function exists($userID, $name) {
		if(!$userID) return false;
		if(!$name) $name = 'Default';
		
		$result = DB::query("SELECT ID FROM Project WHERE UserID = '".$userID."' AND Name = '".$name."'");
		return (int)$result[0]['ID'];
	}
} 

?>