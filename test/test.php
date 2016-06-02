<?php

error_reporting(-1);
ini_set('display_errors', 1);

require_once (dirname(dirname(__FILE__)) . "/model/msgs_seq.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(dirname(__FILE__)) . "/model/banned_users.php");
require_once (dirname(dirname(__FILE__)) . "/model/categories.php");
require_once (dirname(dirname(__FILE__)) . "/model/msgs.php");
require_once (dirname(dirname(__FILE__)) . "/model/posts.php");
require_once (dirname(dirname(__FILE__)) . "/model/comments.php");

function insert_test($file_name, $fun) {
	$json = fopen($file_name, "r") or die("Unable to open file!");
	$text = fread($json, filesize($file_name));
	$obj = json_decode($text, true);
	foreach ($obj as $unit) {
		call_user_func($fun, $unit);
	}
}

function random_get_id($fun) {
	$all_ids = call_user_func($fun, null);
	$rand = mt_rand(0, count($all_ids['ID']) - 1);
	return $all_ids['ID'][$rand];
}

function posts_insert_test($file_name = "sample_posts_json.txt") {
	$json = fopen($file_name, "r") or die("Unable to open file!");
	$text = fread($json, filesize($file_name));
	$obj = json_decode($text, true);
	foreach ($obj as $unit) {
		$unit['category_id'] = random_get_id("CategoriesTable::select_all");
		$unit['user_id'] = random_get_id("UsersTable::select_all");
		PostsTable::insert($unit);
	}
}

function comments_insert_test($file_name = "sample_comments_json.txt") {
	$json = fopen($file_name, "r") or die("Unable to open file!");
	$text = fread($json, filesize($file_name));
	$obj = json_decode($text, true);
	foreach ($obj as $unit) {
		$unit['post_id'] = random_get_id("PostsTable::select_all");
		$unit['user_id'] = random_get_id("UsersTable::select_all");
		CommentsTable::insert($unit);
	}
}

//insert_test("sample_users_json.txt", "UsersTable::insert");
//insert_test("sample_admins_json.txt", "AdminsTable::insert");
//insert_test("sample_categories_json.txt", "CategoriesTable::insert");

//posts_insert_test();
//comments_insert_test();

//echo random_get_id("UsersTable::select_all");
//echo random_get_id("AdminsTable::select_all");
//echo random_get_id("CategoriesTable::select_all");

//var_dump(UsersTable::select_by_id("12"));
//var_dump(PostsTable::select_by_category_id(random_get_id("CategoriesTable::select_all")));
//var_dump(CategoriesTable::select_all());

//echo random_get_id("MsgsTable::select_all");
?>
