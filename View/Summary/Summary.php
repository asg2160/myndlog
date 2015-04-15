<input type="hidden" id="tuid" value="<?php echo $args['userID']; ?>">

<?php
if(!$args['isSearch']) include_once('View/Summary/Tabs.php');

echo "<div id=\"articles\">";
	if(count($args['thoughtIDs'])) { 
		include_once('View/Summary/Thoughts.php');
	} else { ?>
		<p class='message' style="color:#000;"><?php echo $args['message']; ?></p>
	<?php } 
echo "</div>";
?>