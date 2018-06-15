<?php
  session_start();
  unset($_SESSION['order']);
  header('location:../restaurants.php');
?>
