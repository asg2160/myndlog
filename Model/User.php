<?php

class User extends Model {
	
	var $table = 'User';
	var $fields = array('ID','Email','Password','Status','DateAdded','UserName');
	var $email;
	var $password;
	var $userName;
	var $status;

	function __construct($userID) {
		if(!$userID) return;
		
		$results = DB::selectByID('User', $userID);
		$result = $results[0];
		$this->ID = $userID;
		$this->email = $result['Email'];
		$this->password = $result['Password'];
		$this->userName = $result['UserName'];
	}
	
	function add($args) {
				
		$user = array();
		
		if(!$args['email'] || !$args['password'] || !$args['user_name']) return false;
			
		$user['ID'] = null;
		$user['Email'] = $args['email'];
		$user['Password'] = crypt($args['password']);
		$user['Status'] =  $args['status'] ? $args['status'] : 1;
		$user['DateAdded'] = time();
		$user['UserName'] = str_replace(" ","-",$args['user_name']);
		
		if(User::exists($user['Email'],$user['Password'])) return false;
		if(User::nameExists($user['UserName'])) return false;
		
		$this->ID = $this->insert($user);
		
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
} 

?>