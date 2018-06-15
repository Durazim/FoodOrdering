<?php
  require('../loginTest.php');
  if(!isset($arr))
  {
    header('location:../login.php');
    die();
  }
  if($arr[0] == 'customer')
  {
    header('location:index.php');
		die();
  }
?>
<?php
  extract($_GET);
  try
  {
    require('connection.php');
    $sql = "delete from dish where DID = ? and rid = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($did,$arr[2]));
    header('location:../restaurantDishes.php');
    $db = null;
  }
  catch (PDOException $ex)
  {
    die($ex->getMessage());
  }

 ?>
