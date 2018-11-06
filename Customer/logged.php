<?php
session_start();
if (!isset($_SESSION['session_username']) || $_SESSION['role'] != "customer") {
  header('Location: login.php', true, 302);
  exit;
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="base.css">
    <title>Coffee Shop</title>
  </head>
  <body style="background-color:#ffa64d">
    <p id='lat' class = 'error' >Troll</p>
    <p id='lng' class = 'error' >Troll</p>
    <div class='container'>
      <div class='row'>
        <div class='col-sm-4'>
          <h1 id='user' align='center'></h1>
          <button onclick="window.location.href='logoff.php'">Logout</button>
        </div>
        <div class='col-sm-4'>
          <h1 align='center'>Καλάθι</h1>
          <table id="cart" class='table'>
            <tr id="head">
              <th>Προιόν</th>
              <th>Ποσότητα</th>
              <th>Συνολική τιμή προιόντος</th>
            </tr>
            <tr id='foot'>
              <td>Συνολο</td>
              <td></td>
              <td>0.00€</td>
            </tr>
          </table>
        </div>
        <div class="col-sm-4">
          <h1 align='center'>Στοιχεία</h1>
          Κουδούνι : <input id="bell" type="text"><span class='error' id='wbell'> Κουδούνι παρακαλώ</span><br>
          Διευθυνση : <a id='addr'></a><span class='error' id='waddr'> Διευθυνση παρακαλώ</span><br>
          <button onclick="return sendOrd()">Καταχώρηση</button><span class='error' id='wcart'> Αδειο καλαθι</span>
        </div>
      </div>
      <div class='row'>
        <div class='col-sm-8'>
          <div class="pac-card" id="pac-card">
            <div>
              <div id="title">
                Επιλέξτε διεύθυνση
              </div>
            </div>
            <div id="pac-container">
              <input id="pac-input" type="text">
            </div>
          </div>
          <div id="map">
          </div>
          <div id="infowindow-content">
            <img src="" width="16" height="16" id="place-icon">
            <span id="place-name"  class="title"></span><br>
            <span id="place-address"></span>
          </div>
        </div>
        <div class='col-sm-4'>
          <h1 align='center'>Προιόντα</h1>
            <table id='prod'class='table'>
            </table>
          </div>
      </div>
    </div>
    <script src="logged.js"></script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyjyxRl4Fi4WGnC3kvTQqJs2BwbeaQ2SE&callback=initMap&libraries=places">
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
