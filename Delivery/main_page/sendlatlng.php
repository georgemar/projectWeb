<?php
session_start();
$lat=$_POST['latitude'];
$long=$_POST['longitude'];


include_once 'database.php';
$session_uname= $_SESSION['session_username'];
$sql = "UPDATE dianomeas SET longtitude='$long',langtitude='$lat' WHERE username='$session_uname' ";
$mysql_con->query ($sql);



 ?>
