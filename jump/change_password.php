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
				<?php echo $GLOBALS['result']
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