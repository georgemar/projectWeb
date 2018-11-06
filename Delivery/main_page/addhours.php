<?php
$str_json = file_get_contents('php://input'); //διαβάζω raw δεδομένα του post
$obj = json_decode($str_json); //τα κάνω array στην php
$db_server["host"] = "localhost";
$db_server["username"] = "root";
$db_server["password"] = "";
$db_server["database"] = "web";
$mysql_con = mysqli_connect($db_server["host"], $db_server["username"], $db_server["password"], $db_server["database"]);
$mysql_con->query ('SET CHARACTER SET utf8');
$mysql_con->query ('SET COLLATION_CONNECTION=utf8_general_ci');
$now = new \DateTime('now');
$month = $now->format('m');
$year = $now->format('y');
$query = "select * from plhromesdel where idd like '".$obj->{"user"}."' and month like '".$month."' and year like '".$year."';";
$result = $mysql_con->query($query);
echo $obj->{"user"};
if (mysqli_num_rows($result) == 0) {
  $manager_money = ($obj->{"hours"} * 5);
  $query = "insert into plhromesdel values('".$obj->{"user"}."','".$month."','".$year."',".$manager_money.")";
  $result = $mysql_con->query($query);
} else {
  $row = $result->fetch_array();
  $manager_money = $row['euros'];
  $manager_money = $manager_money + ($obj->{"hours"} * 5);
  $query = "update plhromesdel set euros = ".$manager_money." where idd like '".$obj->{"user"}."';";
  $result = $mysql_con->query($query);
}
?>
