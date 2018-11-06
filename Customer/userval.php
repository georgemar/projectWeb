<?php
session_start();
if (isset($_SESSION['session_username']) && $_SESSION['role'] == "customer") {
  header('Location: logged.php', true, 302);
  exit;
}
//σύνδεση στην βάση
$db_server["host"] = "localhost"; //database server
$db_server["username"] = "root"; // DB username
$db_server["password"] = "3398426754"; // DB password
$db_server["database"] = "web";// database name
$mysql_con = mysqli_connect($db_server["host"], $db_server["username"], $db_server["password"], $db_server["database"]);
$mysql_con->query ('SET CHARACTER SET utf8');
$mysql_con->query ('SET COLLATION_CONNECTION=utf8_general_ci');
if ($_POST["type"] == "new") { //αν ο χρήστης είναι νέος
  $mail = $_POST['email'];
  $pass = $_POST['pass'];
  $tel = $_POST['tel'];
  $query ="select * from pelaths where email ='$mail' limit 1";
  $result = $mysql_con->query($query);
  if (mysqli_num_rows($result) != 0) {
    echo "<script>alert('Το email χρησημοποιείται ήδη')</script>";
    include 'login.php';
  } else {
    $query = "insert into pelaths values('$mail','$pass','$tel');";
    $result = $mysql_con->query($query);
    if ($result) {
      $_SESSION['session_username'] = $_POST['email'];
      $_SESSION['role'] = "customer";
      include 'logged.php';
    } else {
      echo "<script>alert('Κάτι πήγε στραβά δοκιμάστε αργότερα')</script>";
      include 'index.php';
    }
  }
} elseif ($_POST["type"] == "existing"){
  $mail = $_POST['email'];
  $pass = $_POST['pass'];
  $query ="select email,pass from pelaths where email ='$mail' and pass='$pass' limit 1";
  $result = $mysql_con->query($query);
  if($result->fetch_array()) {
    $_SESSION['session_username'] = $_POST['email'];
    $_SESSION['role'] = "customer";
    include 'logged.php';
  } else {
    echo "<script>alert('Λάθος email ή κωδικός')</script>";
    include 'login.php';
  }
}
?>
