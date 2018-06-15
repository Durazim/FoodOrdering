
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

    try
    {
        require('scripts/connection.php');
        $orders = $db->prepare("select * from orderslist where cid = ? and ostatus <> ? ");
        $orders->execute(array($arr[2],"complete"));

        while ($rs = $orders->fetch(PDO::FETCH_OBJ))
        {
            $r=$db->query("select restaurantname from restaurants where rid=$rs->rid");
            $rr=$r->fetch(PDO::FETCH_OBJ);
            $allorder[$rs->oid][]=$rr->restaurantname;
            unset($dishs);
            $allorder[$rs->oid][]=$rs;
            $dish = $db->prepare("select DishName,qty,Image,Price from orderdishes,dish where oid = ? and rid = ? and dish.DID=orderdishes.did");
            $dr[]=$dish->execute(array($rs->oid,$rs->rid));
            $dishs = array();
            while($dr=$dish->fetch(PDO::FETCH_OBJ))
                $dishs[]=$dr;
            $allorder[$rs->oid][]=$dishs;

        }
    }
    catch (PDOException $ex)
    {
        die($ex->getMessage());
    }
//print_r($allorder);
?>


<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - My Orders</title>

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
        <li class="breadcrumb-item active">My Order</li>
      </ol>
      <div class='row'>
        <div class="col-md-12">
          <?php
            if(isset($allorder))
            {
                $c=1;
                foreach($allorder as $k => $v)
                {
                    ?>
                    <h2>Order #<?php echo $c++; ?> from <a href='restaurant.php?rid=<?php echo $v[1]->rid; ?>&menu=yes'><?php echo $v[0]; ?> restaurant</a></h2>
                    <h4>Order ID: <?php echo $k ?></h4>
                    <h4>Price: <?php echo $v[1]->ototalprice ?> BD</h4>
                    <h4>Time: <?php echo $v[1]->otime ?></h4>
                    <h4>State: <span style='color:red'><?php echo $v[1]->ostatus ?></span></h4>
                    <hr/>
                    <?php
                    foreach($v[2] as $row)
                    {
                      ?>
                      <div class='row dish'>
                          <div class="col-md-2">
                            <img src='img/dish/<?php echo $row->Image; ?>' width="150" height="150"/>
                          </div>
                          <div class="col-md-1">
                          </div>
                          <div class="col-md-8">
                            <h3><?php echo $row->DishName; ?></h3>
                            <h3><?php echo $row->qty." × ".$row->Price ." BD"; ?></h3>
                            <h3><?php echo "Total ".$row->qty * $row->Price ." BD";?></h3>
                          </div>
                      </div>
                      <hr/>
                      <?php
                    }
                    echo "<h5>+ 0.500 BD for delivery charge</h5>";
                  ?>
                 <hr/>
                 <br/>
                 <br/>
                  <?php
                }
            }
            else
            {
              ?>
              <h3>You do not have any Ordrer</h3>
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
