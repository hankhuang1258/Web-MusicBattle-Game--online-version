<?php session_start(); ?>
<html>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript">
		
		function goto($roomid){
			alert($roomid);
		}
		
		function add_people_num($roomid){
			alert("wtf");
		
		}
		
	</script>
	</head>
	<body align="center" style="font:bold 20px sans-serif">
		<div id="topbar">
		<p style="font:bold 30px sans-serif position: absolute color:black">MUSIC BATTLE 房間列表</p>
		</div>
		<?php
			include("mysql_music_connect.php");
			$sql="select * from music where NO='9'";
			$result = mysql_query($sql);
			$row = mysql_fetch_row($result); 
			
			$cur_name = $_SESSION['username'];
			mysql_query("UPDATE member_table SET at_which_room ='0' WHERE username = '$cur_name'") or die(mysql_error());
			
		?>
		
        
		<div class="name" style="right:0 top:0 position: absolute ">
			<table >
			<?php
				include("mysql_music_connect.php");
				$id = $_SESSION['username'];
				$sql1="select * from member_table where username='$id'";
				$result1 = mysql_query($sql1);
				$row1 = @mysql_fetch_row($result1); 		
			?>
			
				<?php
					include("mysql_music_connect.php");
					
					//此判斷為判定觀看此頁有沒有權限
					//說不定是路人或不相關的使用者
					//因此要給予排除
					
						if($_SESSION['username'] != null)
						{
								echo "Hi,    ".$_SESSION['username'];
								echo '</br>';
								/*echo '<input type="button" value="新增" class="button_type1"  onclick="window.location.href =\'music_register.php\'">&nbsp;&nbsp;';*/
								echo '<input type="button" value="修改" class="button_type1"  onclick="window.location.href =\'music_update.php\'">&nbsp;&nbsp;';
								echo '<input type="button" value="刪除" class="button_type1"  onclick="window.location.href =\'music_delete.php\'">&nbsp;&nbsp;';
								//echo '<a href="photo.php" target=\"_blank\">新增大頭貼</a>';
						}
						else
						{
								echo '您無權限觀看此頁面!';
								echo '<meta http-equiv=REFRESH CONTENT=2;url=music_login.php>';
						}
						echo '<input type="button" value="登出" class="button_type1"  onclick="window.location.href =\'music_logout.php\'">';
				?>
			</table>
		</form>
		</div>
		<?php


  			$records_per_page=4;
  				//get what page to show

  			if(isset($_GET["page"])){
    			$page=$_GET["page"];
  				}
  				else
    		$page=1;

		$sql = "SELECT * FROM room_table WHERE isActive =1 order by room_no DESC ";
		$result = mysql_query($sql) or die(mysql_error());
						$total_records=mysql_num_rows($result);
//count total page
$total_pages=ceil($total_records/$records_per_page);
//count this page the no.1 record
$started_record=$records_per_page*($page-1);
 
 

mysql_data_seek($result,$started_record);
 

echo"<table width='1000' align='center' cellspacing='5' class='newtable'>";
$bg[0]='transparent';
$bg[1]='transparent';
$bg[2]='transparent';
$bg[3]='transparent';
$bg[4]='transparent';

//show record
$j=1;
while($row=mysql_fetch_assoc($result) and $j<=$records_per_page){
	if($row['isGG']==0){
	$song_num = $row['music_num'];
	$roomid = $row['room_no'];
    echo"<tr>";
    echo"<td width='120' align='center' style='font:bold 40px sans-serif'></td>";
    echo"<td  color='black' bgcolor='".$bg[$j-1]."'>Room ID : ".$row['room_no']."<br>";
    echo"Music :".$row['music_name']."<br>";
    echo"People :".$row['cur_people_num']."<br>";
    echo"<a href =music_index.php?NO=$song_num&roomid=$roomid";
    echo">Join to Battle</a><hr></td></tr>";
    $j++;
}
}
echo"</table>";


echo"<p align='center'>";
if($page>1)
    echo"<a href='view_room.php?page=".($page-1)."'>last page&nbsp;</a>";

for($i=1;$i<=$total_pages;$i++){
    if($i==$page)
        echo"&nbsp;$i&nbsp;";
    else
        echo"&nbsp;<a href='view_room.php?page=$i'>$i&nbsp;</a>";
}
if($page<$total_pages){
    echo"<a href='view_room.php?page=".($page+1)."'>&nbsp;next page</a>";
}
echo"</p>";
		
		/***************************//*
		$rooms = "";
		echo "<table align=\"center\" border=\"1\" style=\"font:bold 20px sans-serif\">";
		if(mysql_num_rows($res)>0){
			while($row = mysql_fetch_assoc($res)){
				$roomid = $row['room_no'];
				$people_num = $row['cur_people_num'];
				$song_name = $row['music_name'];
				$song_num = $row['music_num'];
				if($row['isGG']==0){
					$rooms .= "<a href =music_index.php?NO=$song_num&roomid=$roomid >"."房號:".$roomid."<br>歌名:".$song_name."<br>房間人數:".$people_num."</a>"."<br><br>";		
				}		
			}
	
			echo "<tr>".$rooms."</tr>";
			echo "<br>";
		}
		else{
			echo' 還沒有對戰房間 ';
		}
		echo "</table>";
		*/
		echo "<tr><td colspan = '2'><input type = 'submit' class='button_type1' value = '建立對戰房間' onClick =\"window.location = 'create_room.php'\" /><hr />";
	
		?>
	
      
  </body>
</html>
