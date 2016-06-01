<!DOCTYPE html>
<?php
require_once (dirname(__FILE__) . "/model/users.php");

if(isset($_POST['user_name']) && isset($_POST['email']) && isset($_POST['password'])){
	$data = array();
	$data['name'] =  $_POST['user_name'];
	$data['email'] =  $_POST['email'];
	$data['password'] =  $_POST['password'];
	
	if(!is_null($data['name']) && !is_null($data['email']) && !is_null($data['password'])){
		if(UsersTable::insert($data)){
			exit('register successed');
		}
	}
}
?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<title>Sign Up</title>

		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<style>
			.user_field li {
				display: inline
			}
			.content {
				min-height: 670px;
				margin-top: 5px;
			}
			.detail {
				margin-left: 10px;
			}
			.detail_frameSize {
				width: 72%;
				min-height: 400px;
				margin-top: 23px;
				background-color: white
			}
			#category_field {
				height: 30px;
				width: 20%;
				background-color: white;
				position: fixed;
				border-radius: 2px;
				margin-top: 0;
				overflow: scroll;
			}
			#post_field {
				width: 80%;
				margin-left: 23%;
				border: 1px solid;
				background-color: #e4e4e4;
				border-style: solid;
			}
			#Category_title {
				width: 78%;
				text-align: center;
				color: #01DF3A;
				height: 10%
			}
			.page_clicker {
				margin-left: 26%
			}
			.writing_style h1 {
				font-family: Arial Black;
				color: #a3cf62;
				font-size: 200%
			}
			.writing_style p {
				font-family: Verdana;
				font-size: 100%
			}
		</style>

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

				<div class="navbar-header">
					<span class="navbar-brand glyphicon glyphicon glyphicon-align-justify" aria-hidden="true"></span>
					<label class="navbar-brand">Society Community</label>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
			</div>
		</div>
		<div class="container">
			<div style="height:115px;"></div>
			<form name="form" method="post" action="signup.php">
				<div class="form-group">
					<label for="user name">User Name address</label>
					<input type="text" class="form-control" name="user_name" id="user_name" placeholder="user name">
				</div>
				<div class="form-group">
					<label for="user name">Email address</label>
					<input type="email" class="form-control" name="email" id="email" placeholder="email">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Password">
				</div>
				<!--TODO: Check password and confirm_password in javascript, should be same -->
				<div class="form-group">
					<label for="confirm_password">Password Conformation</label>
					<input type="password" class="form-control" id="confirm_password" placeholder="Confirm Password">
				</div>
				<input type="submit" class="btn btn-default" id="submit" value="submit" style="margin-top:10px">
			</form>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>

