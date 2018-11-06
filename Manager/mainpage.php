<?php
session_start();
if (isset($_SESSION['session_username']) && $_SESSION['role'] == "manager") {
  header('Location: pinakaki.php', true, 302);
  exit;
}
?>
<!DOCTYPE html>
<html>
  <head>
      <title>Coffee Shop</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <link rel="stylesheet" href="style.css">
  </head>
  <body style="background-color:#cc4400">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <h1 class="display-5 text-center">Είσοδος manager</h1>
          <div class="imgcontainer">
            <img src="/coffee-shop-logo1-.jpg" alt="Avatar" class="avatar">
          </div>
        </div>
        <div class="col-sm-8">
          <form method="post" action="login_check.php">
          Username : <input type="text" name="username"><br><br>
          Password : <input type="password" name="password"><br><br>
          <button type="submit" name="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
