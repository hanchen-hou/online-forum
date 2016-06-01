<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');

class AdminsTable {

	// Require:
	/* [
	 * 'name'=> STR
	 * 'password'=> STR
	 * ]
	 */
	static function insert($data) {
		if (is_null($data))
			return FALSE;
		if(!isset($data['password'])) return FALSE;
		if(!isset($data['name'])) return FALSE;
		
		//generate a unique random id
		do {
			$new_id = create_random_num(USER_ID_LENGTH);
		} while(AdminsTable::select_by_id($new_id));

		$salt = create_random_string(SALT_LENGTH);
		$pw_md5 = md5($data['password'] . $salt);

		$conn = connect_db();
		$sql = "insert into " . ADMINS_TABLE . " 
			(id, name, pw_md5, salt, register_datetime) values
			(:id, :name, :pw_md5, :salt, CURRENT_TIMESTAMP)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $new_id);
		oci_bind_by_name($stmt, ":name", $data['name']);
		oci_bind_by_name($stmt, ":pw_md5", $pw_md5);
		oci_bind_by_name($stmt, ":salt", $salt);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * from " . ADMINS_TABLE;
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}

	static function select_by_id($id) {
		if (is_null($id))
			return;

		$conn = connect_db();
		$sql = "select * from " . ADMINS_TABLE . " where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_row($stmt);
		return $row;
	}

	static function create() {
		$conn = connect_db();
		$sql = "create table " . ADMINS_TABLE . " 
			(
			id INT PRIMARY KEY,
			name VARCHAR2(15) NOT NULL,
			pw_md5 CHAR(32) NOT NULL,
			salt CHAR(4) NOT NULL,
			register_datetime TIMESTAMP NOT NULL
			)";
		$stmt = oci_parse($conn, $sql);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . ADMINS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

}
?>