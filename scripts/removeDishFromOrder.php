<?php
  require('../loginTest.php');
  if(isset($arr)){
    if ($arr[0] == 'restaurant') {
      header('location:index.php');
      die();
    }
  }
  extract($_GET);
  if(isset($_SESSION['order']['dishes'][$did])){
    unset($_SESSION['order']['dishes'][$did]);
    if(count($_SESSION['order']['dishes']) == 0){
      unset($_SESSION['order']);
    }
  }
  header('location:../order.php');
?>
