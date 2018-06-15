<?php
  require('../loginTest.php');
  if(!isset($arr)){
    header('location:../index.php');
    die();
  }
  if($arr[0] == 'customer'){
		header('location:index.php');
		die();
  }
  extract($_POST);
  if(isset($commid) && isset($replay)){
    if (!(trim($commid) == '' || trim($replay) == '')) {
      try {
        require('connection.php');
        $sql = "UPDATE restaurantreviews set replaycomment = ? where commid = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($replay,$commid));
        header("location:../restaurantComments.php?commid=$commid&comment=yes");
        $db = null;
      }
      catch (PDOException $ex) {
        die($ex->getMessage());
      }
    }
    else {
      header("location:../restaurantComments.php?commid=$commid&missComment=yes");
    }
  }
?>
