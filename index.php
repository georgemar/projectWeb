<?php
session_start();
if (isset($_SESSION['session_username']) and $_SESSION['role'] == "customer") {
  header('Location: Customer/logged.php', true, 302);
} else if (isset($_SESSION['session_username']) and $_SESSION['role'] == "manager") {
  header('Location: Manager/pinakaki.php', true, 302);
} else if (isset($_SESSION['session_username']) and $_SESSION['role'] == "delivery") {
  header('Location: Delivery/main_page/mainpage.php', true, 302);
} else {
  header('Location: Customer/login.php', true, 302);
}
?>
