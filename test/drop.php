<?php
error_reporting(-1);
ini_set('display_errors', 1);

//require_once(dirname(dirname(__FILE__))."/lib/common.php");
require_once (dirname(dirname(__FILE__)) . "/model/msgs_seq.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(dirname(__FILE__)) . "/model/banned_users.php");
require_once (dirname(dirname(__FILE__)) . "/model/categories.php");
require_once (dirname(dirname(__FILE__)) . "/model/msgs.php");
require_once (dirname(dirname(__FILE__)) . "/model/posts.php");
require_once (dirname(dirname(__FILE__)) . "/model/comments.php");

CategoriesTable::drop_view();
PostsTable::drop_view();

CommentsTable::drop();
PostsTable::drop();
MsgsTable::drop();
MsgsSeq::drop();
CategoriesTable::drop();
BannedUsersTable::drop();
AdminsTable::drop();
UsersTable::drop();
?>