<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');
require_once (dirname(__FILE__) . '/msgs.php');
require_once (dirname(__FILE__) . '/msgs_seq.php');

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
		if (!PostsTable::select_by_id($data['post_id']))
			return FALSE;

		$id = MsgsSeq::next_value();
		if (MsgsTable::insert($id, $data)) {
			$conn = connect_db();
			$sql = "insert into " . COMMENTS_TABLE . " (id, post_id) values
			(:id, :post_id)";
			$stmt = oci_parse($conn, $sql);
			oci_bind_by_name($stmt, ":id", $id);
			oci_bind_by_name($stmt, ":post_id", $data['post_id']);

			$result = oci_execute($stmt);
			oci_close($conn);

			return $result;
		} else {
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
				Where c.id = :id and m.id = c.id and u.id = m.user_id 
				Order BY m.datetime ASC";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		return oci_fetch_array($stmt);
	}

	static function select_visible_by_post_id($post_id) {
		if (is_null($post_id))
			return;

		$conn = connect_db();
		$sql = "Select m.content, m.datetime, u.name, m.visible 
				From " . COMMENTS_TABLE . " c, " . MSGS_TABLE . " m, " . USERS_TABLE . " u 
				Where c.post_id=:post_id and c.id = m.id and u.id = m.user_id and m.visible = 0
				Order BY m.datetime ASC";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":post_id", $post_id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}
	
	static function select_all_by_post_id($post_id) {
		if (is_null($post_id))
			return;

		$conn = connect_db();
		$sql = "Select m.content, m.datetime, u.name, m.visible 
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

	static function create() {
		$conn = connect_db();
		$sql = "create table " . COMMENTS_TABLE . " 
			(
			id INT PRIMARY KEY,
			post_id INT NOT NULL,
			FOREIGN KEY (id) REFERENCES " . MSGS_TABLE . "(id),
			FOREIGN KEY (post_id) REFERENCES " . POSTS_TABLE . "(id)
			)";
		$stmt = oci_parse($conn, $sql);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . COMMENTS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

}
?>