<?php
class EditProfileController extends Controller {

	function load($args) { 
	
		$this->loadJS(array('EditProfile','jquery.ui','jquery.validate'));
		$this->loadCSS(array('EditProfile','jquery.ui'));
		
		if(!$args) $args = array();
		
		if(!isAuth()) loadURL($GLOBALS['DefaultPage']);
		
		// edit user data
		if($args['post']['edit']) {
			$userData = array();
		
			if($args['post']['user_name']) $userData['UserName'] = str_replace(" ","-",$args['post']['user_name']);
			if($args['post']['new_password']) $userData['Password'] = $args['post']['new_password'];
			User::updateByID($userData,$_SESSION['UserID']);
		}
		
		$args['user'] = new User($_SESSION['UserID']);
		
		$this->view($args,'View/EditProfile/EditProfile.php');
	}
}
?>