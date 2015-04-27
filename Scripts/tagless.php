<?php
$query = "SELECT ID FROM Thought where (SELECT COUNT(ID) FROM ThoughtTag WHERE ThoughtTag.ThoughtID = Thought.ID) = 0";
$results = DB::Query($query);
foreach($results as $result) {
	echo $result['ID']."<br>";
}
?>