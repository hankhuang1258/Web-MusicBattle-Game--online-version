<?php
include("mysql_music_connect.php");
if(isset($_POST['post_score'])){
	$msg = $_POST['post_score'];
	$room = $_POST['post_room'];
	$name = $_POST['post_name'];
	
	
	
	$sql = "UPDATE member_table SET cur_score='$msg'  WHERE username='$name'";
    mysql_query($sql);
	
	$sq2 = "UPDATE member_table SET time=now()  WHERE username='$name'";
    mysql_query($sq2);
	

}

?>


