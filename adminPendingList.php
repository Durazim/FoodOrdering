<?php
session_start();
  if(!isset($_SESSION['isAdmin'])){
    header('location:admin.php');
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - Restaurant</title>

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
      .restaurant{
        padding: 10px;
      }
      .restaurant:hover{
        background-color: #f6f6f6;
      }
    </style>
  </head>

  <body>

    <!-- Navigation -->

	<?php require('adminNav.php'); ?>


    <!-- Page Content -->
    <div class="container">

      <br/>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="adminIndex.php">Home</a>
        </li>
        <li class="breadcrumb-item active">Restaurants</li>
      </ol>
	</div>
	<div class='row'>
		<div class='col-md-3'>
		</div>
		<div class='col-md-6'>
			<?php
			try {
				require('scripts/connection.php');
				$pending = $db->prepare("select * from restaurants where accepted = 'n' order by registerDate DESC");
				$pending->execute();
				$db = null ;
			}
			catch (PDOException $ex) {
				die($ex->getMessage());
			}
      if($pending->rowCount() == 0){
        echo "<h3>No waiting restaurants</h3>";
      }
      else {
  			while ($row = $pending->fetch(PDO::FETCH_OBJ)) {
  			?>
  				<div class='row'>
  					<div class='col-md-3'>
  						<br/>
  						<img height=100 width=100 src='img/restaurants/<?php echo $row->profileImage ; ?>'/>
  					</div>
  					<div class="col-md-6">
  						<h6 class="my-3"><?php echo "<b>Restaurant name </b>: ".$row->restaurantname; ?></h6>
  						<h6 class="my-3"><?php echo "<b>email </b>: ".$row->email; ?></h6>
  						<h6 class="my-3"><?php echo "<b>phone </b>: ".$row->phone; ?></h6>
  						<h6 class="my-3"><?php echo "<b>Register date </b>: ". $row->registerDate; ?></h6>
  						<hr/>
  					</div>
  					<div class='col-md-3'>
  							<br/><br/><br/>
  							<div class="input-group stylish-input-group">
  								<a href='adminAcception.php?rid=<?php echo $row->rid;?>'><button type="button" class="btn btn-danger btn-sm">Accept</button></a>
  							</div>
  						</div>
  					</div>
  			<?php
  			}
      }
			?>
		</div>
    <br/>
	</div>
  <br/>
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
