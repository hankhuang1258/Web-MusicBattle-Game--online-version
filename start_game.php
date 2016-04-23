<?php session_start(); ?>
<?php
	include("mysql_music_connect.php");
	$room_id = $_SESSION['room_id'];
	$NO = $_SESSION['NO'];
	$sql = "SELECT * from room_table WHERE room_no = $room_id";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	mysql_query("UPDATE room_table SET isStart = 1 WHERE room_no = '$room_id'") or die(mysql_error());
	echo "<meta http-equiv=REFRESH CONTENT=0;url=music_index.php?NO=$NO&roomid=$room_id>";
?>