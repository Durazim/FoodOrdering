<?php
  require('../loginTest.php');
  if(!isset($arr)){
    header('location:../login.php');
    die();
  }
  if($arr[0] == 'customer'){
    header('location:index.php');
		die();
  }
?>
<?php
  extract($_GET);
  try {
    require('connection.php');
    $sql = "delete from restaurantstypes where tid = ? and rid = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($tid,$arr[2]));
    header('location:../profile.php');
    $db = null;
  }
  catch (PDOException $ex) {
    die($ex->getMessage());
  }

 ?>
