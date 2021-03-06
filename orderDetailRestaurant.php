<?php
  require('loginTest.php');
  if(isset($arr)){
    if ($arr[0] == 'customer') {
      header('location:index.php');
      die();
    }
  }
    extract($_GET);
    if(!isset($oid)){
      header("location:allOrderRestaurant.php");
      die();
    }
    if(trim($oid) == ""){
      header("location:allOrderRestaurant.php");
      die();
    }
  try {
    require('scripts/connection.php');
    $sql = "select * from orderslist where rid = ? and oid = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute(array($arr[2],$oid));
  }
  catch (PDOException $ex)
  {
    die($ex->getMessage());
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - Order Details</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>

      .checked {
        color: orange;
      }
      .dish{
        padding: 10px;
      }
      .dish{
        background-color: #f6f6f6;
      }
    </style>
  </head>
  <body>

    <!-- Navigation -->

	<?php require('navbar.php'); ?>


    <!-- Page Content -->
    <div class="container">
      <br/>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Home</a>
        </li>
        <li class="breadcrumb-item">
          <a href="allOrderRestaurant.php">All Orders</a>
        </li>
        <li class="breadcrumb-item active">Order Details</li>
      </ol>
      <div class='row'>
        <div class="col-md-12">
          <?php
            if($stmt->rowCount() == 0){
              echo "<h3>Something is error</h3>";
            }
            else {
              while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                try{
                  require("scripts/connection.php");
                  $username = $db->prepare("select username from customer where CID = ?");
                  $username->execute(array($row->cid));
                  $username = $username->fetch(PDO::FETCH_OBJ);
                  $username = $username->username;
                  $dishes = $db->prepare("select * from orderdishes where oid = ?");
                  $dishes->execute(array($row->oid));
                  $db = null;
                }
                catch (PDOException $ex){
                  die($ex->getMessage());
                }
                ?>
                <h2>Order by <a href='customerinfo.php?username=<?php echo $username; ?>'><?php echo $username; ?></a></h2>
                <h4>Order ID: <?php echo $row->oid ?></h4>
                <h4>Price: <?php echo $row->ototalprice ?> BD</h4>
                <h4>Time: <?php echo $row->otime ?></h4>
                <hr/>
                <?php
                while ($dish = $dishes->fetch(PDO::FETCH_OBJ)) {
                  try{
                    require("scripts/connection.php");
                    $dishDetail = $db->prepare("select * from dish where did = ?");
                    $dishDetail->execute(array($dish->did));
                    $dishDetail = $dishDetail->fetch(PDO::FETCH_OBJ);
                  }
                  catch (PDOException $ex){
                    die($ex->getMessage());
                  }
                  ?>
                  <div class='row dish'>
                      <div class="col-md-2">
                        <img src='img/dish/<?php echo $dishDetail->Image; ?>' width="150" height="150"/>
                      </div>
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-8">
                        <h3><?php echo $dishDetail->DishName; ?></h3>
                        <h3><?php echo $dish->qty." × ".$dishDetail->Price ." BD"; ?></h3>
                        <h3><?php echo "Total ".$dish->qty * $dishDetail->Price ." BD";?></h3>
                      </div>
                  </div>
                  <hr/>
                  <?php
                }
              }
            }
          ?>
        </div>
      </div>
      <br/>

    </div>
    <!-- Footer -->
    <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Your Website 2018</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  </body>
</html>
