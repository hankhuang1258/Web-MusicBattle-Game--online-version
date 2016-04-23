<?php session_start(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	include("mysql_music_connect.php");
	echo '<a href="music_logout.php">登出</a>  <br><br>';
	if($_SESSION['username'] != null)
	{
			echo '<a href="music_register.php">新增</a>    ';
			echo '<a href="music_update.php">修改</a>    ';
			echo '<a href="music_delete.php">刪除</a>  <br><br>';
		
			/*//將資料庫裡的所有會員資料顯示在畫面上
			$sql = "SELECT * FROM member_table";
			$result = mysql_query($sql);
			while($row = mysql_fetch_row($result))
			{
					 echo "$row[0] - 名字(帳號)：$row[1], " . 
					 "電話：$row[3]<br>";
			}*/
	}
	else
	{
			echo '您無權限觀看此頁面!';
			echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
	}
?>