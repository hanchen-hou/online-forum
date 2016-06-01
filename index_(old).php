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
			.page_clicker{margin-left: 26%}
			.writing_style h1{font-family:Arial Black;color: #a3cf62;font-size: 200%}
			.writing_style p{font-family:Verdana;font-size: 100%}
		</style

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
						<form class="form-inline navbar-form" method="post" action="#">
							<li>
								<label style="color:white; margin-right: 5px">User name:</label>
								<input type="text" class="form-control" placeholder="user name" />
							</li>

							<li>
								<label style="color:white; margin-right: 5px">Password:</label>
								<input type="text" class="form-control" placeholder="password" />
							</li>
							<li>
								<label style="color: white;margin-right: 5px">Admin:</label>
								<input type="checkbox" class="checkbox" />
							</li>
							<li>
								<input type="submit" value="Login" id="Login" class="form-control" name="login">
								<input type="submit" value="Signup" id="Signup" class="form-control" name="signup">
							</li>
						</form>
				</div>
			</div>
		</div>

		<div class="container" style="overflow-x: hidden;overflow-y: scroll;">
			<div style="height: 60px"></div>
			<div class="content" id="category_field" >
				<ul class="list-group" >
					<label>Category</label>
					<?php
					require_once (dirname(__FILE__) . "/model/categories.php");
					$template = '<li><a class="list-group-item" href="index.php?category=%s&page=1">%s</a></li>';
					$categories = CategoriesTable::select_all();

					$GLOBAL['page'] = 1;
					if (isset($_GET['page']) && is_numeric($_GET['page'])) {
						if ($GLOBALS['total_pages'] > $_GET['page']) {
							$GLOBAL['page'] = $_GET['page'];
						}
					}

					for ($i = 5 * ($GLOBAL['page'] - 1); $i < count($categories['ID']); $i++) {
						echo sprintf($template, $categories['ID'][$i], $categories['NAME'][$i]);
					}

					$GLOBALS['category_id'] = $categories['ID'][0];
					$GLOBALS['category_name'] = $categories['NAME'][0];
					if (isset($_GET['category'])) {
						for ($i = 0; $i < count($categories['ID']); $i++) {
							if ($categories['ID'][$i] == $_GET['category']) {
								$GLOBALS['category_id'] = $categories['ID'][$i];
								$GLOBALS['category_name'] = $categories['NAME'][$i];
								break;
							}
						}
					}

					$GLOBALS['total_pages'] = count($categories['ID']) / 5 + 1;
					?>
				</ul>
			</div>
			<div class="content" id="post_field">
				<div class="container" style="overflow: auto">
					<div class="container detail" id="Category_title">
						<h2><?php echo $GLOBALS['category_name'] ?></h2>
					</div>
					<?php
					require_once (dirname(__FILE__) . "/model/posts.php");
					$template = '<div class="container detail detail_frameSize writing_style" id="%s">
						<h1>%s</h1>
						<p>%s</p>
						</div>';
					
					$posts = PostsTable::select_by_category_id($GLOBALS['category_id']);
					for($i = ($page - 1) * NUM_POSTS_ONE_PAGE; $i < count($posts['ID']); $i++){
						echo sprintf($template, $posts['ID'][$i], $posts['TITLE'][$i], $posts['CONTENT'][$i]);
					}
					?>
					<form>
						<div class="container page_clicker">
							<ul class="pagination" >
							<?php 
								$start_num = ($GLOBAL['page'] - ($GLOBAL['page'] % 5)) + 1;
								$template = '<li><a href="index.php?category=%s&page=%s">%s</a></li>';
								
								if($start_num-1 > 0){
									echo sprintf($template, $GLOBALS['category_id'], $start_num-1, '&laquo;');
								}
								for($i = 0; $i < 5; $i++){
									if($start_num + $i > $GLOBALS['total_pages']) break;
									
									if($start_num + $i == $GLOBAL['page']){
										echo '<li><a>'.$GLOBAL['page'].'</a></li>';
									}else{
										echo sprintf($template, $GLOBALS['category_id'], $start_num + $i, $start_num + $i);
									}
								}
								if($start_num+5 < $GLOBALS['total_pages']){
									echo sprintf($template, $GLOBALS['category_id'], $start_num+5, '&raquo;');
								}
							?>
							</ul>
						</div>
						<div class="form-group">
							<label for="Post">Comment:</label>
							<textarea style="width: 70%" class="form-control" id="post_content" placeholder="Your post content"></textarea>
						</div>
						<input type="submit" class="btn btn-default" value="Submit" id="post_comment">
						<input type="submit" class="btn btn-default" value="Report" id="report">
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