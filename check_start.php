<?php
	include("mysql_music_connect.php");
	$room_id = $_POST['room_id'];
	if(isset($_POST['attr'])&&$_POST['attr']==1){
		$sql_temp = "SELECT * from room_table WHERE room_no = $room_id";
		$result_temp = mysql_query($sql_temp);
		$row_temp = mysql_fetch_array($result_temp);
		echo $row_temp['isStart'];
	}
?>