<?php
session_start();
$q = $_GET['q'];
include_once 'database.php';
$session_uname= $_SESSION['session_username'];
if ($q=='online' OR $q=='offline') {
  $sql = "UPDATE dianomeas SET katastasi='$q' WHERE username='$session_uname' ";
  $mysql_con->query ($sql);
}elseif ($q=='busy') {
  $sql2= "UPDATE paragelia SET seenbyd='yes' WHERE idd='$session_uname' AND seenbyd='no' " ;
  $mysql_con->query ($sql2);
  $sql = "UPDATE dianomeas SET katastasi='$q' WHERE username='$session_uname' ";
  $mysql_con->query ($sql);

}elseif ($q == 'offout'){
  $sql = "UPDATE dianomeas SET katastasi='offline' WHERE username='$session_uname' ";
  $mysql_con->query ($sql);
  $sql3= "UPDATE paragelia SET delivered='yes' WHERE idd='$session_uname' AND delivered='no' " ;
  $mysql_con->query ($sql3);

}else {
  $sql3= "UPDATE paragelia SET delivered='yes' WHERE idd='$session_uname' AND delivered='no' " ;
  $mysql_con->query ($sql3);
  $sql = "UPDATE dianomeas SET katastasi='online' WHERE username='$session_uname' ";
  $mysql_con->query ($sql);
}
 ?>
