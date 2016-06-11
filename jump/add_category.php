<?php
require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
/*
 * check cookie, only admin can access this page
 * otherwise, cannot access this page
 */
if (isset($_COOKIE['id']) && isset($_COOKIE['type'])) {
	if ($_COOKIE['type'] == 'admin') {
		$admin = AdminsTable::select_by_id($_COOKIE['id']);
		if ($admin) {
			$GLOBALS['user_name'] = $admin['NAME'];
		}
	}
}

if (!isset($GLOBALS['user_name'])) {
	exit("No Permission");
}
?>

<?php
require_once (dirname(dirname(__FILE__)) . "/model/categories.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

if (isset($_POST['name']) && isset($_POST['admin_id'])) {
	$data = array();
	$data['name'] = $_POST['name'];
	$data['admin_id'] = $_POST['admin_id'];
	if (!empty($data['name']) && !empty($data['admin_id'])) {
		if (CategoriesTable::insert($data)) {
			$GLOBAL['result'] = 'Add New Category Successfully';
		} else {
			$GLOBAL['result'] = 'Database Error';
		}
	}
} else {
	$GLOBAL['result'] = "Category Name cannot be none";
}

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = '../index.php';
}

print_jump_page($GLOBAL['result'], $GLOBALS['referer']);
?>