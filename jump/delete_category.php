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

if (isset($_POST['name'])) {
	$category_name = $_POST['name'];
	if (!empty($category_name)) {
		if (CategoriesTable::delete_by_name($category_name)) {
			$GLOBALS['result'] = 'Delete Category Successfully';
		} else {
			$GLOBALS['result'] = 'There are still some posts in this category';
		}
	}else{
		$GLOBALS['result'] = 'Category name cannot be none';
	}
} else {
	$GLOBALS['result'] = "Category Name cannot be none";
}

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = '../index.php';
}

print_jump_page($GLOBALS['result'], $GLOBALS['referer']);
?>
