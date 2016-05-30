<?php
    
require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class MsgsManageTable{
	
	// Require:
	/* [
	 * 'msg_id'=> INT
	 * 'admin_id'=> INT
	 * 'operation_type'=> INT
	 * 'detail'=> STR
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".MSGS_MANAGE_TABLE." (msg_id, admin_id, datetime, operation_type, detail) values
			(:msg_id, :admin_id, CURRENT_TIMESTAMP, :operation_type, :detail)";
		$stmt = oci_parse($conn, $sql);
		//oci_bind_by_name($stmt, ":id", $data['id']); // id number auto increase
		oci_bind_by_name($stmt, ":msg_id", $data['msg_id']);
		oci_bind_by_name($stmt, ":admin_id", $data['admin_id']);
		//oci_bind_by_name($stmt, ":datetime", $data['datetime']); // use CURRENT_TIMESTAMP
		oci_bind_by_name($stmt, ":operation_type", $data['operation_type']); // 0=set_visible/1=set_invisible
		oci_bind_by_name($stmt, ":detail", $data['detail']); 
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	static function select_by_id($id){
		if(is_null($id)) return;
		
		$conn = connect_db();
		$sql = "select * from ".PROBLEM_MSGS_TABLE." where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);
		
		$row = oci_fetch_row($stmt);
		return $row;
	}
	
	static function create(){
		$conn = connect_db();
		$sql_1 = "create table ".PROBLEM_MSGS_TABLE." 
			(
			id INT PRIMARY KEY,
			msg_id INT NOT NULL,
			admin_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			operation_type INT NOT NULL,
			detail VARCHAR2(100),
			FOREIGN KEY (msg_id) REFERENCES ".MSGS_TABLE."(id),
			FOREIGN KEY (admin_id) REFERENCES ".ADMINS_TABLE."(id)
			)";
		$sql_2 = "CREATE SEQUENCE msgs_manage_seq";
		$sql_3 = "CREATE OR REPLACE TRIGGER add_msgs_manage
			BEFORE INSERT ON ".PROBLEM_MSGS_TABLE." 
			FOR EACH ROW
			BEGIN
			  SELECT msgs_manage_seq.NEXTVAL
			  INTO   :new.id
			  FROM   dual;
			END;";
		$stmt_1 = oci_parse($conn, $sql_1);
		oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		oci_execute($stmt_2);
		$stmt_3 = oci_parse($conn, $sql_3);
		oci_execute($stmt_3);
		oci_close($conn);
	}
}

?>