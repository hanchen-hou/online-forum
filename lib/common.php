<?php

require_once(dirname(__FILE__).'/config.php');

define('USERS_TABLE', 'CS_USERS');
define('ADMINS_TABLE', 'CS_ADMINS');
define('MSGS_TABLE', 'CS_MSGS');
define('POSTS_TABLE', 'CS_POSTS');
define('COMMENTS_TABLE', 'CS_COMMENTS');
define('MSGS_MANAGE_TABLE', 'CS_MSGS_MANAGE');
define('USERS_MANAGE_TABLE', 'CS_USERS_MANAGE');
define('CATEGORIES_TABLE', 'CS_CATEGORIES');
define('PROBLEM_MSGS_TABLE', 'CS_PROBLEM_MSGS');
define('MSGS_SEQ', 'CS_MSGS_SEQ');

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