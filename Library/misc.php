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

function arrayToColumnString($columnArray) {
	$columnString = '('.implode(',', $columnArray).')';
	
	return $columnString;
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

function getImageURL($images) {
	return "";
}

function loadURL($url) {
	echo "<script>";
	echo "window.location='".getURL($url)."';";
	echo "</script>";
	die();
	/*error_reporting(E_ALL); ini_set('display_errors', 1);
	header("Location: ".getURL($url));
	die();*/
}

function auth($userID) {
	setcookie('LoggedInUserID', $userID, time() + (86400 * 30 * 365), "/");
}

function isAuth() {
	if(!$_SESSION['UserID'] && $_COOKIE['LoggedInUserID'] && $_COOKIE['LoggedInUserID'] != "null") $_SESSION['UserID'] = $_COOKIE['LoggedInUserID'];
	return $_SESSION['UserID'];
}

function unAuth() {
	unset($_SESSION['UserID']);
	unset($_SESSION['Theme']);
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

function tweetButton($url, $text) {
	return "<a href='https://twitter.com/share' class='twitter-share-button' data-url='".$url."' data-text='".$text."'>Tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
}

function getAdSenseCode($type) {
	switch("myndlog_".$type) {
		case 'myndlog_thought':
		default:
		$code = "<script async src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
	<!-- myndlog_thought -->
	<ins class='adsbygoogle'
	     style='display:inline-block;width:336px;height:280px'
	     data-ad-client='ca-pub-1294683367767480'
	     data-ad-slot='9786246346'></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>";
	}
	
	return $code;
}

function getAdSenseView($type, $num) {
	echo "<div class='ad_sense'>";
	for($i=0; $i < $num; $i++) {
		echo "<div>";
		echo getAdSenseCode($type);
		echo "</div>";
	}
	echo "</div>";
}

function splitStringBySpace($haystack,$n) {
	$needle = ' ';
	return splitString($haystack,$needle,$n);
}

function splitString($haystack,$needle,$n) {
	$pos = strposn($haystack,$needle,$n);
	$strings = array();
	$strings[0] = $pos ? substr($haystack,0,$pos) : '';
	$strings[1] = $pos ? substr($haystack,$pos + strlen($needle)) : $haystack;
	return $strings;
}

function strposn($haystack,$needle,$n) {
	if(!$n) return 0;
	$pos1 = strpos($haystack, $needle);
	for($i = 1; $i < $n; $i++) {
		$posn = strpos($haystack, $needle, $pos1+1);
		$pos1 = $posn;
	}
	return $pos1;
}

function countWords($string) {
	$words = explode(" ",$string);
	return count($words);
}

function getTagLink($userName, $tagName, $pageName) {
	
	if($pageName) {
		$urlPageName = ($pageName == 'Page') ? $userName : $pageName;
		$href = "inline-href='".getURL($urlPageName.'/'.urlencode($tagName))."'";
	} else {
		$href = "href='".getURL($userName.'/'.urlencode($tagName))."'";
	}
	
	if($userName)
		return "<a class='fetch_summary' ".$href.">".$tagName."</a>";
	else
		return $tagName;
}

function getThoughtView($thoughtID, $tagID, $args) {
	$isPublicPage = ($args['page_name'] == 'Thought') || ($args['page_name'] == 'Page') || $args['isPublicPage'];
	$isThoughtPage = ($args['page_name'] == 'Thought');
	$tabLinkPageName = $isThoughtPage ? '' : $args['page_name'];
	$thought = new Thought($thoughtID);
	$tagObj = new Tag($tagID);
	
	$tags = str_replace(" ","-",$thought->tags);

	$append = $i ? "," : ""; 
	$allTags .= $append.$tags;
	
	$isVisible = $thought->visible;	
	
	$view .= "<div class='article thought' id='thought_".$thought->ID."'>";
		
		$view .= "<p class='article_date'>
			".date('F j, Y',$thought->dateAdded)."
		</p>";
		
		$makeTextClickable = !$thought->title;
		$view .= "<div class='text_container".($makeTextClickable ? ' open_article' : '')."'>";
			$title_url = ($isPublicPage ? ($isThoughtPage ? '' : getURL('thought/'.$thought->ID)) : 'home');
			if(!$makeTextClickable) {
				$view .= "<p class='title' href='".$title_url."'>".$thought->title."</p>";
			}
			if(!$args['hide_text']) {
				$view .= "<p>".nl2br($thought->text)."</p>";
			}
		$view .=  "</div>";
		
		$view .= "<p class='para_tags'>";
			$view .= "<span class='list'>";
			$tags = explode(",",$thought->tags);
			$tagCount = 0;
			
			foreach($tags as $tag) {
				$delimeter = ($tagCount++) ? ", " : "";
				$view .= $delimeter.getTagLink($args['thought_user_name'],$tag,$tabLinkPageName);
			}	
		$view .= "</span>
		</p>";

		if(!$isPublicPage) {
			$view .= "<p class='delete_thought data_action' tid='".$thought->ID."'>x</p>";
		}
		
		if(!$isPublicPage) {
			$view .= "<div class='bottom'>
				make public<input type='checkbox' ".($isVisible ? 'checked' : '')." class='visibility' disabled>
			</div>";
		}
	$view .= "</div>";
	return $view;
}

function array_flatten($array) { 
	$return = array();
	array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
	return $return;
}

function loadThemeInSession($themeID) {
	if(!$themeID) return false;
	
	$colorPairs = Theme::getColorPairs($themeID);
	if(!$colorPairs || empty($colorPairs) || !is_array($colorPairs)) return false;
	
	$theme = "	#header .contents {
					background: [header_background];
				}
				
				#header .nds {
					color: [username_color];
				}
				
				#articles .para_tags {
					background: [selected_tab_background];
				}
				
				#thought_tags a:hover,
				#tabs li.selected:hover,
				#tabs li.selected {
				    background: [article_tab];
				}
				
				#page_page .articles_desc span {
					background: [articles_desc];
					color: [articles_desc_color];
				}";
	
	foreach($colorPairs as $name=>$color) {
		$theme = str_replace("[".$name."]",$color,$theme);
	}
	
	unset($_SESSION['Theme']);
	$_SESSION['Theme'] = "<style>".$theme."</style>";
}
?>