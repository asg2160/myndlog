<?php

class DB {

private static $mysqli;

static function cxn() {
	if(!self::$mysqli) {
		if($GLOBALS['isDev']) 
			self::$mysqli = mysqli_connect("127.0.0.1", "root", "", 'ideas') or die_dump(mysqli_error());
		else 
			self::$mysqli =  mysqli_connect('boxofficemachinecom.ipagemysql.com', 'myndlog', 'home4page123_','myndlog') or die_dump(mysqli_error());
	}
	return self::$mysqli;
}

function query($query) {
	$result = mysqli_query(DB::cxn(),$query);
	$rows = array();
	if($result) {
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
			$rows[] = $row;
		}
	}
	return $rows;
}

function insert($tableName, $valueArray) {

	if(empty($valueArray) || !is_array($valueArray)) return;

	$valueString = arrayToValueString($valueArray);
	mysqli_query(DB::cxn(),'INSERT INTO '.$tableName.' VALUES '.$valueString); 

	return mysqli_insert_id(DB::cxn());
}

function updateByID($tableName, $data, $ID) {
	foreach($data as $name=>$value) {
		DB::query('UPDATE '.$tableName.' SET '.$name.' = '.mapToSQL($value).' WHERE ID = '.$ID);
	}
	return $ID;
}

function selectByID($tableName, $id, $fields) {

	if(!$id) return array();
	
	if(!$fields) 
		$fields = '*';
	else if(is_array($fields)) 
		$fields = implode(',', $fields);
	
	return DB::query('SELECT '.$fields.' FROM '.$tableName.' WHERE ID = '.$id); 
}

function selectAll($tableName, $fields) {

	if(!$fields) 
		$fields = '*';
	else if(is_array($fields)) 
		$fields = implode(',', $fields);
	
	return DB::query('SELECT '.$fields.' FROM '.$tableName); 
}

function selectAllByUserID($tableName, $userID, $fields) {

	if(!$fields) 
		$fields = '*';
	else if(is_array($fields)) 
		$fields = implode(',', $fields);
	
	return DB::query('SELECT '.$fields.' FROM '.$tableName." WHERE UserID = ".$userID); 
}

function delete($table, $id) {
	DB::query('DELETE FROM '.$table.' WHERE ID = '.$id);
}

}

?>