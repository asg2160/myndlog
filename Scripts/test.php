<?php
/**
 * This code will benchmark your server to determine how high of a cost you can
 * afford. You want to set the highest cost that you can without slowing down
 * you server too much. 8-10 is a good baseline, and more is good if your servers
 * are fast enough. The code below aims for ² 50 milliseconds stretching time,
 * which is a good baseline for systems handling interactive logins.
 */

$user_input = "abc123";
$hashed_password = crypt($user_input); 
echo $hashed_password."<Br>";
if ($hashed_password == crypt($user_input, $hashed_password)) {
   echo "Password verified!";
}

die();
$t = new Thought(138);
echo $t->tags;
die();

$results = DB::query("SELECT ID, Tags from Thought WHERE Tags != ''");
foreach($results as $result) {
	echo "\n-- ".$result['Tags']." --\n";
	$tagNames = explode(",",$result['Tags']);
	foreach($tagNames as $tagName) {
		$tagName = str_replace(" ","-",$tagName);
		$tag = new Tag();
		$tagID = $tag->add(array('name'=>$tagName));
		$thoughtTag = new ThoughtTag();
		$thoughtTag->add(array('thoughtID'=>$result['ID'], 'tagID'=>$tagID));
		echo "\n $thoughtID $tagID ADDED";
	}
}

die();

echo "DONE";
$args = array('text'=>"fucking hell",'tags'=>'an,dsf,dd','tid'=>1);


$thoughtData = array();
if(!is_null($args['text'])) $thoughtData["Text"] = $args['text'];
if(!is_null($args['visible'])) $thoughtData["Visible"] = $args['visible'];
$thoughtID = $args['tid'];

if($thoughtID) {							
		
	if(!is_null($args['tags'])) $tagNames = explode(",",$args['tags']);
		foreach($tagNames as $tagName) {
			$tag = new Tag();
			$tagID = $tag->add(array('name'=>$tagName));
			if(!$tagID) continue;
			Thought::updateByID($thoughtData,$thoughtID);
			$thoughtTag = new ThoughtTag();
			$thoughtTag->add(array('thoughtID'=>$thoughtID, 'tagID'=>$tagID));
	}
}

echo "DONE";
?>