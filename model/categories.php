<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

define('CATEGORIES_TABLE', 'CS_CATEGORIES');

class CategoriesTable{
	
	// Require:
	/* [
	 * 'id'=> INT
	 * 'name'=> STR
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".CATEGORIES_TABLE." (id, name) values
			(:id, :name)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $data['id']);
		oci_bind_by_name($stmt, ":name", $data['name']);
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	// select by user_id
	// return false if the user_id does not exit.
	static function select_by_id($id){
		if(is_null($id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".CATEGORIES_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".CATEGORIES_TABLE." (id INT PRIMARY KEY,
			name VARCHAR2(20) NOT NULL";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}
}

?>