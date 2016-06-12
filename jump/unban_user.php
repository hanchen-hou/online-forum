<?php
/*
 * require:
 * $_GET['user_id']
 * $_COOKIE['id'] // must be admin
 * $_COOKIE['type']
 * 
 */
 
require_once (dirname(dirname(__FILE__)) . "/model/banned_users.php");
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");


$GLOBALS['result'] = 'Banned Error';

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
	if (!isset($_GET['user_id']) || $_GET['user_id'] == FALSE) {
		$GLOBALS['result'] = 'No User ID';
		return;
	}
	if (AdminsTable::select_by_id($_COOKIE['id']) == FALSE){
		$GLOBALS['result'] = 'Only admins can unban users.';
		return;
	}
	if (!BannedUsersTable::select_by_id($_GET['user_id'])){
		$GLOBALS['result'] = 'This user is not banned!';
		return;
	}

	$data = array();
	$data['user_id'] = $_GET['user_id'];
	$data['admin_id'] = $_COOKIE['id'];
	
	if (BannedUsersTable::unban_by_id($data['user_id'], $data['admin_id'])) {
		$GLOBALS['result'] = 'Unban Successfully';
	} else {
		$GLOBALS['result'] = 'Server Error';
	}
}

print_jump_page($GLOBALS['result'], $GLOBALS['referer']);
?>

