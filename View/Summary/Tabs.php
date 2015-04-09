<div id="tabs">
	<?php if(!$isArticle) { ?>
		<ul id="menu_tags">
		<?php
			foreach($args['tagNames'] as $tagName) {
				$selectedClass = ($args['selectedTagName'] == $tagName['Name']) ? 'selected' : '';
		?>
				<li class="tab menu_tag <?php echo $selectedClass; ?>">
					<a href="<?php echo ($GLOBALS['Page'] == 'Page') ? getURL($args['public_user_name'].'/'.urlencode($tagName['Name'])) : replaceParamInURL('tag', urlencode($tagName['Name'])); ?>">
						<?php echo $tagName['Name']; ?>
					</a>
				</li>
		<?php
			}
		?>
				<li class="tab menu_tag show_all_tags <?php echo ($args['selectedTagName'] == 'Show-All') ? 'selected' : '' ?>">
					<a href="<?php echo ($GLOBALS['Page'] == 'Page') ? getURL($args['public_user_name'].'/Show-All') : replaceParamInURL('tag', urlencode('Show-All')); ?>">
						Show All
					</a>
				</li>
		</ul>
	<?php } ?>
</div>
<input id="selected_tag" type="hidden" value="<?php echo $args['selectedTagName']; ?>">