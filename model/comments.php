<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');
require_once (dirname(__FILE__) . '/msgs.php');
require_once (dirname(__FILE__) . '/msgs_seq.php');
require_once (dirname(__FILE__) . '/posts.php');

class CommentsTable {

	// Require:
	/* [
	 * 'post_id'=> INT
	 * 'user_id'=> INT
	 * 'content'=> STR
	 * ]
	 */
	static function insert($data) {
		if (is_null($data))
			return;
		if (!isset($data['post_id']))
			return FALSE;
		if (!isset($data['user_id']))
			return FALSE;
		if (!isset($data['content']))
			return FALSE;
		if (strlen($data['content']) > CONTENT_LENGTH)
			return FALSE;
		if (!PostsTable::select_by_id($data['post_id']))
			return FALSE;

		$conn = connect_db();
		
		$msgs_insert_sql = "insert into " . MSGS_TABLE . " (user_id, datetime, content) values
			(:user_id, CURRENT_TIMESTAMP, :content)";
		$stmt = oci_parse($conn, $msgs_insert_sql);
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":content", $data['content']);
		$msgs_insert_result = oci_execute($stmt, OCI_DEFAULT);

		if ($msgs_insert_result) {
			$comments_insert_sql = "insert into " . COMMENTS_TABLE . " (post_id) values
			(:post_id)";
			$stmt = oci_parse($conn, $comments_insert_sql);
			oci_bind_by_name($stmt, ":id", $id);
			oci_bind_by_name($stmt, ":post_id", $data['post_id']);

			$comments_insert_result = oci_execute($stmt);
			
			if($comments_insert_result){
				oci_commit($conn);
			}else{
				oci_rollback($conn);
			}
			
			oci_close($conn);
			return $msgs_insert_result;
		} else {
			oci_close($conn);
			return FALSE;
		}
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * from " . COMMENTS_TABLE;
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
		$sql = "Select m.content, m.datetime, u.name, m.visible 
				From " . COMMENTS_TABLE . " c, " . MSGS_TABLE . " m, " . USERS_TABLE . " u 
				Where c.id = :id and m.id = c.id and u.id = m.user_id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		return oci_fetch_array($stmt);
	}

	static function select_by_post_id($post_id) {
		if (is_null($post_id))
			return;

		$conn = connect_db();
		$sql = "Select m.id, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD') as DATETIME, u.id as USER_ID, u.name as USER_NAME, u.status as USER_STATUS
				From " . COMMENTS_TABLE . " c, " . MSGS_TABLE . " m, " . USERS_TABLE . " u 
				Where c.post_id=:post_id and c.id = m.id and u.id = m.user_id
				Order BY m.datetime ASC";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":post_id", $post_id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}

	static function delete_by_id($id) {
		return MsgsTable::delete_by_id($id);
	}

	static function create() {
		$conn = connect_db();
		$sql_1 = "create table " . COMMENTS_TABLE . " 
			(
			id INT PRIMARY KEY,
			post_id INT NOT NULL,
			FOREIGN KEY (id) REFERENCES " . MSGS_TABLE . "(id) ON DELETE CASCADE,
			FOREIGN KEY (post_id) REFERENCES " . POSTS_TABLE . "(id) ON DELETE CASCADE
			)";
		$sql_2 = "CREATE OR REPLACE TRIGGER " . COMMENTS_TRIGGER . " 
			BEFORE INSERT ON " . COMMENTS_TABLE . " 
			FOR EACH ROW
			BEGIN
			  SELECT " . MSGS_SEQ . ".CURRVAL
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
		$sql_1 = "DROP TRIGGER " . COMMENTS_TRIGGER;
		$sql_2 = "DROP TABLE " . COMMENTS_TABLE . " CASCADE CONSTRAINTS PURGE";

		$stmt_1 = oci_parse($conn, $sql_1);
		$result = oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		$result = oci_execute($stmt_2) && $result;

		oci_close($conn);
		return $result;
	}

}
?>