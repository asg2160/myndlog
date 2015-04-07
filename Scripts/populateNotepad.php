<?php
$results = DB::Query("SELECT ID FROM User");
foreach($results as $result) {
	try {
		$userID = $result['ID'];
		$notepad = new Notepad();
		$notepadID = $notepad->add(array('userID'=>$userID));
		echo "notepad $notepadID for user# $userID created <br>";
	} catch(Exception $e) {
		continue;
	}
}
?>