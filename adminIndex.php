<?php
  session_start();
  if(!isset($_SESSION['isAdmin'])){
    header('location:admin.php');
  }
  $back = $_SERVER['REMOTE_ADDR'];
  if(isset($_SERVER['HTTP_REFERER'])){
    $back = $_SERVER['HTTP_REFERER'];
    $temp = explode('/',$back);
    if($temp[count($temp) - 1] == 'admin.php'){
    ?>
    <script>
      alert("<?php echo 'Hello admin'; ?>");
    </script>
    <?php
    }
  }
?>
<?php
	try {
		require('scripts/connection.php');
		$pending = $db->prepare("select * from restaurants where accepted = 'n' order by registerDate DESC limit 3");
		$pending->execute();
		$db = null ;
	}
	catch (PDOException $ex) {
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

    <title>Food Ordering - Home</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->

	<?php require('adminNav.php'); ?>


    <!-- Page Content -->
    <div class="container">

      <h1 class="my-4">Welcome to Online Food Ordering</h1>

      <!-- Marketing Icons Section -->
      <div class="row">
	  <?php
    if($pending->rowCount() == 0){
      echo "<div class='col-md-12'><h3>No waiting restaurants</h3><br/></div>";
    }
    else {
  	  while ($row = $pending->fetch(PDO::FETCH_OBJ)) {
  		$ID = $row->rid;
  		$RN = $row->restaurantname;
  		$EM = $row->email;
  		$PH = $row ->phone;
  		$IM = $row->profileImage;
  		?>
          <div class="col-lg-4 mb-4">
            <div class="card">
              <h4 class="card-header text-center"><?php echo $RN ; ?></h4>
              <div class="card-body">
                <p class="card-text">
  			  <img src='img/restaurants/<?php echo $IM ; ?>' align = 'center' height='200' width='200' /> <br/><br/>
  			  Phone : <?php echo $PH ; ?> <br/>
  			  Email : <?php echo $EM ; ?>
  			  </p>
              </div>
              <div class="card-footer" align='center'>
                <a href="../newT/adminAcception.php?rid=<?php echo $ID;?>" class="btn btn-primary">Accept</a>
              </div>
            </div>
          </div>
  	  <?php
        }
      }
    ?>
	  <div class='col-md-5'>
	  </div>
	  <div class='col-md-12'>
            <a href="../newT/adminPendingList.php" class="btn btn-primary">Show all</a>
      </div>
	  <div class='col-md-5'>
	  </div>
      </div> <br/>
    </div>
    <!-- /.container -->

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
