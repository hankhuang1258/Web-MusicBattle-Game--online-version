<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="stylelogin.css">
<?php
//將session清空
	unset($_SESSION['username']);
	echo '登出中......';
	echo '<meta http-equiv=REFRESH CONTENT=1;url=music_login.php>';
?>