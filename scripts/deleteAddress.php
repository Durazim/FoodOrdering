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
?>
<?php
  extract($_GET);
  try {
    require('connection.php');
    $sql = "delete from customeraddress where AID = ? and CID = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($aid,$arr[2]));
    header('location:../profile.php');
    $db = null;
  }
  catch (PDOException $ex) {
    die($ex->getMessage());
  }

 ?>
