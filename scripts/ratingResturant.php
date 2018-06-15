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
  if(isset($rating)){
    try {
      require('connection.php');
      $temp = explode('#',$rating);
      $rid = $temp[1];
      $rating = $temp[0];
      $stmt = $db->prepare("select * from restaurantrating where CID = ? and rid = ?");
      $stmt->execute(array($arr[2],$rid));
      if($stmt->rowCount() != 0){
        $stmt = $db->prepare("update restaurantrating set rating = ? where CID = ? and rid = ?");
        $stmt->execute(array($rating,$arr[2],$rid));
      }
      else {
        $stmt = $db->prepare("insert into restaurantrating values(?,?,?)");
        $stmt->execute(array($rid,$arr[2],$rating));
      }
      //calculate rating
      $stmt = $db->prepare("select * from restaurantrating where rid = ?");
      $stmt->execute(array($rid));
      $count = $stmt->rowCount();
      $tot = 0;
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $tot += $row->rating;
      }
      $res = $tot / $count;
      $res = ceil($res);
      $stmt = $db->prepare("update restaurants set rating = ? where rid = ?");
      $stmt->execute(array($res,$rid));
      $back = $_SERVER['HTTP_REFERER'];
      header("location:$back");
      $db = null;
    }
    catch (PDOException $ex) {
      die($ex->getMessage());
    }
  }
?>
