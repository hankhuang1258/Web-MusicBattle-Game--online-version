<?php session_start(); ?>
<link rel="stylesheet" type="text/css" href="stylelogin.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

	include("mysql_music_connect.php");
	$id = $_POST['id'];
	$pw = $_POST['pw'];

	$sql = "SELECT * FROM member_table where username = '$id'";
	$result = mysql_query($sql);
	$row = @mysql_fetch_row($result);

	if($id != null && $pw != null && $row[1] == $id && $row[2] == $pw)
	{
			//將帳號寫入session，方便驗證使用者身份
			$_SESSION['username'] = $id;
			echo '登入成功!';
			/*進入房間列表*/
			echo '<meta http-equiv=REFRESH CONTENT=1;url=view_room.php>';
			//echo '<meta http-equiv=REFRESH CONTENT=1;url=music_index.php>';
	}
	else
	{
			echo '登入失敗!';
			echo '<meta http-equiv=REFRESH CONTENT=1;url=music_login.php>';
	}
?>