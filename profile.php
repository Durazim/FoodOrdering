<?php
  require('loginTest.php');
  if(!isset($arr)){
    header('location:login.php');
    die();
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <!-- validation -->
    <script language='javascript' src="scripts/Validation.js" ></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script language='javascript' src="scripts/Validation.js" ></script>

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
        <li class="breadcrumb-item active">Profile</li>
      </ol>
      <?php
        if($arr[0] == 'customer'){
          require ('profile/customerProfile.php');
        }
        else if($arr[0] == 'restaurant'){
          require ('profile/restaurantProfile.php');
        }
      ?>
      <br/>
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
