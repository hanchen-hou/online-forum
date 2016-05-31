<?php
	error_reporting(-1);
	ini_set('display_errors',1);

	//require_once(dirname(dirname(__FILE__))."/lib/common.php");
	require_once(dirname(dirname(__FILE__))."/model/admins.php");
	require_once(dirname(dirname(__FILE__))."/model/categories.php");
	require_once(dirname(dirname(__FILE__))."/model/comments.php");
	require_once(dirname(dirname(__FILE__))."/model/msgs_manage.php");
	require_once(dirname(dirname(__FILE__))."/model/msgs_seq.php");
	require_once(dirname(dirname(__FILE__))."/model/msgs.php");
	require_once(dirname(dirname(__FILE__))."/model/posts.php");
	require_once(dirname(dirname(__FILE__))."/model/problem_msgs.php");
	require_once(dirname(dirname(__FILE__))."/model/users_manage.php");
	require_once(dirname(dirname(__FILE__))."/model/users.php");
	
	CategoriesTable::create();
	UsersTable::create();
	AdminsTable::create();
	MsgsSeq::create();
	MsgsTable::create();
	PostsTable::create();
	CommentsTable::create();
	UsersManageTable::create();
	MsgsManageTable::create();
	ProblemMsgsTable::create();
	
	
?>