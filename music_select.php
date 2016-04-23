<?php
	include("mysql_music_connect.php");
	$sql = "SELECT * from music order by NO DESC";
	$result = mysql_query($sql);
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
		<body style="font:bold 25px sans-serif"  align="center" >
			 
			Total music: <?php echo mysql_num_rows($result);?>
			<table align="center" border="1" class="newtable" style="font:bold 20px sans-serif">
				<tr>
					<th>No.</th>
					
					<th>Music</th>
				</tr>
				<?php
				while($row = mysql_fetch_array($result)){
					echo "<tr><td>".$row['0']."</td>";
					$NO=$row['0'];
					//echo "<td>".$row['2']."</td></tr>";
					echo "<td><a href=create_room.php?NO=$NO>".$row['2']."</a>"; 
				}
				?>
			</table>
		 
		</body>
</html>

