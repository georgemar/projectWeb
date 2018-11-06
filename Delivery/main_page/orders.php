<?php session_start(); ?>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th{
  background-color: blue;
  color: white;
}
</style>
<?php
session_start();
include_once 'database.php';
$session_uname= $_SESSION['session_username'];
$sql="SELECT * FROM paragelia where idd='$session_uname' and seenbyd='no' " ;
$deliveryAddress="SELECT dieuthinsi FROM paragelia WHERE idd='$session_uname' and seenbyd='no' ";
$result = mysqli_query($mysql_con,$sql);
echo "<h2 align='center'> Παραγγελίες </h2>" ;
echo "<table id ='par'>
        <thead>
        <tr>
        <th>Όνομα πελάτη</th>
        <th>Κατάστημα Παραλαβής</th>
        <th>διεύθηνση πελάτη</th>
        </tr>
        </thead>" ;
while($row = mysqli_fetch_array($result)) {
    echo "<tbody>";
    echo "<tr>";
    echo "<td>" . $row['idx'] . "</td>";
    echo "<td>" . $row['idk'] . "</td>";
    echo "<td>" . $row['dieuthinsi'] . "</td>";
    echo "</tr>";
    echo "</tbody>";
}
echo "</table>";
mysqli_close($mysql_con);
?>
