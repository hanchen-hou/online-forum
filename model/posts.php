<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class PostsTable{
	
	// Require:
	/* [
	 * 'id'=> INT
	 * 'category_id'=> INT
	 * 'title'=> STR
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".POSTS_TABLE." (id, category_id, title) values
			(:id, :category_id, :title)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $data['id']);
		oci_bind_by_name($stmt, ":category_id", $data['category_id']);
		oci_bind_by_name($stmt, ":title", $data['title']);
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	static function select_by_id($id){
		if(is_null($id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".POSTS_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql = "create table ".POSTS_TABLE." (id INT PRIMARY KEY,
			category_id INT NOT NULL,
			title VARCHAR2(50) NOT NULL,
			FOREIGN KEY (id) REFERENCES ".MSGS_TABLE."(id),
			FOREIGN KEY (category_id) REFERENCES ".CATEGORIES_TABLE."(id),";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}
}

?>