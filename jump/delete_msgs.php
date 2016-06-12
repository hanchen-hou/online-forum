<?php
/*
 * require:
 * $_GET['msgs_id']
 * $_COOKIE['id'] // must be admin
 * $_COOKIE['type']
 * 
 */
 
require_once (dirname(dirname(__FILE__)) . "/model/msgs.php");
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");


$GLOBALS['result'] = 'Delete Error';

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
	if (!isset($_GET['msgs_id']) || $_GET['msgs_id'] == FALSE) {
		$GLOBALS['result'] = 'No Post or Comment ID';
		return;
	}
	if (!AdminsTable::select_by_id($_COOKIE['id'])){
		$GLOBALS['result'] = 'You are not admin.';
		return;
	}
	
	if (MsgsTable::delete_by_id($_GET['msgs_id'])) {
		$GLOBALS['result'] = 'Delete Successfully';
	} else {
		$GLOBALS['result'] = 'Server Error';
	}
}

print_jump_page($GLOBALS['result'], $GLOBALS['referer']);
?>

