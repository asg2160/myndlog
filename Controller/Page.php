<?php
class PageController extends Controller {
	
	function load($args) {
				
		//$args['userID'] = simple_decrypt($args['get']['uid']);
		$userName = urldecode($args['get']['name']);
		$args['userID'] = User::getIDByUsername($userName);
		$projectID = User::getDefaultProject($args['userID']);
				
		$args['selectedTagName'] = 'Show All';
			
		if($args['get']['tag'] && $args['get']['tag']!='Show All') {
			$args['selectedTagName'] = $args['get']['tag'];
			$args['thoughtIDs'] = Thought::getByUserAndTag($args['userID'],$projectID,$args['get']['tag'], true, $GLOBALS['ThoughtsPerQuery']);
		} else {
			$args['thoughtIDs'] = Thought::getByUser($args['userID'],$projectID, true, $GLOBALS['ThoughtsPerQuery']);
		}
		
		$args['tagNames'] = Tag::getAllNamesByUser($args['userID'], true);
		
		if(!isAuth() || $args['userID'] != $_SESSION['UserID']) {
			$args['message'] = $userName." has not made any thought public.";
		} else {
			$args['message'] = "You have not made any thoughts public.";
		}
	
		$this->view($args,'View/Page/Page.php');
	}
}
?>