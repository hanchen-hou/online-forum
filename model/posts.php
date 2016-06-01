<?php
require_once (dirname(dirname(__FILE__)) . '/lib/common.php');
require_once (dirname(__FILE__) . '/msgs.php');
require_once (dirname(__FILE__) . '/msgs_seq.php');
require_once (dirname(__FILE__) . '/categories.php');

class PostsTable {

	// Require:
	/* [
	 * 'category_id'=> INT
	 * 'user_id'=> INT
	 * 'title'=> STR
	 * 'content'=> STR
	 * ]
	 */
	static function insert($data) {
		if (is_null($data))
			return FALSE;
		if (!isset($data['category_id']))
			return FALSE;
		if (!isset($data['title']))
			return FALSE;
		if (strlen($data['title']) > TITLE_LENGTH)
			return FALSE;
		if (!CategoriesTable::select_by_id($data['category_id']))
			return FALSE;

		$id = MsgsSeq::next_value();
		if (MsgsTable::insert($id, $data)) {
			$conn = connect_db();
			$sql = "insert into " . POSTS_TABLE . " (id, category_id, title) values
			(:id, :category_id, :title)";
			$stmt = oci_parse($conn, $sql);
			oci_bind_by_name($stmt, ":id", $id);
			oci_bind_by_name($stmt, ":category_id", $data['category_id']);
			oci_bind_by_name($stmt, ":title", $data['title']);

			$result = oci_execute($stmt);
			oci_close($conn);

			return $result;
		} else {
			return FALSE;
		}
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * from " . POSTS_TABLE;
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
		$sql = "select p.id, p.title, m.content, m.datetime, u.name, m.visible 
				from " . POSTS_TABLE . " p, " . MSGS_TABLE . " m, " . USERS_TABLE . " u  
				where p.id = :id and p.id = m.id and m.user_id = u.id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		return oci_fetch_array($stmt);
	}

	static function select_visible_by_category_id($category_id) {
		if (is_null($category_id))
			return;

		$conn = connect_db();
		$sql = "select p.id, p.title, m.content, m.datetime, u.name, m.visible 
				from " . POSTS_TABLE . " p, " . MSGS_TABLE . " m, " . USERS_TABLE . " u  
				where p.category_id=:category_id and p.id = m.id and m.user_id = u.id and m.visible = 0 
				Order BY m.datetime DESC";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":category_id", $category_id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}
	
	static function select_all_by_category_id($category_id) {
		if (is_null($category_id))
			return;

		$conn = connect_db();
		$sql = "select p.id, p.title, m.content, m.datetime, u.name, m.visible 
				from " . POSTS_TABLE . " p, " . MSGS_TABLE . " m, " . USERS_TABLE . " u  
				where p.category_id=:category_id and p.id = m.id and m.user_id = u.id 
				Order BY m.datetime DESC";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":category_id", $category_id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}

	static function hide_by_id($msg_id) {
		return MsgsTable::hide_by_id();
	}

	static function restore_by_id($msg_id) {
		return MsgsTable::restore_by_id();
	}

	static function create() {
		$conn = connect_db();
		$sql = "create table " . POSTS_TABLE . " 
			(
			id INT PRIMARY KEY,
			category_id INT NOT NULL,
			title VARCHAR2(" . TITLE_LENGTH . ") NOT NULL,
			FOREIGN KEY (id) REFERENCES " . MSGS_TABLE . "(id),
			FOREIGN KEY (category_id) REFERENCES " . CATEGORIES_TABLE . "(id)
			)";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . POSTS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

}
?>