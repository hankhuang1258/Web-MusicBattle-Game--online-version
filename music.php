<?php session_start(); ?>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<head>
    <link rel="stylesheet" type="text/css" href="stylemusic.css">
	</head>
<?php
include("mysql_music_connect.php");
	//上傳檔案類型清單
	$uptypes=array(	
		'audio/mp3',
		'audio/wav',
        'audio/x-ms-wma',
		'application/x-mplayer2',
	);
	$max_file_size=3617050000; //上傳檔案大小限制, 單位BYTE
	$destination_folder="uploadFile/"; //上傳檔路徑
?>
<html>
	<head>
	<title>檔案上傳程式</title>
	<style type="text/css">
	<!--
	body
	{
	font-size: 9pt;
	}
	input
	{
	background-color: transparent;
	border: 1px inset #CCCCCC;
	}
	-->
	</style>
</head>


<body font size="40" align="center" bgcolor="#000000">
	<div id="muisc_upload">
		<form font size="50" font color="black" align= "center" enctype="multipart/form-data" method="post" name="upform" >
			<br><br>上傳檔案:
			<input name="upfile" class="text_type" type="file">
			<input type="submit"  class="button_type1" value="上傳"><br>
			允許上傳的檔案類型為:MP3,WMA,WAV
			大小上限:10MB
		</form>
	</div>

<?php

//$id = $_POST['id'];


	
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		if (!is_uploaded_file($_FILES["upfile"]['tmp_name']))
		//是否存在檔案
		{
			echo "您還沒有選擇檔!";
			exit;
		}


	$file = $_FILES["upfile"];
	if($max_file_size < $file["size"])
	//檢查檔案大小
	{
		echo "您選擇的檔太大了!";
		exit;
	}


	if(!in_array($file["type"], $uptypes))
	//檢查檔案類型
	{
		echo "檔案類型不符!".$file["type"];
		exit;
	}


	if(!file_exists($destination_folder))
	{
		mkdir($destination_folder);
	}


	$filename=$file["tmp_name"];
	$image_size = getimagesize($filename);
	$pinfo=pathinfo($file["name"]);
	$ftype=$pinfo['extension'];
	$destination = $destination_folder.time().".".$ftype;
	if (file_exists($destination) && $overwrite != true)
	{
		echo "同名檔已經存在了";
		exit;
	}


	if(!move_uploaded_file ($filename, $destination))
	{
		echo "移動檔出錯";
		exit;
	}


	$pinfo=pathinfo($destination);
	$filename1=$pinfo['basename'];
	echo $filename1;
	echo " <font color=red>已經成功上傳</font><br>完整位址: <font color=blue>HTTP://localhost/".$destination_folder.$filename1."</font><br>";
	echo "<br> 大小:".$file["size"]." bytes";
	echo '<br>';
	//將資料插入到資料庫中
	//$dizhi = "$destination_folder"."$fname";
	$address = "$destination_folder"."$filename1";
	$name = $file['name'];
	$sql="INSERT music (address,name,filename1) 
		VALUES ('$address','$name','$filename1')"; 
	
	mysql_query($sql);
	echo "資料插入成功";
	echo '<meta http-equiv=REFRESH CONTENT=1;url=create_room.php>';
	}
	
?>
</body>