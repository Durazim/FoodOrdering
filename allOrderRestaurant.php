
<?php
require('loginTest.php');
  if(isset($arr))
  {
    if ($arr[0] == 'customer')
    {
      header('location:index.php');
      die();
    }
  }
  extract($_GET);
  //paging orders
  if(!isset($p)){
    $p = 1;
  }
  if($p == '' || $p == 1){
    $p1 = 0;
  }
  else {
    $p1 = ($p * 1) - 1;
  }
  //end paging
  try
  {
      require('scripts/connection.php');
      $sql = "select * from orderslist where rid = ? and ostatus = ? limit $p1,10";
      $orderpaging = $db->prepare("select * from orderslist where rid = ? and ostatus = ?");
      $stmt = $db->prepare($sql);
      $stmt->execute(array($arr[2],"complete"));
      $orderpaging->execute(array($arr[2],"complete"));
      $orderpaging = $orderpaging->rowCount();
      $orderpaging = ceil($orderpaging / 10);
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

    <title>Food Ordering - All Orders</title>

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
      .order{
        padding: 10px;
      }
      .order:hover{
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
        <li class="breadcrumb-item active">All orders</li>
      </ol>
      <div class='row'>
        <div class="col-md-12">
          <?php
            if($stmt->rowCount() == 0){
              echo "<h3>No order history</h3>";
            }
            else {
              $c = 1;
              while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                try {
                  require('scripts/connection.php');
                  $username = $db->prepare("select username from customer where CID = ?");
                  $username->execute(array($row->cid));
                  $username = $username->fetch(PDO::FETCH_OBJ);
                  $username = $username->username;
                  $db = null;
                }
                catch(PDOException $ex){
                  die($ex->getMessage());
                }
                ?>
                <div class="col-md-12 order" onclick="location.href='orderDetailRestaurant.php?oid=<?php echo $row->oid;?>';" style="cursor:pointer;">
                  <h2>Order #<?php echo $c++; ?> by <a href='customerinfo.php?username=<?php echo $username; ?>'><?php echo $username; ?></a></h2>
                  <h4>Order ID: <?php echo $row->oid ?></h4>
                  <h4>Price: <?php echo $row->ototalprice ?> BD</h4>
                  <h4>Time: <?php echo $row->otime ?></h4>
                  <h4>State: <span style='color:red'><?php echo $row->ostatus ?></span></h4>
                </div>
                <hr/>
                <?php
              }
            }
          ?>
        </div>
        <div class='col-md-8'>
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php
              for($i = 1; $i <= $orderpaging; $i++){
                  ?>
                      <li class="page-item <?php if($i == $p) echo 'active';?>">
                          <li class="page-item <?php if($i == $p) echo 'active';?>"><a class="page-link" href="allOrderRestaurant.php?<?php echo $current; ?>=yes&p=<?php echo $i;?>"><?php echo $i;?></a></li>
                      </li>
            <?php } ?>
            </ul>
          </nav>
        </div>
      </div>

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
