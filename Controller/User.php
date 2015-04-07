<?php
class UserController extends Controller {

	function load($args) { 
		
		// ajax in-line form validation
		if($args['post']['action']['check_unique']) {
		
			if($args['post']['user_name']) {
				echo json_encode(array('isUnique'=>!User::nameExists($args['post']['user_name'])));
			} else if($args['post']['email']) {
				echo json_encode(array('isUnique'=>!User::emailExists($args['post']['email'])));
			}
			
			die();
		}
	}
	
}
?>