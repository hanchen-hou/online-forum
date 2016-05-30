<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class AdminsTable{
	
	// Require:
	/* [
	 * 'id'=> INT
	 * 'name'=> STR
	 * 'password'=> STR
	 * 'salt'=> STR
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".ADMINS_TABLE." (id, name, password, salt) values
			(:id, :name, :password, :salt)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $data['id']);
		oci_bind_by_name($stmt, ":name", $data['name']);
		oci_bind_by_name($stmt, ":password", $data['password']);
		oci_bind_by_name($stmt, ":salt", $data['salt']);
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	// select by admin_id
	// return false if the admin_id does not exit.
	static function select_by_id($id){
		if(is_null($id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".ADMINS_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".ADMINS_TABLE." (id INT PRIMARY KEY,
			name VARCHAR2(15) NOT NULL,
			password CHAR(32) NOT NULL,
			salt CHAR(4) NOT NULL)";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}
}

?>