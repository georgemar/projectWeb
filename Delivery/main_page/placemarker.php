<?php
  session_start();
  include_once 'database.php';
  $session_uname= $_SESSION['session_username'];

  $sql="SELECT * FROM paragelia where idd='$session_uname' and seenbyd='no' " ;
  $result = mysqli_query($mysql_con,$sql);

  while($row = mysqli_fetch_array($result)) {

  $customerAdress = $row['dieuthinsi'];

  }

  if (isset($customerAdress)){
    echo $customerAdress;
  }
?>
