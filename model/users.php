<?php

require_once (dirname(dirname(__FILE__)) . '/lib/common.php');

class UsersTable {

	// Require:
	/* [
	 * 'email'=> STR
	 * 'name'=> STR
	 * 'password'=> STR
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
		//0=good/1=ban
		oci_bind_by_name($stmt, ":status", $statue);

		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function select_all() {
		$conn = connect_db();
		$sql = "select * from " . USERS_TABLE;
		$stmt = oci_parse($conn, $sql);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}

	static function select_by_id($user_id) {
		if (is_null($user_id))
			return FALSE;

		$conn = connect_db();
		$sql = "select * from " . USERS_TABLE . " where id=:user_id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_id", $user_id);
		oci_execute($stmt);

		return oci_fetch_array($stmt);
	}
	
	static function select_by_name($user_name) {
		if (is_null($user_name))
			return FALSE;

		$conn = connect_db();
		$sql = "select * from " . USERS_TABLE . " where name=:user_name";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":user_name", $user_name);
		oci_execute($stmt);

		return oci_fetch_array($stmt);
	}
	
	static function change_password($user_id, $new_pw) {
		if (is_null($user_id))
			return FALSE;
		if (is_null($new_pw))
			return FALSE;
		
		$user = UsersTable::select_by_id($user_id);
		if(!$user) return FALSE;
		
		$salt = $user['SALT'];
		$new_pw_md5 = md5($new_pw . $salt);
		
		$conn = connect_db();
		$sql = "update " . USERS_TABLE . " set pw_md5=:new_pw_md5 where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":new_pw_md5", $new_pw_md5);
		oci_bind_by_name($stmt, ":id", $user_id);
		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function ban_user($user_id) {
		if (is_null($user_id))
			return FALSE;

		$statue = 1;

		$conn = connect_db();
		$sql = "update " . USERS_TABLE . " set status=:status where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":status", $statue);
		oci_bind_by_name($stmt, ":id", $user_id);
		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function unban_user($user_id) {
		if (is_null($user_id))
			return FALSE;

		$statue = 0;

		$conn = connect_db();
		$sql = "update " . USERS_TABLE . " set status=:status where id=:id";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":status", $statue);
		oci_bind_by_name($stmt, ":id", $user_id);
		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}
	
	static function is_banned($user_id){
		$user = UsersTable::select_by_id($user_id);
		if($user){
			if($user['STATUE'] == 0){
				return FALSE;
			}else{
				return TRUE;
			}
		}else{
			// cannot find the user
			return TRUE;
		}
	}

	// how many posts a user makes in each category
	static function posts_summary($user_id){
		$conn = connect_db();
		$sql = "select category_name, count(*)
				from CS_POSTS_DETAIL
				where user_id = :id
				group by category_name
				UNION
				select category_name, 0
				from CS_POSTS_DETAIL
				where category_name not in (select category_name
											from CS_POSTS_DETAIL
											where user_id = :id)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $user_id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}
	
	// which categories that a user seldom goes
	// this use division
	static function seldom_go($user_id){
		$conn = connect_db();
		$sql = "select c.name
				from CS_CATEGORIES c
				where c.id in (Select distinct id
				                from CS_CATEGORIES
				                MINUS
				                Select distinct p.category_id
				                from cs_posts_detail p
				                where p.USER_ID = :id)";
		$stmt = oci_parse($conn, $sql);
		oci_bind_by_name($stmt, ":id", $user_id);
		oci_execute($stmt);
		oci_close($conn);

		$row = oci_fetch_all($stmt, $res);
		return $res;
	}
	
	static function create() {
		$conn = connect_db();
		$sql = "create table " . USERS_TABLE . " 
			(
			id INT PRIMARY KEY,
			email VARCHAR2(256) UNIQUE NOT NULL,
			name VARCHAR2(15) UNIQUE NOT NULL,
			pw_md5 CHAR(32) NOT NULL,
			salt CHAR(4) NOT NULL,
			register_datetime TIMESTAMP NOT NULL,
			status INT NOT NULL
			)";
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);

		return $result;
	}

	static function drop() {
		$conn = connect_db();
		$sql = "drop table " . USERS_TABLE;
		$stmt = oci_parse($conn, $sql);
		$result = oci_execute($stmt);
		oci_close($conn);
		return $result;
	}

}
?>