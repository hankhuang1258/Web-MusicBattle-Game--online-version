<?php session_start(); ?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="stylelogin.css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body style="font:bold 25px sans-serif" >
	</br></br></br>
	<?php
	include("mysql_music_connect.php");

	if($_SESSION['username'] != null)
	{
			//將$_SESSION['username']丟給$id
			//這樣在下SQL語法時才可以給搜尋的值
			$id = $_SESSION['username'];
			
			$sql = "SELECT * FROM member_table where username='$id'";
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result);
		
			echo "<br><br><br><form align=\"center\" name=\"form\" method=\"post\" action=\"music_update_finish.php\">";
			echo "帳號：<input type=\"text\"  class=\"text_type\"  name=\"id\" value=\"$row[1]\" /><br>";
			echo "密碼：<input type=\"password\" class=\"password_type\" name=\"pw\" value=\"$row[2]\" /> <br>";
			echo "確認密碼：<input type=\"password\" class=\"password_type\" name=\"pw2\" value=\"$row[2]\" /> <br>";
			/*echo "其他：<input type=\"text\" class=\"text_type\" name=\"other\" value=\"$row[3]\" /> <br>";*/
			
		   
			echo "<br><input type=\"submit\" class=\"button_type1\"name=\"button\" value=\"確定\" />";
			echo "</form>";
	}
	else
	{
			echo '您無權限觀看此頁面!';
			echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
	}
	?>
</body>
</html>