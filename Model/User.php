<?php

class User extends Model {
	
	var $table = 'User';
	var $fields = array('ID','Email','Password','Status','DateAdded','UserName','ThemeID');
	var $email;
	var $password;
	var $userName;
	var $status;
	var $themeID;

	function __construct($userID) {
		if(!$userID) return;
		
		$results = DB::selectByID('User', $userID);
		$result = $results[0];
		$this->ID = $userID;
		$this->email = $result['Email'];
		$this->password = $result['Password'];
		$this->userName = $result['UserName'];
		$this->themeID = $result['ThemeID'];
	}
	
	public static function getThemeID($userID) {
		if(!$userID) return Theme::getDefaultID();
		
		$user = new User($userID);
		return $user->themeID;
	}
	
	function _setPassword($value) {
		return crypt($value);
	}
	
	function _setStatus($value) {
		if(!is_int($value)) $value = 1;
		return $value;
	}
	
	function _setUserName($value) {
		$value = str_replace(" ","-",$value);
		return $value;
	}
	
	function add($email,$password,$userName,$status) {
		
		if(User::exists($email,$password)) return false;
		if(User::emailExists($userName)) return false;
		if(User::nameExists($userName)) return false;
				
		$this->setValue('Email',$email);
		$this->setValue('Password',$password);
		$this->setValue('Status',$status);
		$this->setValue('UserName',$userName);
		
		$this->save();
		
		return $this->ID;
	}
	
	function getDefaultProject($userID) {
		$result = DB::query("SELECT ID FROM Project WHERE UserID = ".$userID." LIMIT 1");
		return $result[0]['ID'];
	}
	
	function emailExists($email) {
		$result = DB::query("SELECT ID FROM User WHERE Email = '".$email."'");
		return (int)$result[0]['ID'];
	}
	
	function updateByID($data, $userID) {
		if(!$data || empty($data) || !$userID) return false;
		if($data['Password']) $data['Password'] = crypt($data['Password']);
		return DB::updateByID('User', $data, $userID);
	}
	
	function searchSummary() {
		$result = DB::query("SELECT ID, Email, Name, About, DocumentID FROM User WHERE IsLender = 0");
		return $result;
	}
	
	function getPublicPageURL() {
		return getURL(urlencode($this->userName));
	}
	
	public static function exists($email, $password) {
		if($_SESSION['UserID']) return $_SESSION['UserID'];
		if(!$email || !$password) return false;
		$result = DB::query("SELECT ID,Password FROM User WHERE Email = '".$email."' LIMIT 1");
	
		if($result[0]['Password'] == crypt($password, $result[0]['Password'])) 
			return (int)$result[0]['ID'];
		else 
			return false;
	}
	
	public static function nameExists($userName) {
		return User::getIDByUsername($userName);
	}

	public static function getIDByUsername($userName) {
		if(!$userName) return false;

		$result = DB::query("SELECT ID FROM User WHERE UserName = '".$userName."'");
		return (int)$result[0]['ID'];
	}
	
	public static function getThoughtsGroupedByTag($userID, $visible) {
		if(!$userID) return;
		$visibleQuery = is_null($visible) ? '' : ' AND Visible = '.($visible ? 1 : 0).' ';
		
		$results = DB::query("SELECT Max(ThoughtID) AS ThoughtID, TagID FROM ThoughtTag WHERE ThoughtID IN (SELECT ID FROM Thought WHERE UserID = ".$userID.$visibleQuery.") GROUP BY TagID ORDER BY DateAdded");
		
		return $results;
	}
} 

?>