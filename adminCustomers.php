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

    <title>Food Ordering - Customers</title>

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
        <li class="breadcrumb-item active">Customers</li>
      </ol>
	</div>
	<div class='row'>
		<div class='col-md-3'>
		</div>
		<div class='col-md-6'>
			<?php
			try {
				require('scripts/connection.php');
				$sql = "select * from customer";
				$stmt = $db->prepare($sql);
				$stmt->execute();
			}
			catch (PDOException $ex) {
				die($ex->getMessage());
			}
			while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
			?>
				<div class='row'>
					<div class='col-md-3'>
						<br/>
						<img height=100 width=100 src='img/customers/<?php echo $row->profileImage ; ?>'/>
					</div>
					<div class="col-md-6">
						<h6 class="my-3"><?php echo "<u>ID </u>: ". $row->CID; ?></h6>
						<h6 class="my-3"><?php echo "<u>Name </u>: ".$row->firstName ." ".$row->lastName; ?></h6>
						<h6 class="my-3"><?php echo "<u>email </u>: ".$row->email; ?></h6>
						<h6 class="my-3"><?php echo "<u>phone </u>: ".$row->phone; ?></h6>
						<hr/>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	</div>
	<div class='col-md-3'>
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
