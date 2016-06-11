<?php
/*
 * require:
 * $_POST['content']
 * $_POST['post_id']
 * $_COOKIE['id']
 * $_COOKIE['type']
 *
 */

require_once (dirname(dirname(__FILE__)) . "/model/comments.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

$GLOBALS['result'] = 'Cannot Make comment';

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
	if (!isset($_POST['post_id']) || $_POST['post_id'] == FALSE) {
		$GLOBALS['result'] = 'No Post Id';
		return;
	}
	if (!isset($_POST['content']) || $_POST['content'] == "") {
		$GLOBALS['result'] = 'Post content cannot be empty';
		return;
	}

	$data = array();
	$data['post_id'] = $_POST['post_id'];
	$data['content'] = $_POST['content'];
	$data['user_id'] = $_COOKIE['id'];

	if (trim($data['content']) == "") {
		$GLOBALS['result'] = 'Post content cannot only be spaces';
		return;
	}

	if (CommentsTable::insert($data)) {
		$GLOBALS['result'] = 'Post Successfully';
	} else {
		$GLOBALS['result'] = 'Server Error';
	}
}

print_jump_page($GLOBALS['result'], $GLOBALS['referer']);
?>
