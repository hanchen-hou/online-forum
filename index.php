<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Society Community</title>

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

			.post-title {
				font-size: 22px;
			}

			.big-title {
				font-size: 28px;
			}
			.buttonmargin {
				margin-left: 6px;
				margin-top: -4px;
			}
			.dateTimeANDUser {
				clear: both;
				margin-top: 2px;
			}
			.writing_style_ForTitle {
				font-size: 110%;
			}
			.writing_style_ForPostTitle {
				font-size: 130%;
			}
			.writing_style_Forinput {
				font-size: 100%;
				font-weight: normal
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
      				<a class="navbar-brand" href="./index.php"><b>Society Community</b></a>
    			</div>

                <div class="collapse navbar-collapse user_field">
                    <ul class="nav navbar-nav navbar-right" >
                        <?php
						require_once (dirname(__FILE__) . "/model/admins.php");
						require_once (dirname(__FILE__) . "/model/users.php");

						$login_form = '
									<form class="navbar-form navbar-left" method="post" action="jump/login.php">
					                    <div class="form-group">
					                        <input type="text" class="form-control" name="user_name" placeholder="Username">
					                    </div>
					                    <div class="form-group">
					                        <input type="password" class="form-control" name="password" placeholder="Password">
					                    </div>
					                    <button type="submit" class="btn btn-success">Sign In</button>
					                    <a href="signup.php"><button type="button" class="btn btn-default">Register</button></a>
					                </form>
									';

						$after_login = '<div class="navbar-header navbar-brand" style="color:green">Welcome</div>
										<li><a href="./account/jump.php">%s</a></li>
										<form class="navbar-form navbar-left" role="logout">
											<a href="jump/logout.php"><button type="button" class="btn btn-danger">Logout</button></a>
										</form>';
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
								}else{
									//exit('Fake Administrator Account');
								}
							} else if ($_COOKIE['type'] == 'user') {
								$user = UsersTable::select_by_id($_COOKIE['id']);
								if (count($user['ID']) > 0) {
									$sub_page = sprintf($after_login, $user['NAME']);
								}else{
									//exit('You are not a USER');
								}
							}
						}
						echo $sub_page;
						?>
                </div>
            </div>
        </div>

        <div class="container-fluid">

            <div class="content" id="category_field" style="margin-top:5%;margin-right:2%" style="overflow-x: hidden;overflow-y: scroll;" >
                <ul class="sidebar-nav" style="width:90%" >
                    <label>Category</label>
                    <?php
					/*
					 *  Category Part
					 */

					require_once (dirname(__FILE__) . "/model/categories.php");
					$template = '<a class="sidebar-brand" href="index.php?category_id=%s&page=1">%s';

					$categories = CategoriesTable::select_all();
					$hottest_categories = CategoriesTable::get_hottest_category();

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
							echo '<li class="selected">';
						} else {
							echo '<li>';
						}
						echo sprintf($template, $categories['ID'][$i], $categories['NAME'][$i]);
						for ($j = 0; $j < count($hottest_categories['CATEGORY_ID']); $j++) {
							if ($hottest_categories['CATEGORY_ID'][$j] == $categories['ID'][$i]) {
								echo '<span class="glyphicon glyphicon-fire" style="color:red"></span>';
							}
						}
						echo '</a></li>';
					}
					?>
                </ul>
            </div>
            <div class="panel panel-primary mypanel " id="margintop">
              <div class="panel-heading center">
                <h3 class="panel-title big-title"><b><?php echo $GLOBALS['category_name'] ?></b></h3>
              </div>
              <div class="panel-body Post_Info"id="post_field">
              	<!--Posts-->
				<?php

				/*
				 *  Posts Part
				 */

				require_once (dirname(__FILE__) . "/model/posts.php");
				$template = 
				'<div class="panel panel-success">
				   <div class="panel-heading">
				      <div class-"row">
				         <div class="col-sm-3" style="margin-left:-14px">
				            <label class="marginleft margintopbypx writing_style_ForTitle" >Date:</label>
				            <label class="marginleft margintopbypx writing_style_Forinput" >%s</label>					   		
				         </div>
				         <div class="col-md-6">
				            <center>
				               <a href="./post.php?category_id=%s&post_id=%s&page=1">
				               		<label class="marginleft margintopbypx writing_style_ForTitle" >%s</label>
				               </a>
				            </center>
				         </div>
				         <div class= "pull-right">
				         </div>
				      </div>
				      <div class="dateTimeANDUser">  
				         <label class="marginleft margintopbypx writing_style_ForTitle" >Username: </label>                                
				         <label name="user_name" class="writing_style_Forinput">%s</label>
				      </div>
				      <div class="clearfix"></div>
				   </div>
				   <div class="panel-body">
				      %s
				   </div>
				</div>';
				
				$template_for_admin_to_ban =
				'<div class="panel panel-success">
				   <div class="panel-heading">
				      <div class-"row">
				         <div class="col-sm-3" style="margin-left:-14px">
				            <label class="marginleft margintopbypx writing_style_ForTitle" >Date:</label>
				            <label class="marginleft margintopbypx writing_style_Forinput" >%s</label>					   		
				         </div>
				         <div class="col-md-6">
				            <center>
				               	<a href="./post.php?category_id=%s&post_id=%s&page=1">
				               		<label class="marginleft margintopbypx writing_style_ForTitle" >%s</label>
				               	</a>
				            </center>
				         </div>
				         <div class= "pull-right">
				            <a href="./jump/delete_msgs.php?msgs_id=%s">
				            	<button type="button" class="btn btn-default pull-right">
				            		<span class="glyphicon glyphicon-trash"></span>
				            	</button>
				            </a>
				         </div>
				      </div>
				      <div class="dateTimeANDUser">  
				         <label class="marginleft margintopbypx writing_style_ForTitle" >Username: </label>                                
				         <label name="user_name" class="writing_style_Forinput">%s</label>                               
				         <a href="./jump/ban_user.php?user_id=%s">
				         	<button type="button" class="btn btn-danger btn-xs buttonmargin">Ban</button>
				         </a>
				      </div>
				      <div class="clearfix"></div>
				   </div>
				   <div class="panel-body">
				      %s
				   </div>
				</div>';
				
				$template_for_admin_to_unban =
				'<div class="panel panel-success">
				   <div class="panel-heading">
				      <div class-"row">
				         <div class="col-sm-3" style="margin-left:-14px">
				            <label class="marginleft margintopbypx writing_style_ForTitle" >Date:</label>
				            <label class="marginleft margintopbypx writing_style_Forinput" >%s</label>					   		
				         </div>
				         <div class="col-md-6">
				            <center>
				               	<a href="./post.php?category_id=%s&post_id=%s&page=1">
				               		<label class="marginleft margintopbypx writing_style_ForTitle" >%s</label>
				               	</a>
				            </center>
				         </div>
				         <div class= "pull-right">
				            <a href="./jump/delete_msgs.php?msgs_id=%s">
				            	<button type="button" class="btn btn-default pull-right">
				            		<span class="glyphicon glyphicon-trash"></span>
				            	</button>
				            </a>
				         </div>
				      </div>
				      <div class="dateTimeANDUser">  
				         <label class="marginleft margintopbypx writing_style_ForTitle" >Username: </label>                                
				         <label name="user_name" class="writing_style_Forinput">%s</label>                               
				         <a href="./jump/unban_user.php?user_id=%s">
				         	<button type="button" class="btn btn-danger btn-xs buttonmargin">Unban</button>
				         </a>
				      </div>
				      <div class="clearfix"></div>
				   </div>
				   <div class="panel-body">
				      %s
				   </div>
				</div>';

				$posts = PostsTable::select_by_category_id($GLOBALS['category_id']);

				$GLOBALS['total_pages'] = ceil(count($posts['ID']) / POSTS_NUM_ONE_PAGE);

				$GLOBALS['page'] = 1;
				if (isset($_GET['page']) && is_numeric($_GET['page'])) {
					if ($GLOBALS['total_pages'] >= $_GET['page']) {
						$GLOBALS['page'] = $_GET['page'];
					}
				}
				$offset = ($GLOBALS['page'] - 1) * POSTS_NUM_ONE_PAGE;
				
				if ($_COOKIE['type'] == 'admin') {
					for ($i = $offset, $j = 0; $i < count($posts['ID']) && $j < 10; $i++, $j++) {
						if($posts['USER_STATUS'][$i] == 1){
							echo sprintf($template_for_admin_to_unban, 
										$posts['DATETIME'][$i], 
										$GLOBALS['category_id'], 
										$posts['ID'][$i],
										$posts['TITLE'][$i],
										$posts['ID'][$i],
										$posts['USER_NAME'][$i],
										$posts['USER_ID'][$i], 
										$posts['CONTENT'][$i]);
						}else{
							echo sprintf($template_for_admin_to_ban, 
										$posts['DATETIME'][$i], 
										$GLOBALS['category_id'], 
										$posts['ID'][$i],
										$posts['TITLE'][$i],
										$posts['ID'][$i],
										$posts['USER_NAME'][$i],
										$posts['USER_ID'][$i], 
										$posts['CONTENT'][$i]);
						}
					}
				}else{
					for ($i = $offset, $j = 0; $i < count($posts['ID']) && $j < 10; $i++, $j++) {
						echo sprintf($template, 
									$posts['DATETIME'][$i], 
									$GLOBALS['category_id'], 
									$posts['ID'][$i], 
									$posts['TITLE'][$i], 
									$posts['USER_NAME'][$i], 
									$posts['CONTENT'][$i]);
					}
				}
				?>
                    <!--Posts End-->

                    <!--Page Numbers-->
                    <div id="Pages" class="text-center">
                    <ul class="pagination">
                      <?php

					/*
					 * Page Number
					 */

					$start_num = ($GLOBALS['page'] - ($GLOBALS['page'] % 5)) + 1;
					$template = '<li><a href="index.php?category_id=%s&page=%s">%s</a></li>';

					if ($start_num - 1 > 0) {
						echo sprintf($template, $GLOBALS['category_id'], $start_num - 1, '&laquo;');
					}
					for ($i = 0; $i < 5; $i++) {
						if ($start_num + $i > $GLOBALS['total_pages'])
							break;

						if ($start_num + $i == $GLOBALS['page']) {
							echo '<li><a>' . $GLOBALS['page'] . '</a></li>';
						} else {
							echo sprintf($template, $GLOBALS['category_id'], $start_num + $i, $start_num + $i);
						}
					}
					if ($start_num + 5 < $GLOBALS['total_pages']) {
						echo sprintf($template, $GLOBALS['category_id'], $start_num + 5, '&raquo;');
					}
					?>
                    </ul>
                  </div>
                    <!--End of Page Numbers-->
              </div>
            </div>
            <!--End of post area-->

            <!--Create new post-->
            <div class="panel panel-primary" id="create_post">
                <div class="panel-body">
                    <form class="form" method="post" action="jump/make_post.php">
                        <div class="form-group">
                            <label>Title:</label>
                            <input class="form-control" type="title" name="title" />
                        </div>
                        <div class="form-group">
                            <label>Content:</label>
                            <textarea class="form-control" name="content" ></textarea>
                        </div>
                        <input style="display: none" name="category_id" value="<?php echo $GLOBALS['category_id'] ?>"/>
                       <center><input type="submit" class="btn btn-default" value="Post" id="submit_content" style="" /></center>
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
