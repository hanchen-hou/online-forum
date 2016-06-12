<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');

class MsgsTable {

	// Require:
	/* [
	 * 'user_id'=> INT
	 * 'content'=> STR
	 * ]
	 */
	// NOTE: This function can only be invoked by PostsTable or CommentsTable
	static function insert($id, $data) {
		if (is_null($data))
			return;
		if (!isset($data['user_id']))
			return FALSE;
		if (!isset($data['content']))
			return FALSE;
		if (strlen($data['content']) > CONTENT_LENGTH)
			return FALSE;

		$visible = 0;

		$conn = connect_db();
		$sql = "insert into " . MSGS_TABLE . " (id, user_id, datetime, content, visible) values
			(:id, :user_id, CURRENT_TIMESTAMP, :content, :visible)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		// id number get from MSGS_SEQ
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":content", $data['content']);
		oci_bind_by_name($stmt, ":visible", $visible);
		//0=visible/1=invisible

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * from " . MSGS_TABLE;
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
		$sql = "select * from " . MSGS_TABLE . " where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_row($stmt);
		return $row;
	}

	// if the id is comment, it will delete the comment
	// if the id is post, it will delete the post and comments
	// we only need to operate the CS_MSGS table since 
	// the CS_POSTS andf CS_COMMENTS have FK to CS_MSGS
	static function delete_by_id($id) {
		if (is_null($id))
			return FALSE;

		$conn = connect_db();
		$sql = "delete from ".MSGS_TABLE." where id in (select id from ".COMMENTS_TABLE." where post_id = :id)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		$result = oci_execute($stmt);
		
		$sql = "delete from ".MSGS_TABLE." where id = :id;";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		$result = oci_execute($stmt) && $result;
		
		oci_close($conn);
		return $result;
	}

	static function create() {
		//TODO: Add Constrain to check if the user is banned

		$conn = connect_db();
		$sql = "create table " . MSGS_TABLE . " 
			(
			id INT PRIMARY KEY,
			user_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			content VARCHAR2(" . CONTENT_LENGTH . ") NOT NULL,
			visible INT NOT NULL,
			FOREIGN KEY (user_id) REFERENCES " . USERS_TABLE . "(id)
			)";
		$stmt = oci_parse($conn, $sql);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . MSGS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

}
?>