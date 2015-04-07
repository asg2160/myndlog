<?php
class RegisterController extends Controller {

	function load($args) {
		
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
				$userID = $user->add($args['post']);
				
				if($userID) {
					$project = new Project();
					$projectID = $project->add(array('userID'=>$userID));
					
					$notepad = new Notepad();
					$notepadID = $notepad->add(array('userID'=>$userID));
					
					$_SESSION['UserID'] = $userID;
	
					// go to user page
					loadURL('Home');
				}
			}
		} 

		if(!$args['post']['submit'] || $error) {
			// load register view
			// show articles : 
			
			$args['selectedTagName'] = 'Show All';
												
			$args['userID'] = 1;
			
			if($args['get']['tag'] && $args['get']['tag']!='Show All') {
				$args['selectedTagName'] = $args['get']['tag'];
				$args['thoughtIDs'] = Thought::getByUserAndTag($args['userID'],null,$args['get']['tag'],$GLOBALS['ThoughtsPerQuery']);
			} else {
				$args['thoughtIDs'] = Thought::getByUser($args['userID'],null,$GLOBALS['ThoughtsPerQuery']);
			}
			
			$args['tagNames'] = Tag::getAllNamesByUser($args['userID']);
							
			$this->view($args,'View/Register/Main.php');
		}
	}
}
?>