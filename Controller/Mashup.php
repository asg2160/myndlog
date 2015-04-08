<?php
class MashupController extends Controller {
	
function load($args) {

		if($GLOBALS['DefaultPage'] != 'Mashup') {
			loadUrl('Home');
		}
		
		$args['selectedTagName'] = 'Show-All';
		
		if($args['get']['tag'] && $args['get']['tag']!='Show-All') {
			$args['selectedTagName'] = $args['get']['tag'];
			$args['thoughtIDs'] = Thought::getByTag($args['get']['tag'], true, $GLOBALS['ThoughtsPerQuery']);
		} else {
			$args['thoughtIDs'] = Thought::getAll(true, $GLOBALS['ThoughtsPerQuery']);
		}
		
		//$args['tagNames'] = Tag::getAllNames();

		if($_COOKIE['rememberMeOnMyndLogEmail'] && $_COOKIE['rememberMeOnMyndLogPwd']) {
			$args['rmEmail'] = $_COOKIE['rememberMeOnMyndLogEmail'];
			$args['rmPassword'] = $_COOKIE['rememberMeOnMyndLogPwd'];
		}
		
		$this->view($args,'View/Mashup/Mashup.php');
	}
}
?>