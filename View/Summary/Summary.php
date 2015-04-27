<input type="hidden" id="tuid" value="<?php echo $args['userID']; ?>">

<div id="summary">
	<?php
	if(!$args['isSearch']) include_once('View/Summary/Tabs.php');
	include_once('View/Summary/Thoughts.php');
	if($args['page_name'] == 'Thought') echo getAdSenseView('thought',2);
	?>
</div>