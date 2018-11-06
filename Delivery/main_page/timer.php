<?php
  session_start();
  $db_server["host"] = "localhost";
  $db_server["username"] = "root";
  $db_server["password"] = "";
  $db_server["database"] = "web";
  $mysql_con = mysqli_connect($db_server["host"], $db_server["username"], $db_server["password"], $db_server["database"]);
  $mysql_con->query ('SET CHARACTER SET utf8');
  $mysql_con->query ('SET COLLATION_CONNECTION=utf8_general_ci');
  $session_uname = $_SESSION['session_username'];
  $query="SELECT * FROM paragelia where idd='$session_uname' and seenbyd='yes' and delivered='no'";
  $result = $mysql_con->query($query);
  while($row = $result->fetch_array()){
    $distance = $row['distance'];
  }
  echo $distance;
?>
