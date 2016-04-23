<?php
	
	//$db_server = "118.166.85.234";
	$db_server = "localhost";
	$db_name = "kenny0429_music";
	$db_user = "kenny0429";
	
	$db_passwd = "960288921";
	
	/*
	$db_user = "user3";
	
	$db_passwd = "jjjj";*/
	
	if(!@mysql_connect($db_server, $db_user, $db_passwd))
			die("無法對資料庫連線");
	mysql_query("set character set 'utf8'");
	
	mysql_query("SET NAMES utf8");

	if(!@mysql_select_db($db_name))
			die("無法使用資料庫");
?> 