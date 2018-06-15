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
      $did = $temp[1];
      $rating = $temp[0];
      $stmt = $db->prepare("select * from dishrating where CID = ? and DID = ?");
      $stmt->execute(array($arr[2],$did));
      if($stmt->rowCount() != 0){
        $stmt = $db->prepare("update dishrating set rating = ? where CID = ? and DID = ?");
        $stmt->execute(array($rating,$arr[2],$did));
      }
      else {
        $stmt = $db->prepare("insert into dishrating values(?,?,?)");
        $stmt->execute(array($did,$arr[2],$rating));
      }
      //calculate rating
      $stmt = $db->prepare("select * from dishrating where DID = ?");
      $stmt->execute(array($did));
      $count = $stmt->rowCount();
      $tot = 0;
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $tot += $row->rating;
      }
      $res = $tot / $count;
      $res = ceil($res);
      $stmt = $db->prepare("update dish set Rating = ? where DID = ?");
      $stmt->execute(array($res,$did));
      $back = $_SERVER['HTTP_REFERER'];
      header("location:$back");
      $db = null;
    }
    catch (PDOException $ex) {
      die($ex->getMessage());
    }
  }
?>
