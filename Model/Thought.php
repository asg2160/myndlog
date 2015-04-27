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
	private static $defaultTag = 'Show-All';
	
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
	
	public function _setText($value) {
		$value = substr($value,0,LIMIT::$thought_text);
		if(!is_string($value)) $value = '';
		return $value;
	}
		
	public function _setTitle($value) {
		$value = substr($value,0,LIMIT::$thought_title);
		if(!is_string($value) || $value == '') $value = null;

		if(is_null($value)) $this->setValue('Visible',0);
		
		return $value;
	}
	
	public function _setTags($value) {
		$value = explode(",",cleanupTags($value));
		$value = array_slice($value,0,3);
		return array_slice($value,0,LIMIT::$tags_per_thought);
	}
	
	public function _setVisible($value) {
		$value = $this->title ? $value : 0;
		$value = is_null($value) ? 0 : $value;
		return $value;
	}
		
	function update($column,$value) {
		$this->setValue($column,$value);
		$this->save();
	}
	
	function add($userID,$text,$title,$tags,$visible) {
		$this->setValue('UserID',$userID);
		$this->setValue('Text',$text);
		$this->setValue('Title',$title);
		$this->setValue('Visible',$visible);
		$this->setValue('ProjectID',User::getDefaultProject($thought->getValue('UserID')));
		$this->setValue('Tags',$tags);
		$this->save();
	}

	function postSaveCleanUp() {
		$tags = $this->data['Tags'];
		foreach($tags as $tag) {
			
			// create tag if it does not already exist
			$tagObj = new Tag();
			$tagID = $tagObj->add($tag);
			
			if(!$tagID) continue;
			
			// create thought tag link if it does not already exist
			$thoughtTagObj = new ThoughtTag();
			$thoughtTagObj->add($this->ID,$tagID);
		}
		
		// delete tags not being used
		if($this->ID) {
			$this->deleteAllTagsExcept($tags);
		}
	}
	
	function deleteAllTagsExcept($tags) {
		ThoughtTag::deleteAllTagsExcept($this->ID, $tags);
	}
	
	function getByUser($userID, $projectID, $visible, $limit) {
		if(!$userID) return false;
		if(!$projectID) $projectID = User::getDefaultProject($userID);
		if(!$projectID) return false;
		
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;
		
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		
		$result = DB::query("SELECT ID FROM Thought WHERE UserID = ".$userID." AND ProjectID = ".$projectID.$visibleQuery." ORDER BY ID DESC ".$limitQuery, true);
		
		return $result;
	}
	
	function getByUserAndTag($userID, $projectID, $tagName, $visible, $limit) {
		if(!$userID) return false;
		if(!$projectID) $projectID = User::getDefaultProject($userID);
		if(!$projectID) return false;
		
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;
		
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		
		$result = DB::query("SELECT ThoughtID AS ID FROM ThoughtTag WHERE ThoughtID IN (SELECT ID FROM Thought WHERE UserID = ".$userID." AND ProjectID = ".$projectID.$visibleQuery.") AND TagID IN (SELECT ID FROM Tag WHERE Name='".$tagName."') ORDER BY ID DESC ".$limitQuery, true);
		
		return $result;
	}
	
	function getByTag($tagName, $visible, $limit) {
		if(!$tagName) return false;
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		if(!is_null($limit)) $limitQuery = " LIMIT ".$limit;	

		$result = DB::query("SELECT ThoughtID AS ID FROM ThoughtTag WHERE ThoughtID IN (SELECT ID FROM Thought WHERE 1 ".$visibleQuery.") AND TagID IN (SELECT ID FROM Tag WHERE Name='".$tagName."') ORDER BY ID DESC ".$limitQuery, true);
		
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
		if($tag == Thought::$defaultTag) $tag = '';
		
		if($tag) { 
			$tagID = Tag::getIDByName($tag);
			$tagQuery = " AND (SELECT COUNT(ID) FROM ThoughtTag WHERE ThoughtID = Thought.ID AND TagID = ".$tagID.") > 0 ";
		}
		
		$result = DB::query("SELECT ID FROM Thought WHERE ID > ".$thoughtID." AND UserID = ".$userID.$visibleQuery.$tagQuery." LIMIT ".$num, true);
		return $result;
	}
	
	public static function getXBeforeY($userID, $num, $thoughtID, $tag, $visible) {
		if(!$userID) return false;
		if(!$thoughtID) return false;
		if(!$num) $num = $GLOBALS['ThoughtsPerQuery'];
		if(!is_null($visible)) $visibleQuery = " AND Visible = ".($visible ? 1 : 0);
		if($tag == Thought::$defaultTag) $tag = '';

		if($tag) { 
			$tagID = Tag::getIDByName($tag);
			$tagQuery = " AND (SELECT COUNT(ID) FROM ThoughtTag WHERE ThoughtID = Thought.ID AND TagID = ".$tagID.") > 0 ";
		}
		
		$result = DB::query("SELECT ID FROM Thought WHERE ID < ".$thoughtID." AND UserID = ".$userID.$visibleQuery.$tagQuery." ORDER BY ID DESC LIMIT ".$num, true);
		return $result;
	}
	
	public static function getByUserAndKeyword($userID,$keyword) {
		if(!$userID) return false;
		if(!$keyword) return false;
		
		$result = DB::query("SELECT ID FROM Thought WHERE UserID = ".$userID." AND Text LIKE '%".$keyword."%' ORDER BY ID DESC", true);
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