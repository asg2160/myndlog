<?php
class Controller extends DB {
	
	function __construct() {

	}
	
	function load($args) {
		$this->view($args);
	}
	
	function view($args, $filenames) {
		if(!$args['post']['isAjax'] && !$args['get']['isAjax']) include_once("View/Header.php");
		
		if(!is_array($filenames)) $filenames = array($filenames);
	
		foreach($filenames as $filename) {
			include_once($filename);
		}
		
		if(!$args['post']['isAjax'] && !$args['get']['isAjax']) include_once("View/Footer.php");
	}
	
	function standaloneView($args, $filenames) {
		if(!is_array($filenames)) $filenames = array($filenames);
	
		foreach($filenames as $filename) {
			include_once($filename);
		}
	}
}
?>