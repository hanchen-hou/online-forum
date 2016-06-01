<?php
	setcookie('id', '', time() - 3600, '/');
    setcookie('type', '', time() - 3600, '/');
	if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){
		$GLOBALS['referer'] = $_SERVER['HTTP_REFERER'];
	}else{
		$GLOBALS['referer'] = 'index.php';
	}
?>

<!DOCTYPE HTML>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Logout</title>
    <style type="text/css">
        body{
            background-repeat: no-repeat;
            color: #000;
            font:9pt/200% Verdana;
        }
        a{text-decoration: none;color:#659B28}
        a:hover{text-decoration: underline;}
    </style>
<style>@-moz-keyframes nodeInserted{from{opacity:0.99;}to{opacity:1;}}@-webkit-keyframes nodeInserted{from{opacity:0.99;}to{opacity:1;}}@-o-keyframes nodeInserted{from{opacity:0.99;}to{opacity:1;}}@keyframes nodeInserted{from{opacity:0.99;}to{opacity:1;}}embed,object{animation-duration:.001s;-ms-animation-duration:.001s;-moz-animation-duration:.001s;-webkit-animation-duration:.001s;-o-animation-duration:.001s;animation-name:nodeInserted;-ms-animation-name:nodeInserted;-moz-animation-name:nodeInserted;-webkit-animation-name:nodeInserted;-o-animation-name:nodeInserted;}</style></head>
<body>
<center>
    <div style="padding:30px;padding:36px 80px;border:1px solid #a9a9a9;background:#ffffff ; text-align:center; margin:20% auto; background-repeat: no-repeat; width:55%;">
        Logout Success
        </br>
        <a href=<?php echo $GLOBALS['referer'] ?>>Click Here to go back</a>
        <script>
            var pgo = 0;
            function JumpUrl(){
                if(pgo == 0){
                    location = '<?php echo $GLOBALS['referer'] ?>';
                    pgo = 1;
                }
            }
            setTimeout('JumpUrl()',2000);
        </script>
    </div>
</center>
</body>
</html>