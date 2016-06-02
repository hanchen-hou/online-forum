<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');

class AdminsTable {

	// Require:
	/* [
	 * 'email'=> STR
	 * 'name'=> STR
	 * 'password'=> STR
	 * 'senior_id'=> INT //can be null
	 * ]
	 */
	static function insert($data) {
		if (is_null($data))
			return FALSE;
		if (!isset($data['email']))
			return FALSE;
		if (!isset($data['name']))
			return FALSE;
		if (!isset($data['password']))
			return FALSE;

		$conn = connect_db();

		//generate a unique random id
		do {
			$new_id = create_random_num(USER_ID_LENGTH);
		} while(UsersTable::select_by_id($new_id));

		$salt = create_random_string(SALT_LENGTH);
		$pw_md5 = md5($data['password'] . $salt);
		$statue = 0;

		$sql = "insert into " . USERS_TABLE . " 
			(id, email, name, pw_md5, salt, register_datetime, status) values
			(:id, :email, :name, :pw_md5, :salt, CURRENT_TIMESTAMP, :status)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $new_id);
		oci_bind_by_name($stmt, ":email", $data['email']);
		oci_bind_by_name($stmt, ":name", $data['name']);
		oci_bind_by_name($stmt, ":pw_md5", $pw_md5);
		oci_bind_by_name($stmt, ":salt", $salt);
		oci_bind_by_name($stmt, ":status", $statue); //0=good/1=ban
		$result = oci_execute($stmt);

		$sql = "insert into " . ADMINS_TABLE . " 
			(id, senior_id ) values
			(:id, :senior_id )";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $new_id);
		oci_bind_by_name($stmt, ":senior_id", $data['senior_id']);

		$result = oci_execute($stmt) && $result;
		oci_close($conn);

		return $result;
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * 
				from " . ADMINS_TABLE . "a " . USERS_TABLE . "u 
				where a.id = u.id";
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
				from " . ADMINS_TABLE . "a " . USERS_TABLE . "u 
				where a.id = u.id and u.id=:id and a.id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		return oci_fetch_array($stmt);
	}

	static function select_by_name($user_name) {
		if (is_null($user_name))
			return FALSE;

		$conn = connect_db();
		$sql = "select * 
				from " . ADMINS_TABLE . "a " . USERS_TABLE . "u 
				where a.id = u.id and u.name=:user_name";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_name", $user_name);
		oci_execute($stmt);

		return oci_fetch_array($stmt);
	}

	static function create() {
		$conn = connect_db();
		$sql = "create table " . ADMINS_TABLE . " 
			(
			id INT PRIMARY KEY, 
			senior_id INT,
			FOREIGN  KEY (id) REFERENCES " . USERS_TABLE . "(id),
			FOREIGN  KEY (senior_id ) REFERENCES " . ADMINS_TABLE . "(id)
			)
			";

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