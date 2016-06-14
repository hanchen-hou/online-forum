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
		if (!isset($data['user_id']))
			return FALSE;
		if (!isset($data['title']))
			return FALSE;
		if (strlen($data['title']) > TITLE_LENGTH)
			return FALSE;
		if (!isset($data['content']))
			return FALSE;
		if (strlen($data['content']) > CONTENT_LENGTH)
			return FALSE;
		if (!CategoriesTable::select_by_id($data['category_id']))
			return FALSE;

		$conn = connect_db();
		
		$msgs_insert_sql = "insert into " . MSGS_TABLE . " (user_id, datetime, content) values
			(:user_id, CURRENT_TIMESTAMP, :content)";
		$stmt = oci_parse($conn, $msgs_insert_sql);
		oci_bind_by_name($stmt, ":user_id", $data['user_id']);
		oci_bind_by_name($stmt, ":content", $data['content']);
		$msgs_insert_result = oci_execute($stmt, OCI_DEFAULT);
		
		if ($msgs_insert_result) {
			$posts_insert_sql = "insert into " . POSTS_TABLE . " (category_id, title) values
			(:category_id, :title)";
			$stmt = oci_parse($conn, $posts_insert_sql);
			oci_bind_by_name($stmt, ":category_id", $data['category_id']);
			oci_bind_by_name($stmt, ":title", $data['title']);

			$posts_insert_result = oci_execute($stmt, OCI_DEFAULT);
			
			// need to make sure both insertions are successfully
			// otherwise roll back
			if($posts_insert_result){
				oci_commit($conn);
			}else{
				oci_rollback($conn);
			}
			
			oci_close($conn);
			return $posts_insert_result;
		} else {
			oci_close($conn);
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
		$sql = "select p.id, p.title, m.content, TO_CHAR(m.datetime,'YYYY-MM-DD') as DATETIME, u.name as USER_NAME
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
		$sql_2 = "CREATE OR REPLACE TRIGGER " . POSTS_TRIGGER . " 
			BEFORE INSERT ON " . POSTS_TABLE . " 
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
		$sql_1 = "DROP TRIGGER " . POSTS_TRIGGER;
		$sql_2 = "drop table " . POSTS_TABLE . " CASCADE CONSTRAINTS PURGE";

		$stmt_1 = oci_parse($conn, $sql_1);
		$result = oci_execute($stmt_1);
		$stmt_2 = oci_parse($conn, $sql_2);
		$result = oci_execute($stmt_2) && $result;

		oci_close($conn);
		return $result;
	}

	static function create_view() {
		$conn = connect_db();
		$sql = "create view " . POSTS_VIEW . "(CATEGORY_ID, CATEGORY_NAME, USER_ID, USER_NAME, POST_ID, TITLE, CONTENT, DATETIME) As
				  select c.id, c.name, u.id, u.name, p.id, p.title, m.content, m.datetime
				  from " . MSGS_TABLE . " m, " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . CATEGORIES_TABLE . " c 
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