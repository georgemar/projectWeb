<?php
session_start();
if (!isset($_SESSION['session_username']) || $_SESSION['role'] != "manager") {
  header('Location: mainpage.php', true, 302);
  exit;
}
?>

<html>
  <head>
    <meta charset="utf-8">
    <title>Coffee Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body style="background-color:#cc4400">
    <div class="container">
      <div class="row">
        <div class="col">
          <h1 class="display-5 text-center">Προσθέστε στο απόθεμα του καταστήματος</h1>
          <form method="post" action="pinakas.php">
            <table style="width:100%">
              <thead>
                <tr>
                  <th>Κέϊκ</th>
                  <th>Τραχανάς</th>
                  <th>Κουλούρι</th>
                  <th>Τόστ</th>
                  <th>Τυρόπιτα</th>
                  <th>Χορτοπιτα</th>
                  <th>Επιβεβαίωση;</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th><input type="number" name="keik" min="1" max="2000"></th>
                  <th><input type="number" name="traxanas" min="1" max="2000"></th>
                  <th><input type="number" name="koulouri" min="1" max="2000"></th>
                  <th><input type="number" name="tost" min="1" max="2000"></th>
                  <th><input type="number" name="tiropita" min="1" max="2000"></th>
                  <th><input type="number" name="xortopita" min="1" max="2000"></th>
                  <th><button type="submit">Ναι</button></th>
                </tr>
              </tbody>
            </table>
           </form>
         </div>
       </div>
     </div>
    <div class="container">
      <div class="row">
        <div class="col">
          <h1 class="display-5 text-center">Παρακολούθηση παραγγελιών</h1>
          <?php include 'orders.php' ?>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
         <form method="post" action="logout.inc.php">
           <button type="submit">Logout</button>
         </form>
       </div>
     </div>
    </div>
  </body>
</html>
