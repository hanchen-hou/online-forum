<?php
    
require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class UsersManageTable{
	
	// Require:
	/* [
	 * 'user_id'=> INT
	 * 'admin_id'=> INT
	 * 'operation_type'=> INT
	 * 'detail'=> STR
	 * ]
	 */ 
	static function insert($conn, $data){
		if(is_null($data)) return FALSE;
		if(!isset($data['user_id'])) return FALSE;
		if(!isset($data['admin_id'])) return FALSE;
		if(!isset($data['detail'])) return FALSE;
		
		$conn = connect_db();
		$sql = "insert into ".USERS_MANAGE_TABLE." (user_id, admin_id, datetime, operation_type, detail) values
			(:user_id, :admin_id, CURRENT_TIMESTAMP, :operation_type, :detail)";
		$stmt = oci_parse($conn, $sql);
		//oci_bind_by_name($stmt, ":id", $data['id']); // id number auto increase
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":admin_id", $data['admin_id']);
		//oci_bind_by_name($stmt, ":datetime", $data['datetime']); // use CURRENT_TIMESTAMP
		oci_bind_by_name($stmt, ":operation_type", $data['operation_type']); // 0=set_visible/1=set_invisible
		oci_bind_by_name($stmt, ":detail", $data['detail']); 
		
		$result = oci_execute($stmt);
		oci_close($conn);
		
		return $result;
	}
	
	static function select_by_id($id){
		if(is_null($id)) return FALSE;
		
		$conn = connect_db();
		$sql = "select * from ".USERS_MANAGE_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql_1 = "create table ".USERS_MANAGE_TABLE." 
			(
			id INT PRIMARY KEY,
			user_id INT NOT NULL,
			admin_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			operation_type INT NOT NULL,
			detail VARCHAR2(100),
			FOREIGN KEY (user_id) REFERENCES ".USERS_TABLE."(id),
			FOREIGN KEY (admin_id) REFERENCES ".ADMINS_TABLE."(id)
			)";
		$sql_2 = "CREATE SEQUENCE ".USERS_MANAGE_SEQ;
		$sql_3 = "CREATE OR REPLACE TRIGGER ".USERS_MANAGE_TRIGGER." 
			BEFORE INSERT ON ".USERS_MANAGE_TABLE." 
			FOR EACH ROW
			BEGIN
			  SELECT ".USERS_MANAGE_SEQ.".NEXTVAL
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
	}
	
	static function drop(){
		$conn = connect_db();
		$sql_1 = "DROP TRIGGER ".USERS_MANAGE_TRIGGER;
		$sql_2 = "DROP SEQUENCE ".USERS_MANAGE_SEQ;
		$sql_3 = "DROP TABLE ".USERS_MANAGE_TABLE;
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