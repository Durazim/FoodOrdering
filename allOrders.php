
<?php
require('loginTest.php');
  if(isset($arr))
  {
    if ($arr[0] == 'restaurant')
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
      $sql = "select * from orderslist where cid = ? and ostatus = ? limit $p1,10";
      $orderpaging = $db->prepare("select * from orderslist where cid = ? and ostatus = ?");
      $stmt = $db->prepare($sql);
      if(isset($acknowledge)){
        $stmt->execute(array($arr[2],"acknowledge"));
        $orderpaging->execute(array($arr[2],"acknowledge"));
      }
      else if (isset($inproccess)){
        $stmt->execute(array($arr[2],"in proccess"));
        $orderpaging->execute(array($arr[2],"in proccess"));
      }
      else if (isset($intheway)){
        $stmt->execute(array($arr[2],"in the way"));
        $orderpaging->execute(array($arr[2],"in the way"));
      }
      else if (isset($complete)){
        $stmt->execute(array($arr[2],"complete"));
        $orderpaging->execute(array($arr[2],"complete"));
      }
      else {
        header("location:allOrders.php?acknowledge=yes&p=1");
        die();
      }
      $orderpaging = $orderpaging->rowCount();
      $orderpaging = ceil($orderpaging / 10);
  }
  catch (PDOException $ex)
  {
      die($ex->getMessage());
  }
if(isset($acknowledge)){
  $current = "acknowledge";
}
else if(isset($inproccess)){
  $current = "inproccess";
}
else if(isset($intheway)){
  $current = "intheway";
}
else if(isset($complete)){
  $current = "complete";
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
      <div class="row">
          <div class="col-md-3">
            <a href='allOrders.php?acknowledge=yes&p=1'><button type="button" class="btn btn-<?php if(isset($acknowledge)) echo 'danger'; else echo 'primary';?> btn-sm">Acknowledge</button></a>
          </div>
          <div class="col-md-3">
            <a href='allOrders.php?inproccess=yes&p=1'><button type="button" class="btn btn-<?php if(isset($inproccess)) echo 'danger'; else echo 'primary';?> btn-sm">In proccess</button></a>
          </div>
          <div class="col-md-3">
            <a href='allOrders.php?intheway=yes&p=1'><button type="button" class="btn btn-<?php if(isset($intheway)) echo 'danger'; else echo 'primary';?> btn-sm">In the way</button></a>
          </div>
          <div class="col-md-3">
            <a href='allOrders.php?complete=yes&p=1'><button type="button" class="btn btn-<?php if(isset($complete)) echo 'danger'; else echo 'primary';?> btn-sm">Complete</button></a>
          </div>
      </div>
      <hr/>
      <div class='row'>
        <div class="col-md-12">
          <?php
            if($stmt->rowCount() == 0){
              echo "<h3>No orders in this state</h3>";
            }
            else {
              $c = 1;
              while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                try {
                  require('scripts/connection.php');
                  $restName = $db->prepare("select restaurantname from restaurants where rid = ?");
                  $restName->execute(array($row->rid));
                  $restName = $restName->fetch(PDO::FETCH_OBJ);
                  $restName = $restName->restaurantname;
                  $db = null;
                }
                catch(PDOException $ex){
                  die($ex->getMessage());
                }
                ?>
                <div class="col-md-12 order" onclick="location.href='orderDetail.php?oid=<?php echo $row->oid;?>';" style="cursor:pointer;">
                  <h2>Order #<?php echo $c++; ?> from <a href='restaurant.php?rid=<?php echo $row->rid; ?>&menu=yes'><?php echo $restName; ?> restaurant</a></h2>
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
                          <li class="page-item <?php if($i == $p) echo 'active';?>"><a class="page-link" href="allOrders.php?<?php echo $current; ?>=yes&p=<?php echo $i;?>"><?php echo $i;?></a></li>
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
