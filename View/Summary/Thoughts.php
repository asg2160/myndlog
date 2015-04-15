<?php
$view = '';

$isPublicPage = $isArticle || $isPage || $isSignIn || $isRegister || $isMashup || $args['isPublicPage'];

if($isSignIn) {
	include_once('View/SignIn/Box.php');
}

if($isRegister) {
	include_once('View/Register/Box.php');
}

for($i=0; $i<count($args['thoughtIDs']); $i++) {
	
	$thought = new Thought($args['thoughtIDs'][$i]['ID']);
	
	$tags = str_replace(" ","-",$thought->tags);		

	$append = $i ? "," : ""; 
	$allTags .= $append.$tags;
	
	$classTags = str_replace(","," ",$tags);
	$monthClass = strtolower(date("F",$thought->dateAdded));
	$isVisible = $thought->visible;	

	 if($isArticle) {
		$view = "<div id='thought_tags'>";
			if(!$isPublicPage) $view .= "<span>tags:</span>";
				$tags = explode(",",$thought->tags);
				$tagCount = 0;
				foreach($tags as $tag) {
					if($tag) $view .= getTagLink($args['thought_user_name'],$tag);
				}
		$view .= "</div>";
	}
		
	$view .= "<div tags='".$classTags."' month='".$monthClass."' class='article thought' id='thought_".$thought->ID."'>";
		if(!$isPublicPage) {
			$view .= "<p class='edit_thought data_action'>
				edit
			</p>";
		}
		
		if($isPage) {
			$view .= "<a href=".getURL('thought/'.$thought->ID)." class='open'>
				open
			</a>";
		}
		
		$view .= "<p class='article_date'>
			".date('F j, Y',$thought->dateAdded)."
		</p>
		
		<div class='text_container'>";
			if($thought->title) {
				$view .= "<p class='title'>".$thought->title."</p>";
			}
			$view .= "<p>".nl2br($thought->text)."</p>
		</div>";
		
		if(!$isArticle) {
			$view .= "<p class='para_tags'>";
				if(!$isPublicPage) {
					$view .= "<span>tags:</span>";
				}
				$view .= "<span class='list'>";
				$tags = explode(",",$thought->tags);
				$tagCount = 0;
				
				foreach($tags as $tag) {
					$delimeter = ($tagCount++) ? ", " : "";
					$view .= $delimeter.getTagLink($args['thought_user_name'],$tag);
				}	
			$view .= "</span>
			</p>";
		}

		if(!$isPublicPage) {
			$view .= "<p class='delete_thought data_action' tid='".$thought->ID."'>x</p>";
		}
		
		if(!$isPublicPage) {
			$view .= "<div class='bottom'>
				make public<input type='checkbox' ".($isVisible ? 'checked' : '')." class='visibility' disabled>
			</div>";
		}
	$view .= "</div>";
}

if(!$args['view_string']) echo $view;
?>