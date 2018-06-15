<?php
	require('loginTest.php');
	if(isset($arr)){
		header('location:index.php');
		die();
	}
	extract($_POST);
	if(isset($s1)){
		if(trim($uname) == "" || trim($password) == ""){
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else {
			$pass = md5($password);
			try{
				require('scripts/connection.php');
				$sql = "select * from customer where username = ?";
				$stmt = $db->prepare($sql);
				$stmt->execute(array($uname));
				$result = $stmt->fetch(PDO::FETCH_OBJ);
				if($result != null){
					if($result->password == $pass){
						$id = $result->CID;
						$_SESSION['login'] = array("customer",$uname,$id);
						if(isset($rememberme)){
							setcookie("login","customer#$uname#$id",time() + 3600 * 24 * 7);
						}
						header("location:index.php");
						die();
					}
				}
				else {
					$sql = "select * from restaurants where username = ? and accepted = ?";
					$stmt = $db->prepare($sql);
					$stmt->execute(array($uname,'y'));
					$result = $stmt->fetch(PDO::FETCH_OBJ);
					if($result != null){
						if($result->password == $pass){
							$_SESSION['login'] = array("restaurant",$uname,$result->rid);
							if(isset($rememberme)){
								setcookie("login","restaurant#$uname#$pass#$result->rid",time() + 3600 * 24 * 7);
							}
							header("location:index.php");
							die();
						}
					}
				}
				$error = "<p class = '' style='color : red;'>Invalid username or password</p>";
				$db = null;
			}
			catch(PDOException $ex){
				echo $ex->getMessage();
			}
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

    <title>Food Ordering - Login</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

		<!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
        <li class="breadcrumb-item active">Login</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				  <div class="col-lg-8 mb-4">
				    <h3>Login</h3>
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
							<br/>
							<div class="clearfix">
								<br/>
					      <label class="pull-left checkbox-inline"><input type="checkbox" name='rememberme'> Remember me</label>
					      <a href="forgotPass.php" class="pull-right">Forgot Password?</a>
					    </div>
				    </form>
						<p class="">Not register : <a href="register.php">Create an Account</a></p>
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
