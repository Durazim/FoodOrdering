<?php
  require('../loginTest.php');
  if(!isset($arr)){
    header('location:../index.php');
    die();
  }
  if($arr[0] == 'restaurant'){
		header('location:index.php');
		die();
  }
  extract($_POST);
  if(isset($rid) && isset($comment)){
    if (!(trim($rid) == '' || trim($comment) == '')) {
      try {
        require('connection.php');
        $sql = "insert into restaurantreviews VALUES(?,?,?,?,NOW(),?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(null,$rid,$arr[2],$comment,""));
        $back = $_SERVER['HTTP_REFERER'];
        header("location:../restaurant.php?rid=$rid&review=yes&p=1&comment=yes");
        $db = null;
      }
      catch (PDOException $ex) {
        die($ex->getMessage());
      }
    }
    else {
      $back = $_SERVER['HTTP_REFERER'];
      header("location:../restaurant.php?rid=$rid&menu=yes&p=1&missComment=yes");
    }
  }
?>
