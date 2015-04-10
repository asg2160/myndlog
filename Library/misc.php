<?php
function currentURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}

function mapToSQL($str) {
	$str = is_string($str) ? "'".mysqli_real_escape_string(DB::cxn(), $str)."'" : $str;
	$str = is_null($str) ? 'null' : $str;
	return $str;
}

function replaceParamInURL($key,$val,$url) {
	if(!$url) $url = currentURL();

	if(strpos($url,$key."=") !== false) {
		$url = preg_replace('/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
		$url = substr($url, 0, -1); 
	}
	
	$url = $url.((strpos($url,"?") === false) ? '?' : '&').$key.'='.$val;

	return $url;
}

function arrayToValueString($valueArray) {

	$valueArray = array_map('mapToSQL', $valueArray);
	$valueString .= '('.implode(',', $valueArray).')';

	return $valueString;
}

function getURL($controller,$get){
	$url = ($GLOBALS['isDev'] ? $_SERVER['SCRIPT_NAME'] : '')."/".$controller;
	
	if($get) {
		$url = $url.'?';
		foreach($get as $name=>$val) {
			$url = $url.$name.'='.$val.'&';
		}
		$url = substr($url,0,-1);
	}
	return $url;
}

function loadURL($url) {
	echo "<script>";
	echo "window.location='".getURL($url)."';";
	echo "</script>";
	/*error_reporting(E_ALL); ini_set('display_errors', 1);
	header("Location: ".getURL($url));
	die();*/
}

function isAuth() {
	if(!$_SESSION['UserID'] && $_COOKIE['LoggedInUserID'] && $_COOKIE['LoggedInUserID'] != "null") $_SESSION['UserID'] = $_COOKIE['LoggedInUserID'];
	return $_SESSION['UserID'];
}

function unAuth() {
	unset($_SESSION['UserID']);
	setcookie('LoggedInUserID', 0, 1, "/");
	unset($_COOKIE['LoggedInUserID']);
}

function die_dump($chars) {
	var_dump($chars);
	die();
}

function cleanupTags($tags) {
	$tags = trim(str_replace(array(", ",",,"),array(",",","),$tags));
	$tags = str_replace(" ","-",$tags);
	if(substr($tags,-1) == ",") $tags = substr($tags,0,strlen($tags)-1);
	return $tags;
}

function simple_encrypt($text) {
	$my_key = "12345678abcdefgh";
	return base64_encode(mcrypt_encrypt(MCRYPT_3DES, $my_key, $text, MCRYPT_MODE_CBC, $iv));
}

function simple_decrypt($text) {
	$my_key = "12345678abcdefgh";
	return (int)mcrypt_decrypt(MCRYPT_3DES, $my_key, base64_decode($text), MCRYPT_MODE_CBC, $iv);
}

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function getTagLink($userName, $tagName) {
	if($userName)
		return "<a href='".getURL($userName.'/'.urlencode($tagName))."'>".strtoupper($tagName)."</a>";
	else
		return strtoupper($tagName);
}
?>