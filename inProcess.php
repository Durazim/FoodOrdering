
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
  extract($_POST);
  if(isset($s1)){
    try
    {
        require('scripts/connection.php');
        $orders = $db->prepare("update orderslist set ostatus = ? where oid = ?");
        $orders->execute(array($state, $oid));
    }
    catch(PDOException $ex){
      die($ex->getMessage());
    }
  }
    $state=array('acknowledge','in proccess','in the way','complete');

    try
    {
        require('scripts/connection.php');
        $orders = $db->prepare("select * from orderslist where rid = ? and ostatus <> ? order by oid desc");
        $orders->execute(array($arr[2],"complete"));

        while ($rs = $orders->fetch(PDO::FETCH_OBJ))
        {
            $r=$db->query("select username from customer where CID=$rs->cid");
            $rr=$r->fetch(PDO::FETCH_OBJ);
            $allorder[$rs->oid][]=$rr->username;
            unset($dishs);
            $allorder[$rs->oid][]=$rs;
            $dish = $db->prepare("select DishName,qty,Image,Price from orderdishes,dish where oid = ? and rid = ? and dish.DID=orderdishes.did");
            $dr[]=$dish->execute(array($rs->oid,$rs->rid));
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

    <title>Food Ordering - Orders in Process</title>

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
        <li class="breadcrumb-item active">Home</li>
      </ol>
      <div class='row'>
        <div class="col-md-12">
          <?php
            if(isset($allorder))
            {
                $i=1;
                foreach($allorder as $k => $v)
                {
                    ?>
                    <h2>Order #<?php echo $i++; ?> from <span style='color:blue'><?php echo $v[0]; ?> username</span></h2>
                    <h4>Order ID: <?php echo $k ?></h4>
                    <h4>Price: <?php echo $v[1]->ototalprice ?> BD</h4>
                    <h4>Time: <?php echo $v[1]->otime ?></h4>
                    <h4>State:
                      <form method="post">
                        <select name='state' class="form-control">
                        <?php
                            foreach($state as $t)
                            {
                                $c="";
                                if ($v[1]->ostatus==$t)
                                    $c="selected";
                                echo "<option $c>$t</option>";
                            }
                        ?>
                        </select>
                        <br/>
                        <?php
                          try
                          {
                              require('scripts/connection.php');
                              $branches = $db->prepare("select * from branch where rid = ?");
                              $branches->execute(array($arr[2]));
                          }
                          catch(PDOException $ex){
                            die($ex->getMessage());
                          }
                          ?>
                        <input type="hidden" name='oid' value="<?php echo $k; ?>"/>
                        <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Update State"/>
                      </form>
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
              <h3>No current orders right now</h3>
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
