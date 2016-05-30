<?php
    
require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class MsgsTable{
	
	// Require:
	/* [
	 * 'id'=> INT
	 * 'user_id'=> INT
	 * 'content'=> STR
	 * 'visible'=> INT
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".MSGS_TABLE." (id, user_id, datetime, content, visible) values
			(:id, :user_id, CURRENT_TIMESTAMP, :content, :visible)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $data['id']); // id number get from MSGS_SEQ
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":content", $data['content']);
		oci_bind_by_name($stmt, ":visible", $data['visible']); //0=visible/1=invisible
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	static function select_by_id($id){
		if(is_null($id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".MSGS_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".MSGS_TABLE." 
			(
			id INT PRIMARY KEY,
			user_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			content VARCHAR2(512) NOT NULL,
			visible INT NOT NULL,
			FOREIGN KEY (user_id) REFERENCES ".USERS_TABLE."(id)
			)";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt_1);
		oci_close($conn);
	}
}

?>