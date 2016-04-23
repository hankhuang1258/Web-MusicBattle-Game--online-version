<?php session_start(); ?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="stylelogin.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body style="font:bold 25px sans-serif" >
		</br></br></br>
	<?php
	if($_SESSION['username'] != null)
		{
				echo "<br><br><form align=\"center\" name=\"form\" method=\"post\" action=\"music_delete_finish.php\">";
				echo "刪除的帳號：<input type=\"text\"  class=\"text_type\" name=\"id\" /> <br><br>";
				echo "此帳號密碼：<input type=\"password\" class=\"password_type\"  name=\"pw\" /> <br>";
				echo "<br><input type=\"submit\" class=\"button_type1\"name=\"button\" value=\"刪除\" />";
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