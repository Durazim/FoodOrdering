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
  if(isset($did) && isset($comment)){
    if (!(trim($did) == '' || trim($comment) == '')) {
      try {
        require('connection.php');
        $sql = "insert into dishreviews VALUES(?,?,?,?,NOW(),?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(null,$did,$arr[2],$comment,""));
        header("location:../dish.php?did=$did&comment=yes");
        $db = null;
      }
      catch (PDOException $ex) {
        die($ex->getMessage());
      }
    }
    else {
      $back = $_SERVER['HTTP_REFERER'];
      header("location:../dish.php?did=$did&missComment=yes");
    }
  }
?>
