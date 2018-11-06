<?php
  session_start();
  include_once 'database.php';
  $session_uname= $_SESSION['session_username'];
  $sql = "UPDATE dianomeas SET katastasi='offline',longtitude='0',langtitude='0' WHERE username='$session_uname' ";
  $mysql_con->query ($sql);
  session_unset();
  header("Location: /Delivery/login/login.php")
?>
