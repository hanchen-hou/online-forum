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
	
	UsersManageTable::drop();
	MsgsManageTable::drop();
	ProblemMsgsTable::drop();
	CommentsTable::drop();
	PostsTable::drop();
	MsgsTable::drop();
	CategoriesTable::drop();
	UsersTable::drop();
	AdminsTable::drop();
	MsgsSeq::drop();
?>