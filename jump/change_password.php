<?php
/*
 * require:
 * $_POST['old_pw']
 * $_POST['new_pw']
 * $_POST['confirm_pw']
 * $_COOKIE['id']
 * $_COOKIE['type']
 *
 */
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

$GLOBALS['result'] = 'Cannot Change Password';

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
	if (!isset($_POST['old_pw']) || $_POST['old_pw'] == FALSE) {
		$GLOBALS['result'] = 'Old password cannot be empty.';
		return;
	}
	if (!isset($_POST['new_pw']) || $_POST['new_pw'] == "") {
		$GLOBALS['result'] = 'New password cannot be empty';
		return;
	}
	if (!isset($_POST['confirm_pw']) || $_POST['confirm_pw'] == "") {
		$GLOBALS['result'] = 'Please type password again.';
		return;
	}
	if ($_POST['new_pw'] != $_POST['confirm_pw']) {
		$GLOBALS['result'] = 'The new password and the confirm password are different.';
		return;
	}

	$user=UsersTable::select_by_id($_COOKIE['id']);
	if(!user){
		$GLOBALS['result'] = 'The user does not exists';
		return;
	}

	if(md5($_POST['old_pw'].$user['SALT']) != $user['PW_MD5']){
		$GLOBALS['result'] = 'The old password is incorrect.';
		return;
	}

	UsersTable::change_password($_COOKIE['id'], $_POST['new_pw']);
	$GLOBALS['result'] = 'Change successfully.';
}

print_jump_page($GLOBALS['result'], $GLOBALS['referer']);
?>
