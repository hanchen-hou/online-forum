<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class UsersTable{
	
	// Require:
	/* [
	 * 'user_id'=> INT
	 * 'email'=> STR
	 * 'name'=> STR
	 * 'password'=> STR
	 * 'salt'=> STR
	 * 'status'=> INT
	 * ]
	 */ 		
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".USERS_TABLE." (id, email, name, password, salt, register_datetime, status) values
			(:user_id, :email, :name, :password, :salt, CURRENT_TIMESTAMP, :status)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $data['id']);
		oci_bind_by_name($stmt, ":email", $data['email']);
		oci_bind_by_name($stmt, ":name", $data['name']);
		oci_bind_by_name($stmt, ":password", $data['password']);
		oci_bind_by_name($stmt, ":salt", $data['salt']);
		oci_bind_by_name($stmt, ":status", $data['status']); //0=good/1=ban
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	// select by user_id
	// return false if the user_id does not exit.
	static function select_by_id($user_id){
		if(is_null($user_id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".USERS_TABLE." where id=:user_id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $user_id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".USERS_TABLE." (id INT PRIMARY KEY,
			email VARCHAR2(25) NOT NULL,
			name VARCHAR2(15) NOT NULL,
			password CHAR(32) NOT NULL,
			salt CHAR(4) NOT NULL,
			register_datetime TIMESTAMP NOT NULL,
			status INT NOT NULL)";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}
}

?>