<?php
$results = DB::query("SELECT ID FROM Tag;");
foreach($results as $result) {
	$tagID = $result['ID'];
	echo $tagID."<br>";
	DB::updateByID('Tag', array('Color'=>rand_color()), $tagID);
}
die();
?>