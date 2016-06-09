<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');
require_once (dirname(__FILE__) . '/admins.php');

class BannedUsersTable {

	static function ban_by_id($user_id, $admin_id) {
		if (!isset($user_id))
			return FALSE;
		if (!isset($admin_id))
			return FALSE;

		if (AdminsTable::select_by_id($user_id))
			return FALSE;
		if (!AdminsTable::select_by_id($admin_id))
			return FALSE;

		$conn = connect_db();
		$sql = "insert into " . BANNED_USERS_TABLE . " 
							(id, admin_id, banned_datetime) values
							(:user_id, :admin_id, CURRENT_TIMESTAMP)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $user_id);
		oci_bind_by_name($stmt, ":admin_id", $admin_id);
		$result = oci_execute($stmt);

		$sql = "update " . USERS_TABLE . " set STATUS=1 where id = :user_id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $user_id);

		$result = oci_execute($stmt) && $result;
		oci_close($conn);

		return $result;
	}

	static function unban_by_id($user_id, $admin_id) {
		if (!isset($user_id))
			return FALSE;
		if (!isset($admin_id))
			return FALSE;

		if (AdminsTable::select_by_id($user_id))
			return FALSE;
		if (!AdminsTable::select_by_id($admin_id))
			return FALSE;

		$conn = connect_db();
		$sql = "Delete from " . BANNED_USERS_TABLE . " 
				Where id=:user_id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $user_id);
		$result = oci_execute($stmt);

		$sql = "update " . USERS_TABLE . " set STATUS=0 where id = :user_id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $user_id);

		$result = oci_execute($stmt) && $result;
		oci_close($conn);

		return $result;
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * 
				from " . BANNED_USERS_TABLE . " b," . USERS_TABLE . " u 
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
				from " . BANNED_USERS_TABLE . " b," . USERS_TABLE . " u 
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
				FOREIGN  KEY (id) REFERENCES " . USERS_TABLE . "(id),
				FOREIGN  KEY (admin_id) REFERENCES " . ADMINS_TABLE . "(id)
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