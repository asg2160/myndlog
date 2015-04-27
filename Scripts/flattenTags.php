<?php
die();

$query = "SELECT GROUP_CONCAT(ID) AS ThoughtTagIDs FROM ThoughtTag GROUP BY ThoughtID";
$result = DB::Query($query,true);

foreach($result as $ttStr) {
	
	$tt = explode(",",$ttStr);
	
	if(count($tt) > 1) {
		
		echo $ttStr."<br>";
		
		$tt = array_splice($tt,1);
			
		$query = "DELETE FROM ThoughtTag WHERE ID IN (".implode(",",$tt).")";
		echo $query;
		$result2 = DB::Query($query, true);
		
		echo "<br><br>";
	}
}

die();
?>