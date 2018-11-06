<?php
session_start();
if (isset($_SESSION['session_username']) && $_SESSION['role'] == "delivery") {
  header('Location: /delivery/main_page/mainpage.php', true, 302);
  exit(0);
} else  {
  if(isset( $_GET["msg"]) == 'failed'){
    echo '<font color="#FF0000"> <p align="center">Wrong password or username</p></font>';
  }
}
?>

<!DOCTYPE html>

<html>
  <head>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <title>Coffee Shop</title>
  </head>

  <body style="background-color:lightblue">
    <br>
    <br>
    <h1 class="display-5 text-center"> <font face = "Times New Roman">Είσοδος διανομέα</h1>
    <div class="container">
      <div class="row">
        <div class="col">
          <form action="/delivery/login/login_check.php" method="POST">
          <div class="imgcontainer">
          <img src="/coffee-shop-logo1-.jpg" alt="Avatar" class="avatar">
          </div>
          <label><b>Username</b></label>
          <input type="text" placeholder="Enter Username" name="usn" >
          <label><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="psw" >
          <button type="submit" name="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  </body>
</html>
