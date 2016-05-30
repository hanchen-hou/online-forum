<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class CommentsTable{
	
	// Require:
	/* [
	 * 'id'=> INT
	 * 'post_id'=> INT
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".COMMENTS_TABLE." (id, post_id) values
			(:id, :post_id)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $data['id']);
		oci_bind_by_name($stmt, ":post_id", $data['post_id']);
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	static function select_by_id($id){
		if(is_null($id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".COMMENTS_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".COMMENTS_TABLE." (id INT PRIMARY KEY,
			post_id INT NOT NULL,
			title VARCHAR2(50) NOT NULL,
			FOREIGN KEY (id) REFERENCES ".MSGS_TABLE."(id),
			FOREIGN KEY (post_id) REFERENCES ".POSTS_TABLE."(id)";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}
}

?>