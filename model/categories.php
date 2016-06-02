<?php

require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class CategoriesTable{
	
	// Require:
	/* [
	 * 'name'=> STR
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return FALSE;
		if(!isset($data['name'])) return FALSE;
		
		$conn = connect_db();
		$sql = "insert into ".CATEGORIES_TABLE." 
				(name, datetime, admin_id) values
				(:name, CURRENT_TIMESTAMP, :admin_id)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":name", $data['name']);
		oci_bind_by_name($stmt, ":admin_id", $data['admin_id']);
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
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
	
	static function select_all(){
		$conn = connect_db();
		$sql = "select * from ".CATEGORIES_TABLE;
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_all($stmt, $res);
		return $res;
	}
	
	static function create(){
		$conn = connect_db();
		$sql_1 = "create table ".CATEGORIES_TABLE." 
			(
			id INT PRIMARY KEY, 
			name VARCHAR2(20) NOT NULL,
			datetime TIMESTAMP NOT NULL,
			admin_id INT,
			FOREIGN KEY (admin_id) REFERENCES ".ADMINS_TABLE."(id)
			)";
		$sql_2 = "CREATE SEQUENCE ".CATEGORIES_SEQ;
		$sql_3 = "CREATE OR REPLACE TRIGGER ".CATEGORIES_TRIGGER." 
			BEFORE INSERT ON ".CATEGORIES_TABLE." 
			FOR EACH ROW
			BEGIN
			  SELECT ".CATEGORIES_SEQ.".NEXTVAL
			  INTO   :new.id
			  FROM   dual;
			END;";
		$stmt_1 = oci_parse($conn, $sql_1);
		$result = oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		$result = oci_execute($stmt_2) && $result;
		$stmt_3 = oci_parse($conn, $sql_3);
		$result = oci_execute($stmt_3) && $result;
		oci_close($conn);
		
		return $result;
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function drop(){
		$conn = connect_db();
		$sql_1 = "DROP TRIGGER ".CATEGORIES_TRIGGER;
		$sql_2 = "DROP SEQUENCE ".CATEGORIES_SEQ;
		$sql_3 = "DROP TABLE ".CATEGORIES_TABLE;
		$stmt_1 = oci_parse($conn, $sql_1);
		$result = oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		$result = oci_execute($stmt_2) && $result;
		$stmt_3 = oci_parse($conn, $sql_3);
		$result = oci_execute($stmt_3) && $result;
		
		oci_close($conn);
		return $result;
	}
}

?>