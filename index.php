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
	$url = explode('thought/index.php/',$_SERVER['REQUEST_URI']);
} else {
	if(strpos($_SERVER['REQUEST_URI'], "/index.php/") === false) {
		$url = explode('/',$_SERVER['REQUEST_URI']);
	} else	
		$url = explode('/index.php/',$_SERVER['REQUEST_URI']);
}

$GLOBALS['Page'] = count($url) > 1 ? $url[1] : '';
$GLOBALS['Page'] = str_replace("/","",strtok($GLOBALS['Page'],'?'));
$GLOBALS['Page'] = $GLOBALS['Page'];
if(!in_array($GLOBALS['Page'], $controllerNames)) $GLOBALS['Page'] = $GLOBALS['DefaultPage'];

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