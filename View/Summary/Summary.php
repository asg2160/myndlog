<input type="hidden" id="tuid" value="<?php echo $args['userID']; ?>">
<?php
if(count($args['thoughtIDs'])) { 
	include_once('View/Summary/Tabs.php');
	echo "<div id=\"articles\">";
	include_once('View/Summary/Thoughts.php');
	echo "</div>";
} else { ?>
	<p style="color:#000;"><?php echo $args['message']; ?></p>
<?php } ?>