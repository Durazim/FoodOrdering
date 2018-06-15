<?php
  require('loginTest.php');
  if(isset($arr)){
    if ($arr[0] == 'restaurant') {
      header('location:index.php');
      die();
    }
  }
    extract($_POST);


  try {
    if(isset($_SESSION['order'])){
      require('scripts/connection.php');
      $restInformation = $db->prepare("select * from restaurants where rid = ?");
      $restInformation->execute(array($_SESSION['order']['rid']));
      $restInformation = $restInformation->fetch(PDO::FETCH_OBJ);
      $sql = "select * from dish where rid = ? and DID IN(";
      $i = 1;
      foreach ($_SESSION['order']['dishes'] as $did => $qty)
      {
        $sql .= $did;
        if($i != count($_SESSION['order']['dishes'])){
          $sql .= ',';
        }
        $i++;
      }
      $sql .= ')';
      $dishesInformation = $db->prepare($sql);
      $dishesInformation->execute(array($_SESSION['order']['rid']));
    }
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

    <title>Food Ordering - Cart</title>

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
        <li class="breadcrumb-item active">Cart</li>
      </ol>
      <div class='row'>
        <div class="col-md-12">
          <?php
            if(isset($_SESSION['order'])){
              ?>
              <h3>My order from <a href='restaurant.php?rid=<?php echo $restInformation->rid?>&menu=yes'><?php echo $restInformation->restaurantname; ?> restaurant</a></h3>
              <hr/>
              <?php
                $total = 0;
                while($row = $dishesInformation->fetch(PDO::FETCH_OBJ)){
                  ?>
                  <div class='row dish'>
                      <div class="col-md-2">
                        <img src='img/dish/<?php echo $row->Image; ?>' width="150" height="150"/>
                      </div>
                      <div class="col-md-1">
                      </div>
                      <div class="col-md-8">
                        <h3><?php echo $row->DishName; ?></h3>
                        <h3><?php echo $_SESSION['order']['dishes'][$row->DID]." × ".$row->Price ." BD"; ?></h3>
                        <h3><?php echo "Total ".$_SESSION['order']['dishes'][$row->DID] * $row->Price ." BD";
                        $total += $_SESSION['order']['dishes'][$row->DID] * $row->Price;?></h3>
                      </div>
                      <div class="col-md-1">
                        <a href='scripts/removeDishFromOrder.php?did=<?php echo $row->DID; ?>'><button type="button" class="btn btn-danger btn-sm">×</button></a>
                      </div>
                  </div>
                  <hr/>
                  <?php
                }
                echo "<h4>Total price is <span style='color:red'>$total</span> BD and <span style='color:red'>0.500</span> BD for delivery charge</h4>";
              ?>
              <a href='scripts/addOrder.php'><button type="button" class="btn btn-primary btn-sm">Complete the order</button></a>
              <a href='scripts/emptyOrder.php'><button type="button" class="btn btn-primary btn-sm">Empty the order</button></a>
             <br/>
              <?php
            }
            else {
              ?>
              <h3>No dishes are selected</h3>
              <a href='restaurants.php'><h4>Go to select dishes</h4></a>
              <?php
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
