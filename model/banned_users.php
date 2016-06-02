<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');

class BannedUsersTable {

	// Require:
	/* [
	 * 'id'=> STR
	 * 'admin_id' => INT
	 * ]
	 */
	static function insert($data) {
		if (is_null($data))
			return FALSE;
		if(!isset($data['id'])) return FALSE;
		if(!isset($data['admin_id'])) return FALSE;
		
		$conn = connect_db();
		$sql = "insert into " . BANNED_USERS_TABLE . " 
			(id, admin_id, banned_datetime) values
			(:id, :admin_id, CURRENT_TIMESTAMP)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $data['id']);
		oci_bind_by_name($stmt, ":admin_id", $data['admin_id']);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * 
				from " . BANNED_USERS_TABLE." b,".USERS_TABLE." u 
				where b.id = u.id";
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
		$sql = "select * 
				from " . BANNED_USERS_TABLE." b,".USERS_TABLE." u 
				where b.id = u.id and b.id = :id and u.id = :id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		return oci_fetch_array($stmt);
	}
	
	static function create() {
		$conn = connect_db();
		$sql = "create table " . BANNED_USERS_TABLE . " 
				(
				id INT PRIMARY KEY, 
				banned_datetime TIMESTAMP NOT NULL,
				admin_id INT NOT NULL,
				FOREIGN  KEY (id) REFERENCES ".USERS_TABLE."(id),
				FOREIGN  KEY (admin_id) REFERENCES ".ADMINS_TABLE."(id)
				)
				";
		
		$stmt = oci_parse($conn, $sql);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . BANNED_USERS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

}
?>