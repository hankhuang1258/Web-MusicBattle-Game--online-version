<?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="stylelogin.css">
<?php
include("mysql_music_connect.php");
$id = $_POST['id'];
$pw = $_POST['pw'];
if($_SESSION['username'] != null)
{
        //刪除資料庫資料語法
		$sql = "SELECT * FROM member_table where username = '$id'";
        
		$result = mysql_query($sql);
		$row = @mysql_fetch_row($result);
        if($id != null && $pw != null && $row[1] == $id && $row[2] == $pw)
        {
			$sqld = "delete from member_table where username='$id'";
            if(mysql_query($sqld)){
				echo '刪除成功!';
				echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
			}
        }
        else
        {
                echo '刪除失敗!';
                echo '<meta http-equiv=REFRESH CONTENT=2;url=music_index.php>';
        }
}
else
{
        echo '您無權限觀看此頁面!';
        echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
}
?>