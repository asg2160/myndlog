<?php
class UserController extends Controller {

	function load($args) { 
		
		// ajax in-line form validation			
		switch($args['post']['action']) {
		
			case 'check_unique';
				if($args['post']['user_name']) {
					echo json_encode(array('isUnique'=>!User::nameExists($args['post']['user_name'])));
				} else if($args['post']['email']) {
					echo json_encode(array('isUnique'=>!User::emailExists($args['post']['email'])));
				}
			break;
			
			case 'update_theme':
				$themeID = $args['post']['theme_id'];
				$user = new User($_SESSION['UserID']);
				$user->setValue('ThemeID',$themeID);
				$user->save();
			break;
			
			die();
		}
	}
	
}
?>