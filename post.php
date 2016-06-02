<?php
require_once (dirname(__FILE__) . "/model/comments.php");
require_once (dirname(__FILE__) . "/model/posts.php");

if (isset($_GET['post_id']) && is_numeric($_GET['post_id'])) {
	$post = PostsTable::select_by_id($_GET['post_id']);
	if (is_null($post)) {
		exit('no post id');
	}

	$GLOBALS['comments'] = CommentsTable::select_by_post_id($_GET['post_id']);
} else {
	exit('no post id');
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
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/simple-sidebar.css" rel="stylesheet">
		<style>
			.user_field li {
				display: inline
			}
			.content {
				min-height: 670px;
				margin-top: 5px;
			}
			.detail {
				margin-left: 9%;
			}
			.detail_frameSize {
				width: 70%;
				min-height: 100px;
				margin-top: 23px;
				background-color: white
			}
			#category_field {
				height: 30px;
				width: 15%;
				background-color: white;
				position: fixed;
				border-radius: 2px;
				margin-top: 0;
				overflow-x: hidden;
				overflow-y: auto;
			}
			#post_field {
				width: 83%;
				margin-left: 17%;
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
				font-size: 200%;
			}
			.writing_style p {
				font-family: Verdana;
				font-size: 100%;
			}
			.selected {
				background-color: rgb(206,255,104);
				color: black;
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

				<div class="collapse navbar-collapse user_field">
					<ul class="nav navbar-nav navbar-right" >
						<?php
						require_once (dirname(__FILE__) . "/model/admins.php");
						require_once (dirname(__FILE__) . "/model/users.php");

						$login_form = '<form class="form-inline navbar-form" method="post" action="jump/login.php">
<li>
<label style="color:white; margin-right: 5px">User name:</label>
<input type="text" class="form-control" name="user_name" placeholder="user name" />
</li>

<li>
<label style="color:white; margin-right: 5px">Password:</label>
<input type="password" class="form-control" name="password" placeholder="password" />
</li>
<li>
<label style="color: white;margin-right: 5px">Admin:</label>
<input type="checkbox" name="is_admin" class="checkbox" />
</li>
<li>
<input type="submit" value="Login" id="Login" class="form-control" name="login">
</li>
<li>
<a href="signup.php"><input type="button" value="Signup" id="Signup" class="form-control" name="signup"></a>
</li>
</form>';
						$after_login = '<div class="navbar-header navbar-brand" style="color:green">Welcome</div>
<div class="navbar-header navbar-brand">%s</div>
<a href="logout.php"><button type="button" class="btn btn-danger">Logout</button></a>';
						$sub_page = $login_form;

						//error_reporting(-1);
						//ini_set('display_errors', 1);

						/*
						 * check cookie
						 * otherwise, need login
						 */
						if (isset($_COOKIE['id']) && isset($_COOKIE['type']) && !empty($_COOKIE['id']) && !empty($_COOKIE['type'])) {
							if ($_COOKIE['type'] == 'admin') {
								$admin = AdminsTable::select_by_id($_COOKIE['id']);
								if (count($admin['ID']) > 0) {
									$sub_page = sprintf($after_login, $admin['NAME']);
								}
							} else if ($_COOKIE['type'] == 'user') {
								$user = UsersTable::select_by_id($_COOKIE['id']);
								if (count($user['ID']) > 0) {
									$sub_page = sprintf($after_login, $user['NAME']);
								}
							}
						}
						echo $sub_page;
						?>
				</div>
			</div>
		</div>

		<div class="container-fluid" style="overflow-x: hidden; overflow-y: scroll;">
			<div class="content" id="category_field" style="margin-top:5%;margin-right:2%" >
				<ul class="sidebar-nav" style="width=90%" >
					<label>Category</label>
					<?php
					/*
					 *  Category Part
					 */

					require_once (dirname(__FILE__) . "/model/categories.php");
					$template = '<li><a class="sidebar-brand" href="index.php?category=%s&page=1">%s</a></li>';
					$template_selected = '<li class="selected"><a class="sidebar-brand" href="index.php?category=%s&page=1">%s</a></li>';
					$categories = CategoriesTable::select_all();

					$GLOBALS['category_id'] = $categories['ID'][0];
					$GLOBALS['category_name'] = $categories['NAME'][0];
					if (isset($_GET['category_id'])) {
						for ($i = 0; $i < count($categories['ID']); $i++) {
							if ($categories['ID'][$i] == $_GET['category_id']) {
								$GLOBALS['category_id'] = $categories['ID'][$i];
								$GLOBALS['category_name'] = $categories['NAME'][$i];
								break;
							}
						}
					}

					for ($i = 0; $i < count($categories['ID']); $i++) {
						if ($categories['ID'][$i] == $GLOBALS['category_id']) {
							echo sprintf($template_selected, $categories['ID'][$i], $categories['NAME'][$i]);
						} else {
							echo sprintf($template, $categories['ID'][$i], $categories['NAME'][$i]);
						}
					}
					?>
				</ul>
			</div>
			<div class="content" id="post_field" style="margin-top: 5%">
				<div class="container" style="overflow-x: hidden; overflow-y: scroll;">
					<?php

					/*
					 *  Current Post Part
					 */
					?>
					<div class="container detail" id="post_title">
						<h2><?php $post = $GLOBALS['post'];
						echo $post['TITLE'];
 ?></h2>
					</div>
					<div class="container detail detail_frameSize writing_style" id="<? echo $_GET['post_id']; ?>">
						<p>
							<?php $post = $GLOBALS['post'];
							echo $post['CONTENT'];
 ?>
						</p>
					</div>
					
					<?php

					/*
					 *  Comments Part
					 */
					$template = '<div class="container detail detail_frameSize writing_style" id="%s">%s</div>';

					$comments = $GLOBALS['comments'];
					$GLOBALS['total_pages'] = ceil(count($comments['ID']) / COMMENTS_NUM_ONE_PAGE);

					$GLOBALS['page'] = 1;
					if (isset($_GET['page']) && is_numeric($_GET['page'])) {
						if ($GLOBALS['total_pages'] >= $_GET['page']) {
							$GLOBALS['page'] = $_GET['page'];
						}
					}
					$offset = ($GLOBALS['page'] - 1) * COMMENTS_NUM_ONE_PAGE;
					for ($i = $offset, $j = 0; $i < count($comments['ID']) && $j < 10; $i++, $j++) {
						echo sprintf($template, $comments['ID'][$i], $comments['CONTENT'][$i]);
					}
					?>
						<div class="container page_clicker">
							<ul class="pagination" >
								<?php

								/*
								 * Page Number
								 */

								$start_num = ($GLOBALS['page'] - ($GLOBALS['page'] % 5)) + 1;
								$template = '<li><a href="post.php?post_id=%s&page=%s">%s</a></li>';

								if ($start_num - 1 > 0) {
									echo sprintf($template, $_GET['post_id'], $start_num - 1, '&laquo;');
								}
								for ($i = 0; $i < 5; $i++) {
									if ($start_num + $i > $GLOBALS['total_pages'])
										break;

									if ($start_num + $i == $GLOBALS['page']) {
										echo '<li><a>' . $GLOBALS['page'] . '</a></li>';
									} else {
										echo sprintf($template, $_GET['post_id'], $start_num + $i, $start_num + $i);
									}
								}
								if ($start_num + 5 < $GLOBALS['total_pages']) {
									echo sprintf($template, $_GET['post_id'], $start_num + 5, '&raquo;');
								}
							?>
							</ul>
						</div>
						
					<form action="jump/make_comment.php" method="post">
						<div class="form-group">
							<label for="Post">Comment:</label>
							<textarea name="content" style="width: 70%" class="form-control" id="post_content" placeholder="Your post content"></textarea>
							<input style="display: none" name="post_id" value="<?php echo $_GET['post_id'] ?>"/>
						</div>
						<input type="submit" class="btn btn-default" value="Submit" id="post_comment">
					</form>
				</div>
			</div>
		</div>

		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>