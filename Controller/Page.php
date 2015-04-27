<?php
class PageController extends Controller {
	
	var $thoughtUserID;
	
	function load($args) {
		
		$this->loadJSInit(array('Home','jquery.ui'));
		$this->loadCSS(array('Page'));
		
		$args['thought_user_name'] = urldecode($args['get']['user_name']);

		if($args['get']['user_name']) {
			
			$args['userID'] = User::getIDByUsername($args['thought_user_name']);
			
			// set user id whose page is being viewed
			$this->thoughtUserID = $args['userID'];
			
			$args['selectedTagName'] = $args['get']['tag'];
			$projectID = User::getDefaultProject($args['userID']);
			
			$this->view($args,'View/Page/Page.php');
		}
	}
	
	function calculateThemeID() {
		$this->themeID = User::getThemeID($this->thoughtUserID);
	}
}
?>