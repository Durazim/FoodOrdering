<?php
session_start();
if(isset($_SESSION['isAdmin']))
{
  header('location:adminIndex.php');
  die();
}
if(isset($_SESSION['login']))
{
    header('location:index.php');
    die();
}

  extract($_POST);
  if(isset($uname) && isset($password))
  {
    if(trim($uname) == '' || trim($password) == '')
    {
      $miss = "<p class = '' style='color : red;'>Missed Information</p>";
    }
    else if($uname != 'admin' || $password != '12345')
        {
          $error = "<p class = '' style='color : red;'>Invalid username or password</p>";
        }
        else 
        {
          $_SESSION['isAdmin'] = 'yes';
          header('location:adminIndex.php');
        }
  }
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - Admin Login</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

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
        <li class="breadcrumb-item active">Admin Login</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				  <div class="col-lg-8 mb-4">
				    <h3>Admin Login</h3>
						<div id="success">
							<?php
								if (isset($miss))
									echo $miss;
								if (isset($error))
									echo $error;
							?>
						</div>
				    <form name="sentMessage" id="loginForm" method="post" enctype="multipart/form-data">
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Username</label>
				          <input type="text" class="form-control" id="name" name='uname' required data-validation-required-message="Enter your username.">
				          <p class="help-block"></p>
				        </div>
				      </div>
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Password</label>
				          <input type="password" name='password' class="form-control" id="phone" required data-validation-required-message="Enter your password.">
				        </div>
				      </div>
				      <!-- For success/fail messages -->
				      <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Login"/>
							<br
				    </form>
				  </div>

	  </div>
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
