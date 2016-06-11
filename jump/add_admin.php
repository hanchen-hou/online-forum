<?php
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

if (isset($_POST['user_name']) && isset($_POST['email']) && isset($_POST['password'])) {
	if ($_POST['password'] == $_POST['confirm_password']) {
		$data = array();
		$data['name'] = $_POST['user_name'];
		$data['email'] = $_POST['email'];
		// senior_id can be none
		$data['senior_id'] = $_POST['senior_id'];
		$data['password'] = $_POST['password'];
		$data['confirm_password'] = $_POST['confirm_password'];
		if (!empty($data['name']) && !empty($data['email']) && !empty($data['password']) && !empty($data['confirm_password'])) {
			if (AdminsTable::insert($data)) {
				$GLOBAL['result'] = 'Add New Admin Successfully';
			} else {
				$GLOBAL['result'] = 'Database Error';
			}
		}
	} else {
		$GLOBAL['result'] = 'Confirm password should be same.';
	}
} else {
	$GLOBAL['result'] = "Register Error";
}

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = '../index.php';
}

print_jump_page($GLOBAL['result'], $GLOBALS['referer']);
?>