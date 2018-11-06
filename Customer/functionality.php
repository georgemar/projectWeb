<?php
session_start();
if (!isset($_SESSION['session_username']) || $_SESSION['role'] != "customer") {
  header('Location: login.php', true, 302);
  exit;
}
//Διαβάζω την είσοδο απο το header
$str_json = file_get_contents('php://input'); //διαβάζω raw δεδομένα του post
$obj = json_decode($str_json); //τα κάνω array στην php
//Σύνδεση με την βάση
$db_server["host"] = "localhost";
$db_server["username"] = "root";
$db_server["password"] = "";
$db_server["database"] = "web";
$mysql_con = mysqli_connect($db_server["host"], $db_server["username"], $db_server["password"], $db_server["database"]);
$mysql_con->query ('SET CHARACTER SET utf8');
$mysql_con->query ('SET COLLATION_CONNECTION=utf8_general_ci');
//εδω διαλέγω μία απο τις λειτουργίες που απαιτούνται για την παραγγελία
if ($obj->{"function"} == "fetch"){
  //η fetch φέρνει δυναμικά τα προιόντα απο την βάση και τα δίνει στο js
  $query ="select * from Product";
  $result = $mysql_con->query($query);
  while($row = $result->fetch_array()){
    $id = $row['name'];
    $price = $row['price'];
    $tosent1[] = ['id'=>$id,'price'=>$price];
  }
  $query = "select * from store";
  //επιπλέον στέλνει στο js τα καταστήματα και τις αποστάσεις τους για να βρεί το κοντινότερο
  $result = $mysql_con->query($query);
  while($row = $result->fetch_array()){
    $name = $row['name'];
    $lat = $row['lattitude'];
    $lng = $row['longtitude'];
    $tosent2[] = ['name'=>$name,'lat'=>$lat,'lng'=>$lng];
  }
  $tosent[] = ['products'=>$tosent1,'stores'=>$tosent2,'user'=>$_SESSION['session_username']];
  echo json_encode($tosent,JSON_UNESCAPED_UNICODE);
} else if ($obj->{"function"} == "order"){
  //η order παίρνει την παραγγελία του πελάτη και ελέγχει αν υπάρχουν
  //τα προίντα και σε ποιά καταστήματα και αν υπάρχει ελεύθερος διανομέας
  $fail = 1;
  for ($j=0; $j<sizeof($obj->{"stores"}); $j++){
    $quantity_flag = 1;
    for ($i=0; $i<sizeof($obj->{"goods"}); $i++){
      if ($obj->{"goods"}[$i]->product == 'ελληνικος' || $obj->{"goods"}[$i]->product == 'εσπρεσο' || $obj->{"goods"}[$i]->product == 'καπουτσινο' || $obj->{"goods"}[$i]->product == 'φιλτρου'
      || $obj->{"goods"}[$i]->product == 'φραπε'){
        continue;
      }
      $query = "select ids from Reserve where ids like '".$obj->{"stores"}[$j]->name."' and idp like '".$obj->{"goods"}[$i]->product."' and ".$obj->{"goods"}[$i]->count." <= quantity;";
      $result = $mysql_con->query($query);
      if (!$result->fetch_array()){
        $quantity_flag = 0;
        break;
      }
    }
    if ($quantity_flag){
      $fail = 0;
      break;
    }
  }
  if ($fail){ //αν δεν βρέι το προιόν σε κανένα κατάστημα επιστρέφει λάθος
    $tosent3[] = ['failed'=>'1', 'message'=>'Κανένα κατάστημα δεν έχει τα προιόντα που ζητήσατε.'];
    echo json_encode($tosent3,JSON_UNESCAPED_UNICODE);
    exit(0);
  }
  $query = 'select * from store where name like "'.$obj->{"stores"}[$j]->name.'" limit 1;';
  $result = $mysql_con->query($query);
  while($row = $result->fetch_array()){
    $lat = $row['lattitude'];
    $lng = $row['longtitude'];
    $tosent1[] = ['name'=>$obj->{"stores"}[$j]->name,'lat'=>$lat,'lng'=>$lng];
  }
  $result = $mysql_con->query("select * from dianomeas where katastasi like 'online' and langtitude != 0 and longtitude != 0;");
  while($row = $result->fetch_array()){
    $username = $row['username'];
    $lat = $row['langtitude'];
    $lng = $row['longtitude'];
    $tosent2[] = ['username'=>$username,'lat'=>$lat,'lng'=>$lng];
  }
  if (sizeof($tosent2) == 0){ //αν δεν υπάρχει ελεύθερος διανομές επιστρέφει λάθος
    $tosent4[] = ['failed'=>'1', 'message'=>'Δεν υπάρχει ελεύθερος ντελιβεράς αυτη την στιγμη, προσπαθήστε ξανα.'];
    echo json_encode($tosent4,JSON_UNESCAPED_UNICODE);
    exit(0);
  }
  //Εδω θα αυξήσω τον μισθό του manager για τον μήνα
  $query = "select user from manager where directing like '".$obj->{"stores"}[$j]->name."' limit 1;";
  $result = $mysql_con->query($query);
  while($row = $result->fetch_array()){
    $user = $row['user'];
  }
  $now = new \DateTime('now');
  $month = $now->format('m');
  $year = $now->format('y');
  $query = "select * from plhromesman where idx like '".$user."' and month like '".$month."' and year like '".$year."';";
  $result = $mysql_con->query($query);
  if (mysqli_num_rows($result) == 0) {
    $manager_money = 800 + $obj->{"sum"}*0.02;
    $query = "insert into plhromesman values('".$user."','".$month."','".$year."',".$manager_money.")";
    $result = $mysql_con->query($query);
  } else {
    $row = $result->fetch_array();
    $manager_money = $row['euros'];
    $manager_money = $manager_money + $obj->{"sum"}*0.02;
    $query = "update plhromesman set euros = ".$manager_money." where idx like '".$user."' and month like '".$month."' and year like '".$year."';";
    $result = $mysql_con->query($query);
  }
  //Εδώ φτιάχνω την παραγγελία
  $query = "insert into paragelia values(0,'".$obj->{"user"}."','".$obj->{"address"}."','".$obj->{"bell"}."','".$obj->{"stores"}[$j]->name."',null,'NO','NO',".$obj->{"sum"}.",null,'NO')";
  $result = $mysql_con->query($query);
  $result = $mysql_con->query("SELECT LAST_INSERT_ID()");
  $id = $result->fetch_array()[0];
  for ($i=0; $i<sizeof($obj->{"goods"}); $i++){
    $query = "insert into periexomeno values(".$id.",'".$obj->{"goods"}[$i]->product."','".$obj->{"goods"}[$i]->count."')";
    $result = $mysql_con->query($query);
  }
  $tosent[] = ['store'=>$tosent1, 'deliveries'=>$tosent2, 'paragelia'=>$id, 'failed'=>'0'];
  echo json_encode($tosent,JSON_UNESCAPED_UNICODE);
} else if ($obj->{"function"} == "select_delivery"){
  $query = "update paragelia set idd ='".$obj->{"user"}."' where id = ".$obj->{"id"};
  $result = $mysql_con->query($query);
  $fdest = $obj->{"finaldest"}/1000;
  $query = "update paragelia set distance = ".$fdest." where id = ".$obj->{"id"}.";";
  $result = $mysql_con->query($query);
  $now = new \DateTime('now');
  $month = $now->format('m');
  $year = $now->format('y');
  $query = "select * from plhromesdel where idd like '".$obj->{"user"}."' and month like '".$month."' and year like '".$year."';";
  $result = $mysql_con->query($query);
  if (mysqli_num_rows($result) == 0) {
    $manager_money = $fdest * 0.1;
    $query = "insert into plhromesdel values('".$obj->{"user"}."','".$month."','".$year."',".$manager_money.")";
    $result = $mysql_con->query($query);
  } else {
    $row = $result->fetch_array();
    $manager_money = $row['euros'];
    $manager_money = $manager_money + $fdest * 0.1;
    $query = "update plhromesdel set euros = ".$manager_money." where idd like '".$obj->{"user"}."' and month like '".$month."' and year like '".$year."';";
    $result = $mysql_con->query($query);
  }
  $query = "update dianomeas set katastasi ='BUSY', langtitude = ".$obj->{"lat"}." , longtitude = ".$obj->{"long"}."  where username like '".$obj->{"user"}."';";
  $result = $mysql_con->query($query);
  $query = "select idk from paragelia where id =".$obj->{"id"};
  $result = $mysql_con->query($query);
  $idk = $result->fetch_array()[0];
  for ($i=0; $i<sizeof($obj->{"goods"}); $i++){
    if ($obj->{"goods"}[$i]->product == 'ελληνικος' || $obj->{"goods"}[$i]->product == 'εσπρεσο' || $obj->{"goods"}[$i]->product == 'καπουτσινο' || $obj->{"goods"}[$i]->product == 'φιλτρου'
    || $obj->{"goods"}[$i]->product == 'φραπε'){
      continue;
    }
    $query = "select quantity from Reserve where idS like '".$idk."' and idP like '".$obj->{"goods"}[$i]->product."';";
    $result = $mysql_con->query($query);
    $quantity = $result->fetch_array()[0];
    $towrite = $quantity - $obj->{"goods"}[$i]->count;
    $query = "update reserve set quantity = ".$towrite." where idS like '".$idk."' and idP like '".$obj->{"goods"}[$i]->product."'";
    $result = $mysql_con->query($query);
  }
  echo "Καταχωρήθηκε με επιτυχία η παραγγελία σας";
}
