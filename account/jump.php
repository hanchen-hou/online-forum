<?php
/*
 * This file is used to recheck users type
 * type:user goes to user folder
 * type:admin goes to admin folder
 */

require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");

if (isset($_COOKIE['id']) && isset($_COOKIE['type'])) {
	if ($_COOKIE['type'] == 'admin') {
		$admin = AdminsTable::select_by_id($_COOKIE['id']);
		if (count($admin['ID']) > 0) {
			header('Location: ./admin/change_password.php');
		}
	} else if ($_COOKIE['type'] == 'user') {
		$user = UsersTable::select_by_id($_COOKIE['id']);
		if (count($user['ID']) > 0) {
			header('Location: ./user/change_password.php');
		}
	}
}else{
	header('Location: ../index.php');
}
?>