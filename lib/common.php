<?php

require_once(dirname(__FILE__).'/config.php');

// Tables Name
define('USERS_TABLE', 'CS_USERS');
define('ADMINS_TABLE', 'CS_ADMINS');
define('MSGS_TABLE', 'CS_MSGS');
define('POSTS_TABLE', 'CS_POSTS');
define('COMMENTS_TABLE', 'CS_COMMENTS');
define('MSGS_MANAGE_TABLE', 'CS_MSGS_MANAGE');
define('USERS_MANAGE_TABLE', 'CS_USERS_MANAGE');
define('CATEGORIES_TABLE', 'CS_CATEGORIES');
define('PROBLEM_MSGS_TABLE', 'CS_PROBLEM_MSGS');

// Sequences Name
define('CATEGORIES_SEQ', 'CS_CATEGORIES_SEQ');
define('MSGS_SEQ', 'CS_MSGS_SEQ');
define('MSGS_MANAGE_SEQ', 'CS_MSGS_MANAGE_SEQ');
define('USERS_MANAGE_SEQ', 'CS_USERES_MANAGE_SEQ');
define('PROBLEM_MSGS_SEQ', 'CS_PROBLEM_MSGS_SEQ');

// Triggers Name
define('CATEGORIES_TRIGGER', 'CS_ADD_CATEGORIES');
define('MSGS_MANAGE_TRIGGER', 'CS_ADD_MSGS_MANAGE');
define('USERS_MANAGE_TRIGGER', 'CS_ADD_USERS_MANAGE');
define('PROBLEM_MSGS_TRIGGER', 'CS_ADD_PROBLEM_MSGS');


define('USER_ID_LENGTH', 9);
define('ADMIN_ID_LENGTH', 9);
define('SALT_LENGTH', 4); 		// 4 bytes = 32 bits

define('TITLE_LENGTH', 50);
define('CONTENT_LENGTH', 512);

function connect_db()
{
	//connect database
	$conn = oci_connect(DB_USER, DB_PASS, DB_DATABASENAME);
	//$conn->query("set names 'utf8'");
	if (!$conn) {
  		$e = oci_error();   // For oci_connect errors pass no handle
  		echo htmlentities($e['message']);
	}else{
		return $conn;
	}
}


function create_random_string($str_length)
{
	// random string dict
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	//$chars = "abcdefghijklmnopqrstuvwxyz0123456789";

	$str = "";
	for ($i = 0; $i < $str_length; $i++)
	{
		$str .= $chars[mt_rand(0, strlen($chars) - 1)];
	}

	return $str;
}

function create_random_num($str_length)
{
	// random string dict
	$chars = "0123456789";
	//$chars = "abcdefghijklmnopqrstuvwxyz0123456789";

	$num = "";
	for ($i = 0; $i < $str_length; $i++)
	{
		$num .= $chars[mt_rand(0, strlen($chars) - 1)];
	}
	return $num;
}

?>