<?php
class Controller extends DB {
	
	public static $isAjax = false;
	protected $jsFiles;
	public $themeID;
	
	function __construct() {
		$this->loadJSInit(array('jquery','Main','jquery.cookie'));
		$this->loadCSS(array('Theme','Light','Main','Dialog','Header','Footer'));
	}
	
	function load($args) {
		$this->view($args);
	}
	
	function loadCSS($filenames) {
		if(Controller::$isAjax) return false;
		if(!is_array($filenames)) return false;
		if(empty($filenames)) return false;
		
		foreach($filenames as $filename) {
			echo "<style>";
			if($GLOBALS['isDev'])
				include_once "Library/CSS/".$filename.".css";
			else
				include_once "Library/CSS/".$filename.".".$GLOBALS['version'].".min.css";
			echo "</style>";
		}
	}
	
	function loadJSInit($filenames) {
		if(!$filenames || empty($filenames)) return;
		
		if(!empty($this->jsFiles)) 
			$this->jsFiles = array_merge($this->jsFiles, $filenames);
		else
			$this->jsFiles = $filenames;
	}
	
	function loadJSComplete() {
		$filenames = $this->jsFiles;

		if(Controller::$isAjax) return false;
		if(!is_array($filenames)) return false;
		if(empty($filenames)) return false;
		
		foreach($filenames as $filename) {
			echo "<script type='text/javascript'>";
			if($GLOBALS['isDev'])
				include_once "Library/JS/".$filename.".js";
			else
				include_once "Library/JS/".$filename.".".$GLOBALS['version'].".min.js";
			echo "</script>";
		}
	}
	
	function calculateThemeID() {
		$this->themeID = User::getThemeID($_SESSION['UserID']);
	}
	
	function initView() {
		$this->calculateThemeID();
		loadThemeInSession($this->themeID);
	}
	
	function view($args, $filenames) {
		$this->initView();
		
		if(!Controller::$isAjax) echo $_SESSION['Theme'];
		
		if(!Controller::$isAjax) include_once("View/Header.php");
		
		if(!is_array($filenames)) $filenames = array($filenames);
		foreach($filenames as $filename) {
			include_once($filename);
		}
		
		if(!Controller::$isAjax) include_once("View/Footer.php");
	}
	
	function viewString($args, $filenames) {
		$this->initView();
		
		$args['view_string'] = true;
		
		if(!Controller::$isAjax) {
			echo $_SESSION['Theme'];
			$viewString = $view;
		}

		if(!Controller::$isAjax) {
			include_once("View/Header_.php");
			$viewString .= $view;
		}
		
		if(!is_array($filenames)) $filenames = array($filenames);
				
		foreach($filenames as $filename) {
			include_once($filename);
			$viewString .= $view;
		}
		
		if(!Controller::$isAjax) {
			include_once("View/Footer.php");
			$viewString .= $view;
		}
		
		return $viewString;
	}
}
?>