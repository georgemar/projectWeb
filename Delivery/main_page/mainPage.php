<?php
session_start();
if (!isset($_SESSION['session_username']) || $_SESSION['role'] != "delivery") {
  header('Location: /delivery/login/login.php', true, 302);
  exit(0);
}
echo "<h1 align='center'>Καλώς ήρθες ".$_SESSION['session_username']."  </h1>";
echo "<p id='paragraph' align='center'> δώσε την τοποθεσία σου στον χάρτη και την κατάσταση σου </p>";
echo "<h1 id='name' style='display:none'>".$_SESSION['session_username']."</h1>";
?>
<!DOCTYPE html>
<html>
  <head>
      <title>Coffee Shop</title>
      <link rel="stylesheet" type="text/css" href="style.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
      <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </head>

  <body style="background-color:lightblue">
    <div class="container">
      <div class="row">
        <div class="col">
          <form action="/Delivery/login/logout.php" method="POST" onclick="stopCount();">
             <button  type="submit" name="submit_out" style="float: right;">Logout</button>
          </form>

          <p id='address' style="display:none"></p>
          <p id='distance' style="display:none">0</p>
          <form>
            <select disabled id="mySelect" name="kat" onchange="changeKat(this.value);showTxthint(this.value)">
              <option value="">Select your status</option>
              <option value="online">ONLINE</option>
              <option value="offline">OFFLINE</option>
            </select>
          </form>
        </div>
      </div>
    </div>
    <br>
    <?php include "map.php";?>
    <div class="container">
      <div class="row">
        <div class="col">
          <div style="display:none" id="txtHint">

          </div>
        </div>
      </div>
    </div>
    <div id="wrap">
      <button style="display: none" type="button" id="confirmO" onclick="confirmButton()" >Confirm Order</button>
      <button style="display: none" type="button" id="deliverdO" onclick="deliveredButton()">Deliverd</button>
      <button style="display: none" type="button" id="deliverdOO" onclick="deliveredOfflineButton()">Deliverd and Offline</button>
    </div>
    <script>
      function deliveredOfflineButton(){
        changeKat("offout");
        document.getElementById("mySelect").value = 'offline';
        document.getElementById('confirmO').style.display = 'none';
        document.getElementById('deliverdO').style.display = 'none';
        document.getElementById('deliverdOO').style.display = 'none';
        stopCount();
      }
      function changeKat(str) {
        if (str=="") {
          document.getElementById("kat").innerHTML="";
          return;
        }
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        } else {  // code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {

        }
      }
      xmlhttp.open("GET","change_con.php?q="+str,true);
      xmlhttp.send();
      }

      function deliveredButton(){
        changeKat();
        document.getElementById('confirmO').style.display = 'none';
        document.getElementById('wrap').style.textAlign = 'center';
        document.getElementById('deliverdO').style.display = 'none';
        document.getElementById('deliverdOO').style.display = 'none';
        document.getElementById('txtHint').style.display = 'block';
      }

      function confirmButton(){
        changeKat('busy');
        document.getElementById('confirmO').style.display = 'none';
        document.getElementById('deliverdO').style.display = 'inline';
        document.getElementById('deliverdOO').style.display = 'inline';
        document.getElementById('txtHint').style.display = 'none';
        postc()
      }

      function showTxthint(str){
        if (str=="online"){
          document.getElementById('txtHint').style.display = 'block';
          document.getElementById('wrap').style.textAlign = 'center';
          startCount();
        }else {
          document.getElementById('txtHint').style.display = 'none';
          document.getElementById('confirmO').style.display = 'none';
          stopCount();
        }
      }
      var c = 0;
      function timedCount() {
        c = c + 1;
      }

      var myVar;
      function startCount() {
        myVar = setInterval(timedCount ,1000);
      }

      function stopCount() {
        var hr = (c/3600).toFixed(7);
        var distance = parseFloat(document.getElementById("distance").innerHTML);
        c = 0;
        var apostash = parseFloat(document.getElementById("distance").innerHTML);
        document.getElementById("distance").innerHTML = '0';
        var xmlhttp3 = new XMLHttpRequest();
        xmlhttp3.open("POST","addhours.php",true);
        xmlhttp3.setRequestHeader("Content-type", "application/json");
        xmlhttp3.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
            }
        };
        var doc = document.getElementById("name").innerHTML;
        doc = doc.slice(3, doc.length-4);
        var hour = {hours : hr, user : doc};
        xmlhttp3.send(JSON.stringify(hour));
        lefta = distance * 0.1 + hr * 5;
        alert("Βγαλατε " + lefta + "€" + "\nΚάνατε " + apostash + "km");
        clearInterval(myVar);
        c = 0;
      }

      function postc(){
        var xmlhttp5 = new XMLHttpRequest();
        xmlhttp5.open("POST","timer.php",true);
        xmlhttp5.setRequestHeader("Content-type", "application/json");
        xmlhttp5.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              //alert(this.responseText);
              var distance = parseFloat(document.getElementById("distance").innerHTML);
              distance = distance + parseFloat(this.responseText);
              document.getElementById("distance").innerHTML = distance;
          }
        };
        xmlhttp5.send();
      }
     function showOrders() {
       if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp = new XMLHttpRequest();
        } else {
          // code for IE6, IE5
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("txtHint").innerHTML = this.responseText;
              if (document.getElementById("txtHint").innerHTML.length > 412){
                document.getElementById('confirmO').style.display = 'inline';
              } else {
                document.getElementById('confirmO').style.display = 'none';
              }
          }
        };
        xmlhttp.open("GET","orders.php",true);
        xmlhttp.send();

        var xmlhttp2 = new XMLHttpRequest();
        xmlhttp2.open("POST","placemarker.php",true);
        xmlhttp2.setRequestHeader("Content-type", "application/json");
        xmlhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
               document.getElementById("address").innerHTML = this.responseText;
            }
        };
        xmlhttp2.send();
      };
      showOrders();
      setInterval(function(){
        showOrders();
      },2000);
      showOrders();
    </script>
  </body>
</html>
