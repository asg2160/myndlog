<?php
$view = "<div id='tabs'>";
if(!$isArticle && count($args['tagNames'])) {
	$view .= "
		<div>
			<p class='left_scroller scroller'></p>
		</div>
		<ul id='menu_tags'>";
		
		foreach($args['tagNames'] as $tagName) {
			$selectedClass = ($args['selectedTagName'] == $tagName['Name']) ? 'selected' : '';
			$url = ($GLOBALS['Page'] == 'Page') ? getURL($args['thought_user_name'].'/'.urlencode($tagName['Name'])) : replaceParamInURL('tag', urlencode($tagName['Name']));
			
			$view .= "<li class='tab menu_tag ".$selectedClass."'>
				<a href='".$url."'>
					".$tagName['Name']."
				</a>
			</li>'";
			}
			
			$selectedClass = ($args['selectedTagName'] == 'Show-All') ? 'selected' : '';
			$url = ($GLOBALS['Page'] == 'Page') ? getURL($args['thought_user_name'].'/Show-All') : replaceParamInURL('tag', urlencode('Show-All'));
			$view .= "<li class='tab menu_tag show_all_tags ".$selectedClass."'>
				<a href='".$url."'>
					Show-All
				</a>
			</li>
		</ul>
		<div>
			<p class='right_scroller scroller'></p>
		</div>
	</div>";
}
$view .= "</div>";
$view .= "<input id='selected_tag' type='hidden' value='".$args['selectedTagName']."'>";

if(!$args['view_string']) echo $view;
?>