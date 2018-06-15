<?php
	//customer or restaurant!!!
	require('loginTest.php');
	if(isset($arr)){
		header('location:index.php');
		die();
	}
	extract($_POST);
	if(isset($s1)){
		if(trim($email) == ""){
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else {
			try{
				require('scripts/connection.php');
				$sql = "select * from customer where email = ?";
				$stmt = $db->prepare($sql);
				$stmt->execute(array($email));
				$result = $stmt->fetch(PDO::FETCH_OBJ);
				if($result != null){
          $alphabet = 'abcdefghijklmnopqrstuvwxyz';
          $numbers = '0123456789';
          $newPass = DATE('his');
          for ($i = 0; $i < 3; $i++) {
            $newPass .= substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
          }
          for ($i = 0; $i < 3; $i++) {
            $newPass .= substr($numbers, rand(0, strlen($numbers) - 1), 1);
          }
          //send mail
          // the message
          $msg = "Hello $result->username\n Your new password is <span style='color:red;'>$newPass</span>\n You can change it from your profile after login\n";
          // use wordwrap() if lines are longer than 70 characters
          $msg = wordwrap($msg,70);
          // send email
          //mail($email,"New password",$msg);

          $sql = "UPDATE customer set password = ? where CID = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute(array(md5($newPass),$result->CID));
          //header('location:login.php');
				}
        else {
					$sql = "select * from restaurants where email = ?";
					$stmt = $db->prepare($sql);
					$stmt->execute(array($email));
					$result = $stmt->fetch(PDO::FETCH_OBJ);
					if($result != null){
						$alphabet = 'abcdefghijklmnopqrstuvwxyz';
	          $numbers = '0123456789';
	          $newPass = DATE('his');
	          for ($i = 0; $i < 3; $i++) {
	            $newPass .= substr($alphabet, rand(0, strlen($alphabet) - 1), 1);
	          }
	          for ($i = 0; $i < 3; $i++) {
	            $newPass .= substr($numbers, rand(0, strlen($numbers) - 1), 1);
	          }
	          //send mail
	          // the message
	          $msg = "Hello $result->username\n Your new password is $newPass\n You can change it from your profile after login\n";
	          // use wordwrap() if lines are longer than 70 characters
	          $msg = wordwrap($msg,70);
	          // send email
	          //mail($email,"New password",$msg);

	          $sql = "UPDATE restaurants set password = ? where rid = ?";
	          $stmt = $db->prepare($sql);
	          $stmt->execute(array(md5($newPass),$result->rid));
					}
          else {
						$error = "<p style='color : red;'>This is email is not registered</p>";
					}
        }
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
        <li class="breadcrumb-item active">Forgot password</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				  <div class="col-lg-8 mb-4">
				    <h3>Forgot password</h3>
						<div id="success">
							<?php
								if(isset($miss))
									echo $miss;
								if (isset($error))
									echo $error;
							 ?>
						</div>
				    <form name="sentMessage" id="loginForm" method="post" enctype="multipart/form-data">
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Email</label>
				          <input type="text" class="form-control" id="email" name='email' required data-validation-required-message="Enter your username.">
				          <p class="help-block"></p>
				        </div>
				      </div>
				      <!-- For success/fail messages -->
				      <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Submit"/>
							<br/>
              <br/>
              <p>The new password will be sended to your email</p>
							<?php
								if(isset($msg))
									echo $msg;
							 ?>
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
