<?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="stylelogin.css">
<?php
	include("mysql_music_connect.php");

	$id = $_POST['id'];
	$pw = $_POST['pw'];
	$id = preg_replace("/[^A-Za-z0-9]/","",$id);
    $pw = preg_replace("/[^A-Za-z0-9]/","",$pw);
	$pw2 = $_POST['pw2'];
	/*$other = $_POST['other'];*/
	
	$sql = "SELECT * FROM member_table where username = '$id'";
	$result = mysql_query($sql);
	$row = @mysql_fetch_row($result);
	if ( $row[1] == $id)
	{
		echo '帳號重複';
		echo '<meta http-equiv=REFRESH CONTENT=2;url=music_index.php>';	
	}
		 
	else if($id != null && $pw != null && $pw2 != null && $pw == $pw2)
	{
			
			$sql = "insert into member_table (username, password) values ('$id', '$pw')";
			if(mysql_query($sql))
			{
					echo '新增成功!請重新登入!';
					echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
			}
			else
			{
					echo '新增失敗!';
					echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
			}
	}
	else
	{
			echo '註冊失敗!';
			echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
	}
?>