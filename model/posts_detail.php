<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');

class AdminsTable {

	// Require:
	/* [
	 * ]
	 */
	static function insert($data) {
		//view does not need insertion
	}

	static function select_all() {
	}

	static function select_by_id($id) {
	}

	static function select_by_name($user_name) {
	}

	static function create() {
		$conn = connect_db();
		$sql = "create view Posts_Detail(ID, USER_ID, USER_NAME, TITLE, CONTENT, DATETIME) As
				  select m.id, m.user_id, u.name, p.title, m.content, m.datetime
				  from ".MSGS_TABLE." m, ".POSTS_TABLE." p, ".USERS_TABLE." u
				  where m.id = p.id and u.id = m.user_id";

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