<?php
require_once (dirname(dirname(dirname(__FILE__))) . "/model/admins.php");
/*
 * check cookie
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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Manage Users</title>

        <!-- Bootstrap -->

        <link href="../../css/bootstrap.min.css" rel="stylesheet">
        <link href="../../css/simple-sidebar.css" rel="stylesheet">
        <style>
			.user_field li {
				display: inline
			}
			.content {
				min-height: 670px;
				margin-top: 5px;
			}
			.detail {
				margin-left: 10%;
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
				width: 100%;
				border: 1px solid;
				background-image: url('https://wp-themes.com/wp-content/themes/gule/images/pattern.png');
				background-repeat: repeat;
				background-position: top left;
				background-attachment: scroll;
				border-style: solid;
			}
			#Category_title {
				width: 78%;
				text-align: center;
				color: #01DF3A;
				height: 10%
			}
			.page_clicker {
				margin-left: 34%
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
			.writing_style_ForTitle {
				font-size: 110%;
			}
			.writing_style_Forinput {
				font-size: 100%;
				font-weight: normal
			}
			.selected {
				background-color: rgb(206,255,104);
				color: black;
			}
			.big-title{
              font-size: 20px;
            }
			.marginleft {
				margin-left: 2%;
			}
			.margintopbypx {
				margin-top: 8px
			}
			#margintop {
				margin-top: 59px;
			}
			.mypanel {
				width: 84%;
				margin-left: 16%;
			}
			#create_post {
				margin-left: 16%;
				width: 84;
			}
			.center {
				text-align: center;
			}
			#post_field {
				width: 100%;
				border: 1px solid;
				background-color: #e4e4e4;
				border-style: solid;
				margin-left: 0;
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
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="../../index.php">Society Community</a>
				</div>
				<div class="collapse navbar-collapse user_field">
					<ul class="nav navbar-nav navbar-right" >
						<div class="navbar-header navbar-brand" style="color:green">
							Welcome
						</div>
						<li>
							<a href="../jump.php"><?php echo $GLOBALS['user_name'] ?></a>
						</li>
						<form class="navbar-form navbar-left" role="logout">
							<a href="../../jump/logout.php">
							<button type="button" class="btn btn-danger">
								Logout
							</button> </a>
						</form>
					</ul>
				</div>
			</div>
		</div>

        <div class="container-fluid" style="overflow-x: hidden;overflow-y: auto;margin-top: ">
            <div class="content" id="category_field" style="margin-top:5%;margin-right:2%;overflow-x: hidden;overflow-y: auto;" >
                <ul class="sidebar-nav" style="width: 90%" >
                  <label>Admin Settings</label>
									<li>
											<a class="sidebar-brand" href="admin_profile.php">Profile</a>
									</li>
                  					<li>
										<a class="sidebar-brand" href="change_password.php">Change Password</a>
									</li>
									<li class="selected">
										<a class="sidebar-brand" href="manage_users.php">Manage Users</a>
									</li>
									<li>
										<a class="sidebar-brand" href="add_admin.php">Add Admin</a>
									</li>
									<li>
										<a class="sidebar-brand" href="add_category.php">Manage Category</a>
									</li>
                </ul>
            </div>
            <div class="panel panel-primary mypanel " id="margintop">
              <div class="panel-heading center">
                    <h3 class="panel-title big-title">Manage Users</h3>
              </div>
              <div class="panel-body Post_Info"id="post_field">
                    <!--Posts-->
                    <?php
                    require_once (dirname(dirname(dirname(__FILE__))) . "/model/banned_users.php");
                    $template = '
                    
                    <div class="panel panel-success">
                      <div class="panel-heading">
                            <div class="btn-group pull-left">
                            	<a href="../../jump/unban_user.php?user_id=%s">
	                              <button type="button" class="btn btn-default" name="unban1">
	                                    UNBAN
	                              </button>
                              	</a>
                            </div>
	                        <label class="marginleft margintopbypx writing_style_ForTitle" >User ID:</label>
	                        <label name="user_id" class="writing_style_Forinput">%s</label>
	                        <label class="marginleft margintopbypx writing_style_ForTitle" >User Name:</label>
	                        <label name="user_name" class="writing_style_Forinput">%s</label>
                        <div class="clearfix"></div>
                      </div>
                    </div>';
					$banned_users = BannedUsersTable::select_all();

					for($i = 0; $i<count($banned_users['ID']);$i++){
						echo sprintf($template, $banned_users['ID'][$i], $banned_users['ID'][$i], $banned_users['NAME'][$i]);
					}
                    ?>
                    <!--Posts-->
              </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
