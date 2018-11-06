<?php
session_start();
if (isset($_POST['submit'])){
    include_once 'database.php';
    $success=false;
    $user =mysqli_real_escape_string($mysql_con, $_POST['usn']);
    $pass =mysqli_real_escape_string($mysql_con,$_POST['psw']);
    if (empty($user) || empty($pass)){
        header("Location: /delivery/login/login.php?login=empty");
        exit(0);
    } else{
        $my_query = "SELECT username,password FROM dianomeas where username='$user' AND password='$pass'";
        $result = mysqli_query($mysql_con,$my_query);
        while($row = mysqli_fetch_array($result)) {
            $success = true;
        }
        if( $success == true) {
            $_SESSION['session_username'] = $user;
            $_SESSION['role'] = "delivery";
            header("Location: /delivery/main_page/mainPage.php");
        } else {
            header("Location: /delivery/login/login.php?msg=failed");
        }
    }
} else {
    header("Location: /delivery/login/login.php");
    exit();
}
?>
