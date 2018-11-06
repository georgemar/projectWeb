<?php
session_start();
if (isset($_SESSION['session_username']) && $_SESSION['role'] == "manager") {
  header('Location: pinakaki.php', true, 302);
  exit;
}
//Pairnaw tis parametrous apo to mainpage.php
$username = $_POST['username'];
$password = $_POST['password'];

//Sindesi sto server kai dialogh database
$con=mysqli_connect("localhost","root","","web");
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
	 $_SESSION['session_username']=$username;
	 $_SESSION['session_password']=$password;
	 $_SESSION['role']="manager";
	 header("Location: pinakaki.php");
 } else {
	 echo "<script>alert('Wrong Username or Password');</script>";
   include "mainpage.php";
 }
?>
