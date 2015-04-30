<?php
die();

// create tag if it does not already exist
$tagObj = new Tag();
$tagID = $tagObj->add('tagless');
	
$query = "SELECT ID FROM Thought where (SELECT COUNT(ID) FROM ThoughtTag WHERE ThoughtTag.ThoughtID = Thought.ID) = 0";
$results = DB::Query($query);
	
foreach($results as $result) {
	
	if(!$tagID) continue;
	
	// create thought tag link if it does not already exist
	$thoughtTagObj = new ThoughtTag();
	$thoughtTagObj->add($result['ID'],$tagID);

	echo "<br> ThoughtID : ".$result['ID']." --- TagID : ".$tagID." --- ThoughtTagID : ".$thoughtTagObj->ID;
}

die();
?>