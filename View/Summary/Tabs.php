<?php
$user = new User($args['thought_user_id']);
$view .= "<div id='tabs'>";
$tabLinkPageName = ($args['page_name'] == 'Thought') ? '' : $args['page_name'];

if(count($args['tagNames'])) {
	$view .= "
		<div>
			<p class='left_scroller scroller'></p>
		</div>
		<ul id='menu_tags'>";
			if($args['page_name'] != 'Home') {
				$selectedClass = ($args['page_name'] == 'Page' && !$args['selectedTagName']) ? 'selected' : '';
				$view .= "<li class='".$selectedClass."'><a href='".$user->getPublicPageURL()."'>ML</a></li>";
			}
			
			foreach($args['tagNames'] as $tagName) {
				$selectedClass = ($args['selectedTagName'] == $tagName['Name']) ? 'selected' : '';			
				$view .= "<li class='tab menu_tag ".$selectedClass."'>
					".getTagLink($args['thought_user_name'],$tagName['Name'],$tabLinkPageName)."
				</li>";
			}
			
			$selectedClass = ($args['selectedTagName'] == 'Show-All') ? 'selected' : '';
			$view .= "<li class='tab menu_tag show_all_tags ".$selectedClass."'>
				".getTagLink($args['thought_user_name'],"Show-All",$tabLinkPageName)."
			</li>
		</ul>
		<div>
			<p class='right_scroller scroller'></p>
		</div>";
}
$view .= "<input id='selected_tag' type='hidden' value='".$args['selectedTagName']."'>";
$view .= "</div>";

if(!$args['view_string']) { 
	echo $view;
	$view = '';
}
?>