<?php
session_start();
if (!isset($_SESSION['session_username']) || $_SESSION['role'] != "manager") {
  header('Location: mainpage.php', true, 302);
  exit;
}
//Pairnaw tis parametrous apo to mainpage.php
$traxanas = $_POST['traxanas'];
$tiropita = $_POST['tiropita'];
$tost = $_POST['tost'];
$keik = $_POST['keik'];
$koulouri = $_POST['koulouri'];
$xortopita = $_POST['xortopita'];
$username = $_SESSION['session_username'];
$password = $_SESSION['session_password'];

if ($traxanas == NULL){
  $traxanas = 0;
}
if ($tiropita == NULL){
  $tiropita = 0;
}
if ($tost == NULL){
  $tost = 0;
}
if ($keik == NULL){
  $keik = 0;
}
if ($koulouri == NULL){
  $koulouri = 0;
}
if ($xortopita == NULL){
  $xortopita = 0;
}
//Sindesi sto server kai dialogh database
$con=mysqli_connect("localhost","root","3398426754","web");
$con->query ('SET CHARACTER SET utf8');
$con->query ('SET COLLATION_CONNECTION=utf8_general_ci');
if (mysqli_connect_errno())
{
   echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

//Querry th database gia manager
$sql="SELECT * FROM manager WHERE user='$username' AND pass='$password'";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

if($row["user"] == $username && $row["pass"] == $password ){
	 $directing = $row["directing"];
}
$storename="SELECT * FROM store WHERE name='$directing'";
$result1 = mysqli_query($con,$storename);
$row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
if($row1["name"]==$directing){
	$name = $row1["name"];
}
$query = "select * from reserve where ids = '$name' and idp='τραχανας'";
$result = $con->query($query);
if (mysqli_num_rows($result) == 0) {
  $set_quantity="insert into reserve values ('$name','τραχανας','$traxanas')";
  $con->query($set_quantity);
} else {
  $row = $result->fetch_array();
  $traxanas = $traxanas + $row['quantity'];
  $set_quantity="UPDATE reserve SET quantity='$traxanas' WHERE idS='$name' AND idP='τραχανας'";
  $con->query($set_quantity);
}

$query = "select * from reserve where ids = '$name' and idp='τυροπιτα'";
$result = $con->query($query);
if (mysqli_num_rows($result) == 0) {
  $set_quantity="insert into reserve values ('$name','τυροπιτα','$tiropita')";
  $con->query($set_quantity);
} else {
  $row = $result->fetch_array();
  $tiropita = $tiropita + $row['quantity'];
  $set_quantity="UPDATE reserve SET quantity='$tiropita' WHERE idS='$name' AND idP='τυροπιτα'";
  $con->query($set_quantity);
}

$query = "select * from reserve where ids = '$name' and idp='τοστ'";
$result = $con->query($query);
if (mysqli_num_rows($result) == 0) {
  $set_quantity="insert into reserve values ('$name','τοστ','$tost')";
  $con->query($set_quantity);
} else {
  $row = $result->fetch_array();
  $tost = $tost + $row['quantity'];
  $set_quantity="UPDATE reserve SET quantity='$tost' WHERE idS='$name' AND idP='τοστ'";
  $con->query($set_quantity);
}

$query = "select * from reserve where ids = '$name' and idp='κεικ'";
$result = $con->query($query);
if (mysqli_num_rows($result) == 0) {
  $set_quantity="insert into reserve values ('$name','κεικ','$keik')";
  $con->query($set_quantity);
} else {
  $row = $result->fetch_array();
  $keik = $keik + $row['quantity'];
  $set_quantity="UPDATE reserve SET quantity='$keik' WHERE idS='$name' AND idP='κεικ'";
  $con->query($set_quantity);
}

$query = "select * from reserve where ids = '$name' and idp='κουλουρι'";
$result = $con->query($query);
if (mysqli_num_rows($result) == 0) {
  $set_quantity="insert into reserve values ('$name','κουλουρι','$koulouri')";
  $con->query($set_quantity);
} else {
  $row = $result->fetch_array();
  $koulouri = $koulouri + $row['quantity'];
  $set_quantity="UPDATE reserve SET quantity='$koulouri' WHERE idS='$name' AND idP='κουλουρι'";
  $con->query($set_quantity);
}

$query = "select * from reserve where ids = '$name' and idp='χορτοπιτα'";
$result = $con->query($query);
if (mysqli_num_rows($result) == 0) {
  $set_quantity="insert into reserve values ('$name','χορτοπιτα','$xortopita')";
  $con->query($set_quantity);
} else {
  $row = $result->fetch_array();
  $xortopita = $xortopita + $row['quantity'];
  $set_quantity="UPDATE reserve SET quantity='$xortopita' WHERE idS='$name' AND idP='χορτοπιτα'";
  $con->query($set_quantity);
}
include "pinakaki.php"
?>
