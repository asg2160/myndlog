<?php
class EditProfileController extends Controller {

	function load($args) { 
	
		$this->loadJSInit(array('EditProfile','jquery.ui','jquery.validate'));
		$this->loadCSS(array('EditProfile','jquery.ui'));
		
		if(!$args) $args = array();
		
		if(!isAuth()) loadURL($GLOBALS['DefaultPage']);
		
		// edit user data
		if($args['post']['edit']) {
			$userData = array();
		
			$user = new User($_SESSION['UserID']);
			
			if($args['post']['user_name']) 
				$user->setValue('UserName',$args['post']['user_name']);
			
			if($args['post']['new_password'])
				$user->setValue('Password',$args['post']['new_password']);
			
			$user->save();
		}
		
		$user = new User($_SESSION['UserID']);
		$args['themes'] = Theme::getAllNames();
		$args['user_user_name'] = $user->userName;
		$args['user_theme_id'] = $user->themeID;
		$args['user_public_page_url'] = $user->getPublicPageURL();
		
		$this->view($args,'View/EditProfile/EditProfile.php');
	}
}
?>