<?php
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");


$GLOBAL['result'] = 'Login Error';

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = '../index.php';
}

if (isset($_POST['user_name']) && isset($_POST['password']) && !empty($_POST['user_name']) && !empty($_POST['password'])) {

	// if the user is an admin
	$admin = AdminsTable::select_by_name($_POST['user_name']);
	
	if ($admin) {
		if ($admin['PW_MD5'] == md5($_POST['password'] . $admin['SALT'])) {
			setcookie('id', $admin['ID'], time() + 3600 * 24, '/');
			setcookie('type', 'admin', time() + 3600 * 24, '/');
			$GLOBAL['result'] = 'Login Successfully';
		} else {
			$GLOBAL['result'] = 'Wrong Password';
		}
	} else {
		$user = UsersTable::select_by_name($_POST['user_name']);
		if (count($user) > 0) {
			if ($user['PW_MD5'] == md5($_POST['password'] . $user['SALT'])) {
				setcookie('id', $user['ID'], time() + 3600 * 24, '/');
				setcookie('type', 'user', time() + 3600 * 24, '/');
				$GLOBAL['result'] = 'Login Successfully';
			} else {
				$GLOBAL['result'] = 'Wrong Password';
			}
		}
	}
} else {
	$GLOBAL['result'] = 'User name or password cannot be empty';
}

print_jump_page($GLOBAL['result'], $GLOBALS['referer']);
?>

