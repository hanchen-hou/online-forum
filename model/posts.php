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
			return FALSE;

		$conn = connect_db();
		$sql = "select p.id, p.title, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD HH24:MI:SS') as DATETIME, u.name as user_name 
				from " . POSTS_TABLE . " p, " . MSGS_TABLE . " m, " . USERS_TABLE . " u  
				where p.id = :id and p.id = m.id and m.user_id = u.id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $id);
		oci_execute($stmt);
		oci_close($conn);

		return oci_fetch_array($stmt);
	}

	static function select_by_category_id($category_id) {
		if (is_null($category_id))
			return FALSE;

		$conn = connect_db();
		$sql = "select p.id, p.title, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD') as DATETIME, u.id as USER_ID, u.name as USER_NAME, u.status as USER_STATUS
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

	static function delete_by_id($id) {
		return MsgsTable::delete_by_id($id);
	}

	static function create() {
		$conn = connect_db();
		$sql = "create table " . POSTS_TABLE . " 
			(
			id INT PRIMARY KEY,
			category_id INT NOT NULL,
			title VARCHAR2(" . TITLE_LENGTH . ") NOT NULL,
			FOREIGN KEY (id) REFERENCES " . MSGS_TABLE . "(id) ON DELETE CASCADE,
			FOREIGN KEY (category_id) REFERENCES " . CATEGORIES_TABLE . "(id) ON DELETE CASCADE
			)";
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . POSTS_TABLE ." CASCADE CONSTRAINTS PURGE";
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

	static function create_view() {
		$conn = connect_db();
		$sql = "create view ".POSTS_VIEW."(CATEGORY_ID, CATEGORY_NAME, USER_ID, USER_NAME, POST_ID, TITLE, CONTENT, DATETIME) As
				  select c.id, c.name, u.id, u.name, p.id, p.title, m.content, m.datetime
				  from ".MSGS_TABLE." m, ".POSTS_TABLE." p, ".USERS_TABLE." u, ".CATEGORIES_TABLE." c 
				  where m.id = p.id and u.id = m.user_id and c.id = p.category_id";

		$stmt = oci_parse($conn, $sql);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}
	
	static function drop_view() {
		$conn = connect_db();
		$sql = "drop view " . POSTS_VIEW;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}
}
?>