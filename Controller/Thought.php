<?php
class ThoughtController extends Controller {

	var $thoughtUserID;

	function load($args) {
		
		$this->loadJSInit(array('jquery.slimscroll'));
		$this->loadCSS(array('Thought'));
		
		if($args['get']['tid']) {
			
			$thoughtID = ($args['get']['tid']);
			$thought = new Thought($thoughtID);
			
			// set user id whose page is being viewed
			$this->thoughtUserID = $thought->userID;
			
			$user = new User($thought->userID);
			$args['thought_user_id'] = $user->ID;
			$args['thought_user_name'] = $user->userName;
			
			if(!$thought->visible) loadUrl('Home');
			
			$args['thoughtIDs'] = array($thoughtID);
			$args['publicPage'] = getURL('Page',array('uid'=>$user->ID));
			
			$args['selectedTagName'] = $args['get']['tag'];
			$args['userID'] = $user->ID;
			$args['page_name'] = 'Thought';
			$args['tagNames'] = Tag::getAllNamesByUser($user->ID, true);
			$this->view($args,'View/Thought/Thought.php');
		}
	}
	
	function calculateThemeID() {
		$this->themeID = User::getThemeID($this->thoughtUserID);
	}
}
?>