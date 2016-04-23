<?php session_start(); ?>
<html>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<?php
			include("mysql_music_connect.php");
			$sql="select * from music where NO='9'";
			$result = mysql_query($sql);
			$row = @mysql_fetch_row($result); 
			$getf=0;
			/*yuan: 判斷有沒有GET到值*/
			if(@$_GET['NO']!=null){
				$getf=1;
				$NO = $_GET['NO'];
				$sqlf = "SELECT * from music WHERE NO = $NO";
				$resultf = mysql_query($sqlf);
				$rowf = mysql_fetch_array($resultf);
				$name = $rowf['name'];
				$cur_name = $_SESSION['username'];
				
				
				mysql_query("INSERT INTO room_table (room_no, music_num, cur_people_num, isActive, music_name,creator_id) VALUES ('','$NO','1','1','$name', '$cur_name')")or die(mysql_error());
				$roomid = mysql_insert_id();
				/*echo "<a href=music_index.php?NO=$NO&room_no=$roomid>建立房間<br></a>";*/
			}
		?>
		
        <div id="topbar">
			
			<input type="button" value="上傳音樂" class="button_type1"  onclick="window.location.href ='music.php'">
		
			<input type="button" value="選擇音樂" class="button_type1"  onclick="window.location.href ='music_select.php'">
			<?php if($getf==0)
					echo "請選擇歌曲";
				  else
					echo $rowf['name'];?>
            <button  id="fileInput" class="button_type1" onclick="window.location.href ='view_room.php'">建立房間</button>
			
			
        </div>
		<div class="name" align="center">
			<table class="table" align="center">
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
								/*echo '<input type="button" value="新增" class="button"  onclick="window.location.href =\'music_register.php\'">&nbsp;&nbsp;';*/
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