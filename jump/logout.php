<?php
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = '../index.php';
}

setcookie('id', '', time() - 3600, '/');
setcookie('type', '', time() - 3600, '/');

print_jump_page("Logout Successfully", "../index.php");

?>