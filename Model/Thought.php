<?php

class Thought extends Model {
	
	var $table = 'Thought';
	var $fields = array('ID','Text','UserID','DateAdded','ProjectID','Visible','Title');
	var $ID;
	var $text;
	var $tags;
	var $userID;
	var $dateAdded;
	var $visible;
	var $title;
	
	function __construct($thoughtID) {
		if(!$thoughtID) return;
		
		$results = DB::selectByID("Thought", $thoughtID);
		$result = $results[0];
		$this->ID = $thoughtID;
		$this->userID = $result['UserID'];
		$this->text = $result['Text'];
		$this->tags = implode(",",ThoughtTag::getTags($this->ID));
		$this->dateAdded = $result['DateAdded'];
		$this->projectID = $result['ProjectID'];
		$this->visible = $result['Visible'];
		$this->title = $result['Title'];
	}
	
	function delete($thoughtID) {
		$thought = new Thought($thoughtID);
		foreach($this->tags as $tag) {
			$tagID = Tag::getIDByName($tag); // CREATE FUNCTION
			$thoughtTagID = ThoughtTag::get($thoughtID, $tagID); // CREATE FUNCTION
			DB::delete('ThoughtTag', $thoughtTagID);
		}
		DB::delete('Thought', $thoughtID);
		
	}
	
	function add($args) {
		
		$thought = array();				
		$thought['ID'] = null;
		$thought['Text'] = substr($args['text'],0,1500);
		$thought['UserID'] = $args['userID'] ? $args['userID'] : $_SESSION['UserID'];
		$thought['DateAdded'] = time();
		$thought['ProjectID'] = $args['projectID'] ? $args['projectID'] : User::getDefaultProject($thought['UserID']);
		$thought['Visible'] = $args['visible'] ? $args['visible'] : 0;
		$thought['Title'] = $args['title'];

		if(!$thought['UserID'] || !$thought['ProjectID']) return false;
		
		$this->ID = $this->insert($thought);
		
		if(!$this->ID) return false;
				
		$tags = explode(",", cleanupTags($args['tags']));
		
		foreach($tags as $tag) {
			
			$tagArr = array('name'=>$tag);
			$tag = new Tag();
			$tagID = $tag->add($tagArr);
			
			if(!$tagID) continue;
			
			$thoughtTagArr = array('thoughtID'=>$this->ID, 'tagID'=>$tagID);
			$thoughtTag = new ThoughtTag();
			$thoughtTagID = $thoughtTag->add($thoughtTagArr);
		}
		
		return $this->ID;
	}
	
	function getByUser($userID, $projectID, $visible, $limit) {
		if(!$userID) return false;
		if(!$projectID) $projectID = User::getDefaultProject($userID);
		if(!$projectID) return false;
		
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;
		
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		
		$result = DB::query("SELECT ID FROM Thought WHERE UserID = ".$userID." AND ProjectID = ".$projectID.$visibleQuery." ORDER BY ID DESC ".$limitQuery);
		
		return $result;
	}
	
	function getByUserAndTag($userID, $projectID, $tagName, $visible, $limit) {
		if(!$userID) return false;
		if(!$projectID) $projectID = User::getDefaultProject($userID);
		if(!$projectID) return false;
		
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;
		
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		
		$result = DB::query("SELECT ThoughtID AS ID FROM ThoughtTag WHERE ThoughtID IN (SELECT ID FROM Thought WHERE UserID = ".$userID." AND ProjectID = ".$projectID.$visibleQuery.") AND TagID IN (SELECT ID FROM Tag WHERE Name='".$tagName."') ORDER BY ID DESC ".$limitQuery);
		
		return $result;
	}
	
	function getByTag($tagName, $visible, $limit) {
		if(!$tagName) return false;
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;	

		$result = DB::query("SELECT ThoughtID AS ID FROM ThoughtTag WHERE ThoughtID IN (SELECT ID FROM Thought WHERE 1 ".$visibleQuery.") AND TagID IN (SELECT ID FROM Tag WHERE Name='".$tagName."') ORDER BY ID DESC ".$limitQuery);
		
		return $result;
	}


	function getAll($visible, $limit) {
		if(!is_null($visible)) $visibleQuery = " Visible = ".($visible ? 1 : 0);
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;

		$result = DB::query("SELECT ID FROM Thought WHERE ".$visibleQuery.$limitQuery);
		return $result;
	}
	
	function updateByID($data, $thoughtID) {
		if(empty($data) || !$thoughtID) return false;
		return DB::updateByID('Thought', $data, $thoughtID);
	}
	
	public static function getXAfterY($userID, $num, $thoughtID, $tag, $visible) {
		if(!$userID) return false;
		if(!$thoughtID) return false;
		if(!$num) $num = $GLOBALS['ThoughtsPerQuery'];
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);

		if($tag) { 
			$tagID = Tag::getIDByName($tag);
			$tagQuery = " AND (SELECT COUNT(ID) FROM ThoughtTag WHERE ThoughtID = Thought.ID AND TagID = ".$tagID.") > 0 ";
		}
		
		$result = DB::query("SELECT ID FROM Thought WHERE ID > ".$thoughtID." AND UserID = ".$userID.$visibleQuery.$tagQuery." LIMIT ".$num);
		return $result;
	}
	
	public static function getXBeforeY($userID, $num, $thoughtID, $tag, $visible) {
		if(!$userID) return false;
		if(!$thoughtID) return false;
		if(!$num) $num = $GLOBALS['ThoughtsPerQuery'];
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);

		if($tag) { 
			$tagID = Tag::getIDByName($tag);
			$tagQuery = " AND (SELECT COUNT(ID) FROM ThoughtTag WHERE ThoughtID = Thought.ID AND TagID = ".$tagID.") > 0 ";
		}
		
		$result = DB::query("SELECT ID FROM Thought WHERE ID < ".$thoughtID." AND UserID = ".$userID.$visibleQuery.$tagQuery." ORDER BY ID DESC LIMIT ".$num);
		return $result;
	}
	
	public static function getByUserAndKeyword($userID,$keyword) {
		if(!$userID) return false;
		if(!$keyword) return false;
		
		$result = DB::query("SELECT ID FROM Thought WHERE UserID = ".$userID." AND Text LIKE '%".$keyword."%' ORDER BY ID DESC");
		return $result;
	}
	
	public static function get($visible = true, $limit) {
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		if(is_null($limit)) $limit = $GLOBALS['ThoughtsPerQuery'];
		$limitQuery = " LIMIT ".$limit;
		
		$result = DB::query("SELECT ID FROM Thought WHERE 1 ".$visibleQuery." ORDER BY ID DESC ".$limitQuery);
		return $result;
	}
} 

?>