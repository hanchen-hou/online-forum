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
            .marginleft {
                margin-left: 2%;
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

                <div class="navbar-header col-md-9">
                    <span class="navbar-brand glyphicon glyphicon glyphicon-align-justify" aria-hidden="true"></span>
                    <label class="navbar-brand">Society Community</label>
                </div>
                <div class="navbar-header navbar-brand" style="color:green">
                    Welcome
                </div>
                <div class="navbar-header navbar-brand">
                    User
                </div>
                <div class="navbar-header navbar-brand">
                    <a href=" #">Logout</a>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="overflow-x: hidden;overflow-y:auto">
            <div class="content" id="category_field" style="margin-top:5%;margin-right:2%;overflow-x: hidden;overflow-y:auto" >
                <ul class="sidebar-nav" style="width: 90%" >
                    <label>Admin setting</label>
                    <li>
                        <a class="sidebar-brand" href="Admin_changePassword.html">Change password</a>
                    </li>
                    <li>
                        <a class="sidebar-brand" href="Admin_report.html">Report</a>
                    </li>
                    <li >
                        <a class="sidebar-brand" href="#">Manage User</a>
                    </li>
                    <li >
                        <a class="sidebar-brand" href="#">Manage Posts</a>
                    </li>
                    <li >
                        <a class="sidebar-brand" href="#">Add Category</a>
                    </li>
                    <li class="selected">
                        <a class="sidebar-brand" href="#">Add Admin</a>
                    </li>
                </ul>
            </div>
            <div class="panel panel-primary mypanel " id="margintop">
                <div class="panel-heading center">
                    <h3 class="panel-title">Add Admin</h3>
                </div>
                <div class="panel-body Post_Info"id="post_field">

                    <!--Posts-->
                    <div class="panel panel-primary marginleft" >
                        <div class="panel-body">
                            <form method="post" action="#">
                                <div class="form-group">
                                    <label for="user name">User Name </label>
                                    <input name="user_name" type="text" class="form-control" id="user_name" placeholder="user name">
                                </div>
                                <div class="form-group">
                                    <label for="user name">Email address </label>
                                    <input name="email" type="email" class="form-control" id="user_name" placeholder="email">
                                </div>
                                <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input name="new_password" type="password" class="form-control" id="new_password" placeholder="New Password">
                                 </div>
                                 <div class="form-group">
                                <label for="new_password">Confirm New Password</label>
                                <input name="confirm_password" type="password" class="form-control" id="confirm_password" placeholder="Confirm Password">
                                 </div>
                                 <div class="form-group">
                                <input type="submit" class="btn primary " id="submit" value="submit" style="margin-top:10px">
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>