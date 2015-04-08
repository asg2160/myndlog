<?php
session_start();

include_once "Globals.php";

// load all libraries
foreach (glob("Library/*.php") as $filename) {
    include_once $filename;
}

// load all model
include_once("Model/Model.php");
foreach (glob("Model/*.php") as $filename) {
    include_once $filename;
}
// Connects to your Database
DB::cxn();

// load all controllers
include_once "Controller/Controller.php";

$controllerNames = array();
foreach (glob("Controller/*.php") as $filename) {
    include_once $filename;
    $controllerName = str_replace(array('Controller/', '.php'), '', $filename);
    if($controllerName == 'Controller') continue;
    $controllerNames[] = $controllerName;
}

// parse url
if($GLOBALS['isDev']) {
	$url = explode('myndlog/index.php/',$_SERVER['REQUEST_URI']);
} else {
	if(strpos($_SERVER['REQUEST_URI'], "/index.php/") === false) {
		$url = explode('/',$_SERVER['REQUEST_URI']);
		foreach($url as $key=>$val) {
			if($key > 1) {
				$url[1] = $url[1].'/'.$val;
			}	
		}
		$url = array($url[0],$url[1]);
	} else	
		$url = explode('/index.php/',$_SERVER['REQUEST_URI']);
}

$pageString = count($url) > 1 ? $url[1] : '';
$pageString = strtok($pageString,'?');
$pageArray = array_filter(explode("/",$pageString));

if(!in_array(ucfirst($pageArray[0]), $controllerNames)) {
	if(User::nameExists($pageArray[0])) {
		$GLOBALS['Page'] = 'Page';
		
		// myndlog.com/abhi
		$_GET['user_name'] = $pageArray[0];		
	} else {
		loadURL($GLOBALS['DefaultPage']);
	}
} else {
	$GLOBALS['Page'] = ucfirst($pageArray[0]);
}

switch($GLOBALS['Page']) {
	case 'Page':
		// myndlog.com/abhi/tag/2do
		if($pageArray[1]) $_GET['tag'] = $pageArray[1];
	break;
	
	case 'Thought':
		// myndlog.com/thought/130
		if($pageArray[1]) $_GET['tid'] = $pageArray[1];
	break;
}

// load page
$args = array();
$args['post'] = $_POST;
$args['get'] = $_GET;
$controllerClassName = $GLOBALS['Page']."Controller";
$controller = new $controllerClassName();

$controller->load($args);

/* Test 
error_reporting(E_ALL); ini_set('display_errors', 1);
echo "start";

// add thoguht
$thought = array('text'=>'i delcare bankruptcy!!!','tags'=>'a,bb,cc,dd');
$t = new Thought();
echo "\n\n".$t->add($thought);

echo "done";
die();

var_dump(Thought::delete(1));
die();

*/
?>