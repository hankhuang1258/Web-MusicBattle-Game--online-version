<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<link rel="stylesheet" type="text/css" href="styleend.css">
<body align="center">
<?php
session_start();

include("mysql_music_connect.php");
	$cur_name = $_SESSION['username'];
	$room_id =$_SESSION['room_id'];
	
	
	$sql = "UPDATE room_table SET isStart=0  WHERE room_no='$room_id'";
    mysql_query($sql);
	
	$sql2 = "UPDATE room_table SET isGG=1  WHERE room_no='$room_id'";
    mysql_query($sql2);
	
	
	$sq3 ="SELECT * FROM member_table WHERE at_which_room ='$room_id' ORDER BY cur_score DESC";

	$records_per_page=5;
  				//get what page to show

  			if(isset($_GET["page"])){
    			$page=$_GET["page"];
  				}
  				else
    		$page=1;

	$result = mysql_query($sq3);
	$total_records=mysql_num_rows($result);
	$total_pages=ceil($total_records/$records_per_page);
//count this page the no.1 record
$started_record=$records_per_page*($page-1);


echo"<h1>Final Score<br></h1>";

echo"<table width='1000' align='center' cellspacing='5' class='newtable'>";
$bg[0]='transparent';
$bg[1]='transparent';
$bg[2]='transparent';
$bg[3]='transparent';
$bg[4]='transparent';

//show record
$j=1;
$flag=1;
while($row=mysql_fetch_assoc($result) and $j<=$records_per_page){
	 
	 if($cur_name==$row['username'] && $flag==1){
	 	$message = "You Win!!";
        echo "<script type='text/javascript'>alert('$message');</script>";
        $flag=0;
	 }else if($flag==1){
	 	$message = "You Lose!!";
        echo "<script type='text/javascript'>alert('$message');</script>";
        $flag=0;
	 }
	 
    echo"<tr>";
    echo"<td width='120' align='center' style='font:bold 40px sans-serif'></td>";
    echo"<td  color='black' bgcolor='".$bg[$j-1]."'>Player ID : ".$row['username']."<br>";
    echo"Score :".$row['cur_score']."<br><hr>";
     
    echo"</td></tr>";
    $j++;
 
}
echo"</table>";


echo"<p align='center'>";
if($page>1)
    echo"<a href='end_game.php?page=".($page-1)."'>last page&nbsp;</a>";

for($i=1;$i<=$total_pages;$i++){
    if($i==$page)
        echo"&nbsp;$i&nbsp;";
    else
        echo"&nbsp;<a href='end_game.php?page=$i'>$i&nbsp;</a>";
}
if($page<$total_pages){
    echo"<a href='end_game.php?page=".($page+1)."'>&nbsp;next page</a>";
}
echo"</p>";

/*
	echo "最後成績:"."</br>";
	while($row= mysql_fetch_assoc($result)){
		echo $row['username']. "score ==== ".$row['cur_score']."</br>";
	}
	
*/


?>
<input type="button" value="Back to Room" style="align:center" class="button_type1"  onclick="window.location.href ='view_room.php'">
</body>
</html>

