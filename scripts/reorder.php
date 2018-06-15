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
  if(!isset($oid)){
    header("location:allOrders.php?acknowledge=yes&p=1");
    die();
  }
  if(trim($oid) == ""){
    header("location:allOrders.php?acknowledge=yes&p=1");
    die();
  }
  try {
    require("connection.php");
    $orderDetails = $db->prepare("select * from orderslist where oid = ?");
    $orderDetails->execute(array($oid));
    $orderDetails = $orderDetails->fetch(PDO::FETCH_OBJ);
    $_SESSION['order']['rid'] = $orderDetails->rid;
    $orderdishes = $db->prepare("select * from orderdishes where oid = ?");
    $orderdishes->execute(array($orderDetails->oid));
    while ($dish = $orderdishes->fetch(PDO::FETCH_OBJ)) {
      if(isset($_SESSION['order']['dishes'])){
        $dishes = $_SESSION['order']['dishes'];
      }
      $dishes[$dish->did] = $dish->qty;
      $_SESSION['order']['dishes'] = $dishes;
      }
      $db = null;
      header("location:../order.php");
    }
    catch (PDOException $ex) {
      die($ex->getMessage());
    }
?>
