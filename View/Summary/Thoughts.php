<?php
$thoughtCount = count($args['thoughtIDs']);
$newsLayout = ($args['page_name'] == 'Page' && !$args['selectedTagName']);
$encompassInArticlesDiv = (!$args['view_action'] || $args['view_action'] == 'replace');

if($encompassInArticlesDiv) {
	$view .= "<div id='articles'>";
}

if($newsLayout) {
	$view .= "<div class='article_row'>";
		$view .= "<p class='articles_desc'><span>Newest articles</span></p>";
		$view .= "<div class='article_main'>";
			$view .= getThoughtView($args['thoughtIDs'][0], null, $args);
		$view .="</div>";
		
		if($thoughtCount > 2) {
			$view .= "<div class='article_main_2'>";
				$view .= getThoughtView($args['thoughtIDs'][1], null, $args);
				$view .= getThoughtView($args['thoughtIDs'][2], null, $args);
			$view .= '</div>';
		}
		
		if($thoughtCount > 3) {
			$newArgs = array_merge($args,array('hide_text'=>true));
			$view .= "<div class='article_main_3'>";
				$view .= getThoughtView($args['thoughtIDs'][3], null, $newArgs);
				$view .= getThoughtView($args['thoughtIDs'][4], null, $newArgs);
				$view .= getThoughtView($args['thoughtIDs'][5], null, $newArgs);
			$view .= '</div>';
		}
	$view .= "</div>";
	
	/*
	foreach($args['thoughtIDsByTag'] as $tag=>$thoughtIDs) {
		$thoughtCount = count($thoughtIDs);
		
		$view .= "<div class='article_row'>";
			$view .= "<span>".$tag."</span><br>";
			
			$view .= "<div class='article_main'>";
				$view .= getThoughtView($thoughtIDs[0], null, $args);
			$view .="</div>";
			
			if($thoughtCount > 2) {
				$view .= "<div class='article_main_2'>";
					$view .= getThoughtView($thoughtIDs[1], null, $args);
					$view .= getThoughtView($thoughtIDs[2], null, $args);
				$view .= '</div>';
			}
			
			if($thoughtCount > 3) {
				$view .= "<div class='article_main_3'>";
					$view .= getThoughtView3($thoughtIDs[3], null, $args);
					$view .= getThoughtView3($thoughtIDs[4], null, $args);
					$view .= getThoughtView3($thoughtIDs[5], null, $args);
				$view .= '</div>';
			}		
		$view .= "</div>";
	}
	*/
}

if($thoughtCount) {	
	if($newsLayout) $view .= "<div class='other_rows'>";
		
		$offset = $newsLayout ? count($args['thoughtIDs']) : 0;
		$thoughtIDs = array_slice($args['thoughtIDs'],$offset);
		foreach($thoughtIDs as $thoughtID) {
			$view .= getThoughtView($thoughtID, null, $args);
		}
		
		if($newsLayout) {
			$view .= "<p class='articles_desc'><span>Newest articles by category</span></p>";
			$thoughtAndTagIDs = $args['thoughtIDsGroupedByTag'];
			foreach($thoughtAndTagIDs as $thoughtAndTagID) {
				$view .= getThoughtView($thoughtAndTagID['ThoughtID'], $thoughtAndTagID['TagID'], $args);
			}
		}
		
	if($newsLayout) $view .= "</div>"; 
} else {
	$view .= "<p class='message' style='color:#000;'>".$args['message']."</p>";
}

if($encompassInArticlesDiv) {
	$view .= "</div>";
}

if(!$args['view_string']) { 
	echo $view;
	$view = '';
}
?>