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
	/*static function insert($id, $data) {
		if (is_null($data))
			return;
		if (!isset($data['user_id']))
			return FALSE;
		if (!isset($data['content']))
			return FALSE;
		if (strlen($data['content']) > CONTENT_LENGTH)
			return FALSE;

		$conn = connect_db();
		$sql = "insert into " . MSGS_TABLE . " (id, user_id, datetime, content) values
			(:id, :user_id, CURRENT_TIMESTAMP, :content)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		// id number get from MSGS_SEQ
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":content", $data['content']);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}*/

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
		$sql = "delete from " . MSGS_TABLE . " where id in (select id from " . COMMENTS_TABLE . " where post_id = :id)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		$result = oci_execute($stmt);

		$sql = "delete from " . MSGS_TABLE . " where id = :id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		$result = oci_execute($stmt) && $result;

		oci_close($conn);
		return $result;
	}

	static function create() {
		$conn = connect_db();
		$sql_1 = "create table " . MSGS_TABLE . " 
			(
			id INT PRIMARY KEY,
			user_id INT NOT NULL,
			datetime TIMESTAMP NOT NULL,
			content VARCHAR2(" . CONTENT_LENGTH . ") NOT NULL,
			FOREIGN KEY (user_id) REFERENCES " . USERS_TABLE . "(id)
			)";
		$sql_2 = "CREATE OR REPLACE TRIGGER " . MSGS_TRIGGER . " 
			BEFORE INSERT ON " . MSGS_TABLE . " 
			FOR EACH ROW
			BEGIN
			  SELECT " . MSGS_SEQ . ".NEXTVAL
			  INTO   :new.id
			  FROM   dual;
			END;";
		$stmt_1 = oci_parse($conn, $sql_1);
		$result = oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		$result = oci_execute($stmt_2) && $result;
		oci_close($conn);

		return $result;
	}

	static function drop() {
		$conn = connect_db();
		$sql_1 = "DROP TRIGGER " . MSGS_TRIGGER;
		$sql_2 = "DROP TABLE " . MSGS_TABLE . " CASCADE CONSTRAINTS PURGE";

		$stmt_1 = oci_parse($conn, $sql_1);
		$result = oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		$result = oci_execute($stmt_2) && $result;

		oci_close($conn);
		return $result;
	}

}
?>