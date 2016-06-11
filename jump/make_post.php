<?php
/*
 * require:
 * $_POST['title']
 * $_POST['content']
 * $_POST['category_id']
 * $_COOKIE['id']
 * $_COOKIE['type']
 * 
 */
require_once (dirname(dirname(__FILE__)) . "/model/posts.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

$GLOBALS['result'] = 'Cannot Make post';

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = '../index.php';
}

main();

function main() {
	if (!isset($_COOKIE['id']) || $_COOKIE['id'] == FALSE || !isset($_COOKIE['type']) || $_COOKIE['type'] == FALSE) {
		$GLOBALS['result'] = 'Please Login';
		return;
	}
	if (!isset($_POST['category_id']) || $_POST['category_id'] == FALSE) {
		$GLOBALS['result'] = 'No Category';
		return;
	}
	if (!isset($_POST['title']) || $_POST['title'] == "") {
		$GLOBALS['result'] = 'Post title cannot be empty';
		return;
	}
	if (!isset($_POST['content']) || $_POST['content'] == "") {
		$GLOBALS['result'] = 'Post content cannot be empty';
		return;
	}

	$data = array();
	$data['category_id'] = $_POST['category_id'];
	$data['user_id'] = $_COOKIE['id'];
	$data['title'] = $_POST['title'];
	$data['content'] = $_POST['content'];
	
	if(trim($data['title']) == FALSE){
		$GLOBALS['result'] = 'Post title cannot only be spaces';
		return;
	}
	if(trim($data['content']) == FALSE){
		$GLOBALS['result'] = 'Post content cannot only be spaces';
		return;
	}
	
	if (PostsTable::insert($data)) {
		$GLOBALS['result'] = 'Post Successfully';
	} else {
		$GLOBALS['result'] = 'Server Error';
	}
}

print_jump_page($GLOBALS['result'], $GLOBALS['referer']);
?>