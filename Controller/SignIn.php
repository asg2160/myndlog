<?php
class SignInController extends Controller {

	function load($args) {		
		
		$this->loadJSInit(array('SignIn_Register','Home','jquery.ui','jquery.validate'));
		$this->loadCSS(array('SignIn_Register','jquery.ui'));
		
		if($args['get']['so']) {
			unAuth();
			loadURL($GLOBALS['DefaultPage']);
		}
		
		if($args['post']['sign_in']) {
			$_SESSION['UserID'] = (int)User::exists($args['post']['email'], $args['post']['password']);
			auth($_SESSION['UserID']);
		}
		
		if(isAuth()) {
			loadURL('Home');
		} else {
			
			if($args['post']['sign_in']) {
				$error = 'sign_in_invalid';
			}
			
			if($_COOKIE['rememberMeOnMyndLogEmail'] && $_COOKIE['rememberMeOnMyndLogPwd']) {
				$args['rmEmail'] = $_COOKIE['rememberMeOnMyndLogEmail'];
				$args['rmPassword'] = $_COOKIE['rememberMeOnMyndLogPwd'];
			}
			
			$args['selectedTagName'] = $args['get']['tag'];
			$args['userID'] = 1;
			$this->view($args,'View/SignIn/Main.php');
		}
	}
}
?>