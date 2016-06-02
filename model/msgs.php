<?php
    
require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class MsgsTable{
	
	// Require:
	/* [
	 * 'user_id'=> INT
	 * 'content'=> STR
	 * ]
	 */ 
	// NOTE: This function can only be invoked by PostsTable or CommentsTable
	static function insert($id, $data){
		if(is_null($data)) return;
		if(!isset($data['user_id'])) return FALSE;
		if(!isset($data['content'])) return FALSE;
		if(strlen($data['content']) > CONTENT_LENGTH) return FALSE;
		
		$visible = 0;
		
		$conn = connect_db();
		$sql = "insert into ".MSGS_TABLE." (id, user_id, datetime, content, visible) values
			(:id, :user_id, CURRENT_TIMESTAMP, :content, :visible)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id); // id number get from MSGS_SEQ
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":content", $data['content']);
		oci_bind_by_name($stmt, ":visible", $visible); //0=visible/1=invisible
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function select_all(){
		$conn = connect_db();
		$sql = "select * from ".MSGS_TABLE;
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_all($stmt, $res);
		return $res;
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
	
	static function hide_by_id($msg_id){
		if(is_null($conn)) return FALSE;
		if(is_null($user_id)) return FALSE;
		
		$status = 1;
		
		$conn = connect_db();
		$sql = "update ".MSGS_TABLE." set status=:status";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":status", $status);
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function restore_by_id($msg_id){
		if(is_null($user_id)) return FALSE;
		
		$status = 0;
		
		$conn = connect_db();
		$sql = "update ".MSGS_TABLE." set status=:status";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":status", $status);
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".MSGS_TABLE." 
			(
			id INT PRIMARY KEY,
			user_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			content VARCHAR2(".CONTENT_LENGTH.") NOT NULL,
			visible INT NOT NULL,
			FOREIGN KEY (user_id) REFERENCES ".USERS_TABLE."(id)
			)";
		$stmt = oci_parse($conn, $sql);
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function drop(){
		$conn = connect_db();
		$sql = "drop table ".MSGS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}
}

?>