<?php
session_start();
if (!isset($_SESSION['session_username']) || $_SESSION['role'] != "manager") {
  header('Location: mainpage.php', true, 302);
  exit;
}
$username = $_SESSION['session_username'];
$password=$_SESSION['session_password'];
if(empty($_GET["action"])){
   echo '<div style="display:hidden"  id="hey" style="color:red;">Παρακαλώ περιμένετε...</div>';
 }


 if(!empty($_GET["action"])) {
    $con=mysqli_connect("localhost","root","3398426754","web");
    $con->query ('SET CHARACTER SET utf8');
    $con->query ('SET COLLATION_CONNECTION=utf8_general_ci');
    if (mysqli_connect_errno()){
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $sql="SELECT * FROM manager WHERE user='$username' AND pass='$password'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
    if($row["user"] == $username && $row["pass"] == $password ){
      $magazi= $row["directing"];
    }
    require_once("dbh.inc.php");
    $db_handle = new DBController();
    $product = $db_handle->runQuery("SELECT * FROM paragelia WHERE idk LIKE '$magazi' AND delivered LIKE 'NO'");
    $num_rows = $db_handle->numRows("SELECT * FROM paragelia WHERE idk LIKE '$magazi' AND delivered LIKE 'NO'");
    echo "<table>
    <tr>
    <th>ID παραγγελίας</th>
    <th>ID χρήστη</th>
    <th>Διεύθυνση</th>
    <th>Κουδούνι</th>
    <th>Κατάστημα</th>
    <th>Διανομέας</th>
    <th>Σύνολο</th>
    <th>Απόσταση</th>
    <th>Παραδώθηκε</th>
    </tr>";
    for ( $i=0; $i<$num_rows; $i++){
      if ($i){
        echo "<table>
        <tr>
        <th>ID παραγγελίας</th>
        <th>ID χρήστη</th>
        <th>Διεύθυνση</th>
        <th>Κουδούνι</th>
        <th>Κατάστημα</th>
        <th>Διανομέας</th>
        <th>Σύνολο</th>
        <th>Απόσταση</th>
        <th>Παραδώθηκε</th>
        </tr>";
      }
       echo "<tr>";
       echo "<td>" . $product[$i]['id'] . "</td>";
       echo "<td>" . $product[$i]['idx'] . "</td>";
       echo "<td>" . $product[$i]['dieuthinsi'] . "</td>";
       echo "<td>" . $product[$i]['bell'] . "</td>";
       echo "<td>" . $product[$i]['idk'] . "</td>";
       echo "<td>" . $product[$i]['idd'] . "</td>";
       echo "<td>" . $product[$i]['sum'] . "</td>";
       echo "<td>" . $product[$i]['distance'] . "</td>";
       echo "<td>" . $product[$i]['delivered'] . "</td>";
       echo "</tr>";
       $par = $product[$i]['id'];
       $product2 = $db_handle->runQuery("SELECT * FROM periexomeno WHERE idp = '$par'");
       $num_rows2 = $db_handle->numRows("SELECT * FROM periexomeno WHERE idp = '$par'");
       echo "<tr>
       <th>ID παραγγελίας</th>
       <th>Προιόν</th>
       <th>Ποσότητα</th>
       </tr>";
       for ( $j=0; $j<$num_rows2; $j++){
            echo "<tr>";
            echo "<td>" . $product2[$j]['idp'] . "</td>";
            echo "<td>" . $product2[$j]['proion'] . "</td>";
            echo "<td>" . $product2[$j]['posothta'] . "</td>";
            echo "</tr>";
       }


      }
      echo "</table>";
 }


?>
  <html>
  <head>
    <link rel="stylesheet" href="css/table.css">
  </head>

  <body>
  <br>

     <div id="txtHint"></div>

   <script>
    function showUser() {
      if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
      } else {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          document.getElementById("txtHint").innerHTML = this.responseText;
        }
      };
      xmlhttp.open("GET","orders.php?action=go",true);
      xmlhttp.send();
      document.getElementById("hey").innerHTML = "";
    }
    showUser();
    setInterval( "showUser()", 5000 );
  </script>
  </body>
</html>
