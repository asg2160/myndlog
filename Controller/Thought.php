<?php
class ThoughtController extends Controller {
	function load($args) {
		if($args['get']['tid']) {
			
			$thoughtID = ($args['get']['tid']);
			$thought = new Thought($thoughtID);
			
			$user = new User($thought->userID);
			$args['thought_user_name'] = $user->userName;
			
			if(!$thought->visible) loadUrl('Home');
			
			$args['thoughtIDs'] = array('0'=>array('ID'=>$thoughtID));
			$args['publicPage'] = getURL('Page',array('uid'=>$user->ID));
			$args['userID'] = $user->ID;
			
			$this->view($args,'View/Thought/Thought.php');
			
		}
	}
}
?>