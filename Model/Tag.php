<?php

class Tag extends Model {
	
	var $table = 'Tag';
	var $fields = array('ID','Name','DateAdded');
	var $ID;
	var $name;
	var $userID;
	var $dateAdded;
	
	function __construct($tagID) {
		if(!$tagID) return;
		
		$results = DB::selectByID("Tag", $tagID);
		$result = $results[0];
		$this->ID = $tagID;
		$this->name = $result['Name'];
		$this->dateAdded = $result['DateAdded'];
	}
	
	function delete($tagID) {
		return DB::delete('Tag', $tag);
	}
	
	function add($args) {
	
		if(!$args['name']) return false;
				
		$tag = array();				
		$tag['ID'] = null;
		$tag['Name'] = $args['name'];
		$tag['DateAdded'] = time();
		
		if($tagID = Tag::exists($tag['Name'])) return $tagID;
		
		$this->ID = $this->insert($tag);
		return $this->ID;
	}
	
	public static function exists($name) {
		if(!$name) return false;
		
		return Tag::getIDByName($name);
	}
	
	public static function getAllNamesByUser($userID,$visible) {
		if(!$userID) return null;
		if(!is_null($visible)) {
			$visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		}
		
		$query = "SELECT Name FROM Tag WHERE ID IN (SELECT TagID FROM ThoughtTag WHERE ThoughtID IN (SELECT ID FROM Thought WHERE UserID = ".$userID.$visibleQuery."));";
		
		$results = DB::query($query);
		return $results;
	}
	
	public static function getIDByName($name) {
		if(!$name) return false;
		
		$result = DB::query("SELECT ID FROM Tag WHERE Name = '".$name."'");
		return $result[0]['ID'];
	}
	
	public static function getColorByName($name) {
		if(!$name) return false;
		
		$result = DB::query("SELECT Color FROM Tag WHERE Name = '".$name."'");
		return $result[0]['Color'];
	}
} 

?>