<?php
class ArticleController extends Controller {
	
	function load($args) {
		$thoughtID = ($args['get']['tid']);
		$thought = new Thought($thoughtID);
				
		if(!$thought->visible) loadUrl('Home');
		
		$args['thoughtIDs'] = array('0'=>array('ID'=>$thoughtID));
		$args['publicPage'] = getURL('Page',array('uid'=>$thought->userID));
		$args['userID'] = $thought->userID;
		$this->view($args,'View/Article/Article.php');
	}
}
?>