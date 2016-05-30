<?php
    
require_once (dirname(dirname(__FILE__)).'/lib/common.php');

class ProblemMsgsTable{
	
	// Require:
	/* [
	 * 'msg_id'=> INT
	 * 'user_id'=> INT
	 * 'reason'=> STR
	 * ]
	 */ 
	static function insert($data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "insert into ".USERS_MANAGE_TABLE." (msg_id, user_id, reason, report_datetime) values
			(:msg_id, :user_id, :reason, CURRENT_TIMESTAMP)";
		$stmt = oci_parse($conn, $sql);
		//oci_bind_by_name($stmt, ":id", $data['id']); // id number auto increase
		oci_bind_by_name($stmt, ":msg_id", $data['msg_id']);
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":reason", $data['reason']);
		//oci_bind_by_name($stmt, ":datetime", $data['datetime']); // use CURRENT_TIMESTAMP
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	static function update_by_id($id, $data){
		if(is_null($data)) return;
		
		$conn = connect_db();
		$sql = "update ".USERS_MANAGE_TABLE." set admin_id=:admin_id, 
		handle_datetime=CURRENT_TIMESTAMP, 
		result=:result 
		where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":admin_id", $data['admin_id']);
		oci_bind_by_name($stmt, ":result", $data['result']);
		oci_bind_by_name($stmt, ":id", $id);
		
		oci_execute($stmt);
		oci_close($conn);
	}
	
	static function select_by_id($id){
		if(is_null($id)) return;
		
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
			msg_id INT NOT NULL,
			reason VARCHAR2(500) NOT NULL,
			user_id INT NOT NULL,
			report_datetime TIMESTAMP NOT NULL,
			admin_id INT,
			handle_datetime TIMESTAMP,
			result VARCHAR2(100),
			FOREIGN KEY (msg_id) REFERENCES ".MSGS_TABLE."(id),
			FOREIGN KEY (user_id) REFERENCES ".USERS_TABLE."(id),
			FOREIGN KEY (admin_id) REFERENCES ".ADMINS_TABLE."(id)
			)";
		$sql_2 = "CREATE SEQUENCE problem_msgs_seq";
		$sql_3 = "CREATE OR REPLACE TRIGGER add_problem_msgs
			BEFORE INSERT ON ".USERS_MANAGE_TABLE." 
			FOR EACH ROW
			BEGIN
			  SELECT problem_msgs_seq.NEXTVAL
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