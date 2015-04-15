<?php

class ThoughtTag extends Model {
	
	var $table = 'ThoughtTag';
	var $fields = array('ID','ThoughtID','TagID','DateAdded');
	var $ID;
	var $thoughtID;
	var $tagID;
	var $dateAdded;
	
	function __construct($tagID) {
		if(!$tagID) return;
		
		$results = DB::selectByID("ThoughtTag", $tagID);
		$result = $results[0];
		$this->ID = $tagID;
		$this->thoughtID = $result['ThoughtID'];
		$this->tagID = $result['TagID'];
		$this->dateAdded = $result['DateAdded'];
	}
	
	function delete($tagID) {
		return DB::delete('ThoughtTag', $tag);
	}
	
	function add($thoughtID,$tagID) {
		if(!$thoughtID) return false;
		if(!$tagID) return false;
		
		$thoughtTagID = ThoughtTag::exists($thoughtID,$tagID);
		
		if(!$thoughtTagID) {
			$this->setValue('ThoughtID',$thoughtID);
			$this->setValue('TagID',$tagID);
			$thoughtTagID = $this->save();
		}
		return $thoughtTagID;
	}
	
	public static function deleteAllTagsExcept($thoughtID, $tags) {
		$query = "DELETE FROM ThoughtTag WHERE ThoughtID = ".$thoughtID." AND TagID NOT IN (SELECT ID FROM Tag WHERE Name IN ".arrayToValueString($tags).");";
		DB::query($query);
	}
	
	public static function exists($thoughtID,$tagID) {
		if(!$thoughtID) return false;
		if(!$tagID) return false;
		
		$result = DB::query("SELECT ID FROM ThoughtTag WHERE ThoughtID = ".$thoughtID." AND TagID = ".$tagID);
		return (int)$result[0]['ID'];
	}
	
	public static function getTags($thoughtID) {
		if(!$thoughtID) return false;
		
		$tagNames = array();
		$results = DB::query("SELECT TagID FROM ThoughtTag WHERE ThoughtID = ".$thoughtID);

		foreach($results as $result) {
			$tag = new Tag($result['TagID']);
			$tagNames[] = $tag->name;
		}
	
		return $tagNames;
	}
	
	public static function deleteByThought($thoughtID) {
		if(!$thoughtID) return;
		DB::query("DELETE FROM ThoughtTag WHERE ThoughtID = ".$thoughtID);
	}
} 

?>