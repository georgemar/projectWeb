<?php
session_start();
if (isset($_SESSION['session_username']) && $_SESSION['role'] == "customer") {
  header('Location: logged.php', true, 302);
  exit;
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="style.css">
    <title>Coffee Shop</title>
  </head>

  <body style="background-color:#ffa64d">
    <div class="container">
      <div class="row">
        <div class='col-sm-4'>
          <h1 class="display-5 text-center">Καλώς ήρθατε στο Coffee Shop</h1>
          <div class="imgcontainer">
            <img src="/coffee-shop-logo1-.jpg" alt="Avatar" class="avatar">
          </div>
        </div>
        <div class='col-sm-4'>
          <h2>Νεος Χρηστης</h2>
          <form name="newuser" method="post" action="userval.php" onsubmit="return validateNew()">
            Email:<br>
            <input type="text" name="email"><span class='error' id='wmailn'> Λάθος mail</span><br><br> Κωδικος:
            <br>
            <input type="password" name="pass"><span class='error' id='wpassn'> Λάθος κωδικός</span><br><span class='hint'>8-32 χαρακτήρες</span><br> Τηλέφωνο:
            <br>
            <input type="text" name="tel"><span class='error' id='wteln'> Λάθος τηλέφωνο</span><br><span class='hint'>10 χαρακτήρες</span><br>
            <input type="hidden" name="type" value="new"/>
            <button type="submit" name="submit">Login</button>
          </form>
        </div>
        <div class='col-sm-4'>
          <h2>Υπαρχων Χρηστης</h2>
          <form name="existuser" method="post" action="userval.php" onsubmit="return validateExist()">
            Email:<br>
            <input type="text" name="email"><span class='error' id='wmaile'> Λάθος mail</span><br><br> Κωδικος:
            <br>
            <input type="password" name="pass"><span class='error' id='wpasse'> Λάθος κωδικός</span><br><span class='hint'>8-32 χαρακτήρες</span><br>
            <input type="hidden" name="type" value="existing"/>
            <button type="submit" name="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
    <script src="valid.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>

</html>
