<?php
class PageController extends Controller {
	
	function load($args) {
		$args['thought_user_name'] = urldecode($args['get']['user_name']);
		
		if($args['get']['user_name']) {
		
			$args['userID'] = User::getIDByUsername($args['thought_user_name']);
			$projectID = User::getDefaultProject($args['userID']);
					
			$args['selectedTagName'] = 'Show-All';
			$withTag = " ";
			
			$args['get']['tag'] = urldecode($args['get']['tag']);	
			if($args['get']['tag'] && $args['get']['tag']!='Show-All') {
				$args['selectedTagName'] = $args['get']['tag'];
				$args['thoughtIDs'] = Thought::getByUserAndTag($args['userID'],$projectID,$args['get']['tag'], true, $GLOBALS['ThoughtsPerQuery']);
				$withTag = " with tag '".$args['selectedTagName']."' ";
			} else {
				$args['thoughtIDs'] = Thought::getByUser($args['userID'],$projectID, true, $GLOBALS['ThoughtsPerQuery']);
			}
			
			$args['tagNames'] = Tag::getAllNamesByUser($args['userID'], true);
			
			if(!isAuth() || $args['userID'] != $_SESSION['UserID']) {
				$args['message'] = $args['thought_user_name']." has not made any thoughts".$withTag."public.";
			} else {
				$args['message'] = "You have not made any thoughts".$withTag."public.";
			}
		
			$this->view($args,'View/Page/Page.php');
		}
	}
}
?>