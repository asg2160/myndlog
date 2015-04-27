<?php
class RegisterController extends Controller {

	function load($args) {
		$this->loadJSInit(array('Register','Home','jquery.ui','jquery.validate'));
		$this->loadCSS(array('SignIn_Register','jquery.ui'));
		
		if(isAuth()) {
			loadURL('Home');
		}
		
		if($args['post']['submit']) {
			// check if email exists
			if(User::emailExists($args['post']['email'])) {
				$error = 'register_unique';
			} else {
			
				// create user
				$user = new User();
				$userID = $user->add($args['post']['email'],$args['post']['password'],$args['post']['user_name']);

				if($userID) {
				
					// create project
					$project = new Project();
					$projectID = $project->add($userID);
					
					// create notepad
					$notepad = new Notepad();
					$notepadID = $notepad->add($userID);
					
					$_SESSION['UserID'] = $userID;
					
					// go to user page
					loadURL('Home');
				}
			}
		} 

		if(!$args['post']['submit'] || $error) {
			
			$args['selectedTagName'] = $args['get']['tag'];
			$args['userID'] = 1;
			$this->view($args,'View/Register/Main.php');
		}
	}
}
?>