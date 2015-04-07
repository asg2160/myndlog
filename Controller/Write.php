<?php
class WriteController extends Controller {

	function load($args) {
		
		if($args['post']['submit']) {
		
			switch($args['post']['action']) {
				
				case 'delete':
					// delete user
					$thought->delete($args['post']['id']);
				break;
			
				case 'add':
					// create user
					$thought = new Thought();
					$thoughtID = $thought->add($args['post']);
				break;
			}
						
			// go to user page
			loadURL('Home');
		}
	}
}
?>