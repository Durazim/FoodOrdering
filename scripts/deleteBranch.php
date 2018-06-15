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
    $sql = "delete from branch where bid = ? and rid = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($bid,$arr[2]));
    header('location:../profile.php');
    $db = null;
  }
  catch (PDOException $ex) {
    die($ex->getMessage());
  }

 ?>
