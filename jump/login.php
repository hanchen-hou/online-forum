<?php

require_once (dirname(dirname(__FILE__)) . "/model/admins.php");
require_once (dirname(dirname(__FILE__)) . "/model/users.php");

$GLOBAL['login_result'] = 'Login Error';

if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
	$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
} else {
	$GLOBALS['referer'] = 'index.php';
}

if (isset($_POST['user_name']) && isset($_POST['password']) && !empty($_POST['user_name']) && !empty($_POST['password'])) {
	if (isset($_POST['is_admin']) && $_POST['is_admin'] == 'on') {
		$admin = AdminsTable::select_by_name($_POST['user_name']);
		if (count($admin) > 0) {
			if ($admin['PW_MD5'] == md5($_POST['password'] . $admin['SALT'])) {
				setcookie('id', $admin['ID'], time() + 3600 * 24, '/');
				setcookie('type', 'admin', time() + 3600 * 24, '/');
				$GLOBAL['login_result'] = 'Login Successful';
			}else{
				$GLOBAL['login_result'] = 'Wrong Password';
			}
		}
	} else {
		$user = UsersTable::select_by_name($_POST['user_name']);
		if (count($user) > 0) {
			if ($user['PW_MD5'] == md5($_POST['password'] . $user['SALT'])) {
				setcookie('id', $user['ID'], time() + 3600 * 24, '/');
				setcookie('type', 'user', time() + 3600 * 24, '/');
				$GLOBAL['login_result'] = 'Login Successful';
			}else{
				$GLOBAL['login_result'] = 'Wrong Password';
			}
		}
	}
}else{
	$GLOBAL['login_result'] = 'User name or password cannot be empty';
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Login</title>
		<style type="text/css">
			body {
				background-repeat: no-repeat;
				color: #000;
				font: 9pt/200% Verdana;
			}
			a {
				text-decoration: none;
				color: #659B28
			}
			a:hover {
				text-decoration: underline;
			}
		</style>
		<style>
			@-moz-keyframes nodeInserted{
			from {
				opacity: 0.99;
			}
			to {
				opacity: 1;
			}}@-webkit-keyframes
			nodeInserted {from{
				opacity: 0.99;
			}
			to {
				opacity: 1;
			}}@-o-keyframes
			nodeInserted {from{
				opacity: 0.99;
			}
			to {
				opacity: 1;
			}}@keyframes
			nodeInserted {from{
				opacity: 0.99;
			}
			to {
				opacity: 1;
			}}
			embed, object {
				animation-duration: .001s;
				-ms-animation-duration: .001s;
				-moz-animation-duration: .001s;
				-webkit-animation-duration: .001s;
				-o-animation-duration: .001s;
				animation-name: nodeInserted;
				-ms-animation-name: nodeInserted;
				-moz-animation-name: nodeInserted;
				-webkit-animation-name: nodeInserted;
				-o-animation-name: nodeInserted;
			}
		</style>
	</head>
	<body>
		<center>
			<div style="padding:30px;padding:36px 80px;border:1px solid #a9a9a9;background:#ffffff ; text-align:center; margin:20% auto; background-repeat: no-repeat; width:55%;">
				<?php echo $GLOBAL['login_result']
				?>
				</br>
				<a href=<?php echo $GLOBALS['referer'] ?>>Click Here to go back</a>
				<script>
										var pgo = 0;
					function jump(){
					if(pgo == 0){
					location = '<?php echo $GLOBALS['referer'] ?>
						';
						pgo = 1;
						}
						}
						setTimeout('jump()',2000);
				</script>
			</div>
		</center>
	</body>
</html>