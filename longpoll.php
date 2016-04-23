<?php
session_start();
include("./pdoInc.php");
 
if( isset($_POST['lastMsgTime']) &&
    ""!=$_POST['lastMsgTime'] &&
    0==(int)$_POST['lastMsgTime'] ||
    !isset($_SESSION['lastMsgTime_longpoll'])){
    $lastMsgTime = 0;
}
else {
    $lastMsgTime = $_SESSION['lastMsgTime_longpoll'];
}
$rid = $_SESSION['room_id']; 
 
$sth = $dbh->prepare("SELECT * FROM member_table WHERE at_which_room ='$rid' ORDER BY cur_score DESC");
session_write_close();
ini_set('session.use_cookies',false);
session_cache_limiter(false);
error_reporting(0);
while(true){
    session_start();
    $sth->execute(array($lastMsgTime));
    $flag = 0;
    if($sth->rowCount()>0){
        $flag = 1;
        while($row=$sth->fetch(PDO::FETCH_ASSOC)){
            echo $row["username"]."  ".$row["cur_score"]."\n";
            $_SESSION['lastMsgTime_longpoll'] = $row["time"];
        }
        $lastMsgTime = $_SESSION['lastMsgTime_longpoll'];
    }
    $sth->closeCursor();
    session_write_close();
    if(1==$flag){
        break;
    }
    sleep(1);
}
?>