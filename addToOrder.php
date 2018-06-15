<?php
  session_start();
  extract($_POST);
  $temp = explode("#",$ridDid);
  $rid = $temp[0];
  $did = $temp[1];
  if(isset($_SESSION['order'])){
    if($_SESSION['order']['rid'] != $rid){
      unset($_SESSION['order']['dishes']);
    }
    $_SESSION['order']['rid'] = $rid;
    if(isset($_SESSION['order']['dishes'])){
      $dishes = $_SESSION['order']['dishes'];
    }
    $dishes[$did] = $qty;
    $_SESSION['order']['dishes'] = $dishes;
  }
  else {
    $_SESSION['order']['rid'] = $rid;
    $dishes[$did] = $qty;
    $_SESSION['order']['dishes'] = $dishes;
  }
  header("location:order.php");
?>
