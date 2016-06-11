<?php
require_once (dirname(dirname(__FILE__)) . "/model/users.php");
require_once (dirname(__FILE__) . "/_print_jump_page_.php");

if(isset($_POST['user_name']) && isset($_POST['email']) && isset($_POST['password'])){
	$data = array();
	$data['name'] =  $_POST['user_name'];
	$data['email'] =  $_POST['email'];
	$data['password'] =  $_POST['password'];
	if(!empty($data['name']) && !empty($data['email']) && !empty($data['password'])){
		if(UsersTable::insert($data)){
			$GLOBAL['result'] = 'Register Successed';
		}else{
			$GLOBAL['result'] = 'Database Error';
		}
	}
}else{
	$GLOBAL['result'] = "Register Error";
}

print_jump_page($GLOBALS['result'], "../index.php");
?>