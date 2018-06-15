<?php
	require('loginTest.php');
	if(!isset($arr)){
		header('location:login.php');
		die();
	}
	extract($_POST);
  $flag = true;
	if(isset($s1)){
		if(trim($oldPass) == "" || trim($newPass) == "" || trim($confpass) == ""){
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else {
      if($newPass != $confpass){
        $confError = "<p class = ' ' style='color : red;'>Error password confirmation</p>";
        $flag = false;
      }

        $patternPASS = "/^[a-zA-Z0-9_.]{8,18}$/";

        if (!preg_match($patternPASS,$newPass))
                $flag = false;

      try{
        require('scripts/connection.php');
        if($arr[0] == 'customer'){
          $sql = "select * from customer where CID=?";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($arr[2]));
          $res = $stmt->fetch(PDO::FETCH_OBJ);
          if(md5($oldPass) != $res->password){
            $error = "<p class = ' ' style='color : red;'>Old password is not correct</p>";
            $flag = false;
          }
          if($flag){
            $sql = "UPDATE customer SET password = ? where CID = ?";
            $newPass = md5($newPass);
            $stmt = $db->prepare($sql);
            $stmt->execute(array($newPass,$arr[2]));
            header("location:../newT/profile.php");
          }
        }
        else {
          $sql = "select * from restaurants where rid=?";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($arr[2]));
          $res = $stmt->fetch(PDO::FETCH_OBJ);
          if(md5($oldPass) != $res->password){
            $error = "<p class = ' ' style='color : red;'>Old password is not correct</p>";
            $flag = false;
          }
          if($flag){
            $sql = "UPDATE restaurants SET password = ? where rid = ?";
            $newPass = md5($newPass);
            $stmt = $db->prepare($sql);
            $stmt->execute(array($newPass,$arr[2]));
            header("location:../newT/profile.php");
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

    <title>Food Ordering - Register</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">
        <script language='javascript'>
            var cpass = false;
            var passF1 = false;
            var passF2 = false;
            var passF3 = false;
            var passF4 = false;
            var passF5 = false;

            function checkPASS(pass)
            {
                var PASSExp1 = /^[a-zA-Z0-9_.]{8,18}$/;
                if (PASSExp1.test(pass))
                {
                    document.getElementById('passmsg1').innerHTML = "Character between 8 to 15<br/>";
                    document.getElementById('passmsg1').style = "color:green";
                    passF1 = true;
                }
                else
                {
                    document.getElementById('passmsg1').innerHTML = "Character between 8 to 15<br/>";
                    document.getElementById('passmsg1').style = "color:red";
                    passF1 = false;
                }

                var PASSExp2 = /[a-z]/;
                if (PASSExp2.test(pass))
                {
                    document.getElementById('passmsg2').innerHTML = "Small latter character<br/>";
                    document.getElementById('passmsg2').style = "color:green";
                    passF2 = true;
                }
                else
                {
                    document.getElementById('passmsg2').innerHTML = "Small latter character<br/>";
                    document.getElementById('passmsg2').style = "color:red";
                    passF2 = false;
                }

                var PASSExp3 = /[A-Z]/;
                if (PASSExp3.test(pass))
                {
                    document.getElementById('passmsg3').innerHTML = "Capital latter character<br/>";
                    document.getElementById('passmsg3').style = "color:green";
                    passF3 = true;
                }
                else
                {
                    document.getElementById('passmsg3').innerHTML = "Capital latter character<br/>";
                    document.getElementById('passmsg3').style = "color:red";
                    passF3 = false;
                }

                var PASSExp4 = /[0-9]/;
                if (PASSExp4.test(pass))
                {
                    document.getElementById('passmsg4').innerHTML = "At least one number<br/>";
                    document.getElementById('passmsg4').style = "color:green";
                    passF4 = true;
                }
                else
                {
                    document.getElementById('passmsg4').innerHTML = "At least one number<br/>";
                    document.getElementById('passmsg4').style = "color:red";
                    passF4 = false;
                }

                var PASSExp5 = /[_.]/;
                if (PASSExp5.test(pass))
                {
                    document.getElementById('passmsg5').innerHTML = "_ or . character<br/>";
                    document.getElementById('passmsg5').style = "color:green";
                    passF5 = true;
                }
                else
                {
                    document.getElementById('passmsg5').innerHTML = "_ or . character<br/>";
                    document.getElementById('passmsg5').style = "color:red";
                    passF5 = false;
                }
            }

            function checkCPASS(confpass)
            {
                if(confpass==document.getElementById('Pass').value)
                {
                    document.getElementById('cpassmsg').innerHTML = "Equal";                   document.getElementById('cpassmsg').style = "color:green";
                    cpass = true;
                }
                else
                {
                    document.getElementById('cpassmsg').innerHTML = " Not equal";
                    document.getElementById('cpassmsg').style = "color:red";
                    cpass = false;
                }
            }

            function validateMyInputs()
            {
                document.forms[0].JSF.value = 'true';
                return (passF1 && passF2 && passF3 && passF4 && passF5 && cpass);
            }
        </script>

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
        <li class="breadcrumb-item">
          <a href="profile.php">Profile</a>
        </li>
        <li class="breadcrumb-item active">Change password</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				<br/>
				  <div class="col-lg-8 mb-4">
				    <h3>Change password</h3>
						<div id="success">
							<?php
								if (isset($miss))
									echo $miss;
								if (isset($confError))
									echo $confError;
								if (isset($error))
									echo $error;
							?>
						</div>
				    <form name="sentMessage" id="addressForm" method="post" enctype="multipart/form-data" onSubmit="return validateMyInputs();">
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Old password</label>
				          <input type="password" class="form-control" id="oldPass" name='oldPass' required data-validation-required-message="Enter your old password.">
				          <p class="help-block"></p>
				        </div>
				      </div>
							<div class="control-group form-group">
 							 <div class="controls">
 								 <label>New password</label>
 								 <input type="password" class="form-control" id="newPass" name='newPass' required data-validation-required-message="Enter new password." onKeyUp='checkPASS(this.value)'/><span id='passmsg1'></span><span id='passmsg2'></span><span id='passmsg3'></span><span id='passmsg4'></span><span id='passmsg5'></span>
 								 <p class="help-block"></p>
 							 </div>
 						 </div>
 						 <div class="control-group form-group">
 							 <div class="controls">
 								 <label>Confirm new password</label>
 								 <input type="password" class="form-control" id="confpass" name='confpass' required data-validation-required-message="Confirm new password." onKeyUp='checkCPASS(this.value)'/><span id='cpassmsg'></span>
 								 <p class="help-block"></p>
 							 </div>
 						 </div>
 						 <input type='hidden' name='JSF' value='false' />
						 <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Change"/>
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
