<?php
class SignInController extends Controller {

	function load($args) {		
		
		$this->loadJSInit(array('SignIn','Home','jquery.ui','jquery.validate','jquery.slimscroll'));
		$this->loadCSS(array('SignIn_Register','jquery.ui'));
		
		if($args['get']['so']) {
			unAuth();
			loadURL($GLOBALS['DefaultPage']);
		}
		
		if($args['post']['sign_in']) {
			$_SESSION['UserID'] = (int)User::exists($args['post']['email'], $args['post']['password']);
			setcookie('LoggedInUserID', $_SESSION['UserID'], time() + (86400 * 30 * 365), "/");
		}
		
		if(isAuth()) {
			loadURL('Home');
		} else {
			
			if($args['post']['sign_in']) {
				$error = 'sign_in_invalid';
			}
			
			$args['selectedTagName'] = 'Show-All';
			$args['userID'] = 1;
			$projectID = User::getDefaultProject($args['userID']);
			
			$args['get']['tag'] = urldecode($args['get']['tag']);	
			if($args['get']['tag'] && $args['get']['tag']!='Show-All') {
				$args['selectedTagName'] = $args['get']['tag'];
				$args['thoughtIDs'] = Thought::getByUserAndTag($args['userID'],$projectID,$args['get']['tag'], true, $GLOBALS['ThoughtsPerQuery']);
			} else {
				$args['thoughtIDs'] = Thought::getByUser($args['userID'],$projectID, true, $GLOBALS['ThoughtsPerQuery']);
			}
			
			$args['tagNames'] = Tag::getAllNamesByUser($args['userID'], true);
			
			if($_COOKIE['rememberMeOnMyndLogEmail'] && $_COOKIE['rememberMeOnMyndLogPwd']) {
				$args['rmEmail'] = $_COOKIE['rememberMeOnMyndLogEmail'];
				$args['rmPassword'] = $_COOKIE['rememberMeOnMyndLogPwd'];
			}
			
			$this->view($args,'View/SignIn/Main.php');
		}
	}
}
?>