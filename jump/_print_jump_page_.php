<?php
function print_jump_page($result, $jump_link){
	echo <<<EOD
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
				$result
				</br>
				<a href="$jump_link">Click Here to go back</a>
				<script>
					var pgo = 0;
					function jump(){
					if(pgo == 0){
					location = '$jump_link';
						pgo = 1;
						}
					}
					setTimeout('jump()',2000);
				</script>
			</div>
		</center>
	</body>
</html>
EOD;
}
?>