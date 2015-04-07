<?php
class SignInController extends Controller {

	function load($args) {
		
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
			
			// show articles : 
			if($args['post']['sign_in']) {
				$error = 'sign_in_invalid';
			}
			
			$args['selectedTagName'] = 'Show All';
			
			$args['userID'] = 1;
			
			if($args['get']['tag'] && $args['get']['tag']!='Show All') {
				$args['selectedTagName'] = $args['get']['tag'];
				$args['thoughtIDs'] = Thought::getByUserAndTag($args['userID'],null,$args['get']['tag'],$GLOBALS['ThoughtsPerQuery']);
			} else {
				$args['thoughtIDs'] = Thought::getByUser($args['userID'],null,$GLOBALS['ThoughtsPerQuery']);
			}
			
			$args['tagNames'] = Tag::getAllNamesByUser($args['userID']);
			
			if($_COOKIE['rememberMeOnMyndLogEmail'] && $_COOKIE['rememberMeOnMyndLogPwd']) {
				$args['rmEmail'] = $_COOKIE['rememberMeOnMyndLogEmail'];
				$args['rmPassword'] = $_COOKIE['rememberMeOnMyndLogPwd'];
			}
				
			$this->view($args,'View/SignIn/Main.php');
		}
	}
}
?>