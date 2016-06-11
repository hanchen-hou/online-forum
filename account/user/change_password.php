<?php
require_once (dirname(dirname(dirname(__FILE__))) . "/model/users.php");
/*
 * check cookie
 * otherwise, cannot access this page
 */
if (isset($_COOKIE['id']) && isset($_COOKIE['type'])) {
	if ($_COOKIE['type'] == 'user') {
		$user = UsersTable::select_by_id($_COOKIE['id']);
		if ($user) {
			$GLOBALS['user_name'] = $user['NAME'];
		}
	}
}

if(!isset($GLOBALS['user_name'])){
	exit("No Permission");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Bootstrap 101 Template</title>

		<!-- Bootstrap -->
		<link href="../../css/bootstrap.min.css" rel="stylesheet">
		<link href="../../css/simple-sidebar.css" rel="stylesheet">
		<link href="../../css/form/customize.css" rel="stylesheet" />
		<script type="text/javascript" src="jquery-1.12.4.min.js"></script>
		

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container">
				<div class="navbar-header col-md-9">
					<span class="navbar-brand glyphicon glyphicon glyphicon-align-justify" aria-hidden="true"></span>
					<label class="navbar-brand">Society Community</label>
				</div>
				<div class="navbar-header navbar-brand" style="color:green">
					Welcome
				</div>
				<div class="navbar-header navbar-brand">
					<a href=""><?php echo $GLOBALS['user_name'] ?></a>
				</div>
				<div class="navbar-header navbar-brand">
					<a href="../../jump/logout.php">
						<button type="button" class="btn btn-danger">Logout</button>
					</a>
				</div>
			</div>
		</div>
		
		<div class="container">
			<div style="height:115px;"></div>
			<div id="error" class=""></div>
			<form method="post" action="../../jump/change_password.php" id="user_form">
				<div class="form-group">
					<label for="old_password">Old Password</label>
					<input name="old_pw" type="password" class="form-control" id="old_password" placeholder="Old Password">
				</div>
				<div class="form-group">
					<label for="new_password">New Password</label>
					<input name="new_pw" type="password" class="form-control" id="new_password" placeholder="New Password">
				</div>
				<div class="form-group">
					<label for="new_password">Confirm New Password</label>
					<input name="confirm_pw" type="password" class="form-control" id="confirm_password" placeholder="Confirm Password">
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-primary " id="submit" value="submit" style="margin-top:10px">
				</div>
			</form>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
		<script>
			$('#user_form').submit(function(event) {

				var error_message = checkform();
				if (error_message != "")
					event.preventDefault();

				$('#error').html(error_message);
			});
			function checkform() {
				var error_M = "";
				var error_color = "rgb(217,83,79)";
				var correct_color = "rgb(76,175,80)";
				if ($('#user_name').val() == "") {
					error_M += "User name is empty<br>";
					$('#user_name').css("background-color", error_color);
				} else {
					$('#user_name').css("background-color", correct_color);
				}

				if ($('#old_password').val() == "") {
					error_M += "Old password is empty<br>";
					$('#old_password').css("background-color", error_color);
				} else {
					$('#old_password').css("background-color", correct_color);
				}
				if ($('#new_password').val() == "") {
					error_M += "New password is empty<br>";
					$('#new_password').css("background-color", error_color);
				} else {
					$('#new_password').css("background-color", correct_color);
				}
				if ($('#confirm_password').val() == "") {
					error_M += "Confirm password is empty<br>";
					$('#confirm_password').css("background-color", error_color);
				} else {
					$('#confirm_password').css("background-color", correct_color);
				}
				if ($('#new_password').val() != $('#confirm_password').val()) {
					error_M += "Confirm password does not match the new password";
					$('#new_password').css("background-color", error_color);
					$('#confirm_password').css("background-color", error_color);
				}
				return error_M;
			}
		</script>
	</body>
</html>
