<?php
	require('loginTest.php');
	if(!isset($arr)){
		header('location:login.php');
		die();
	}
  if($arr[0] == 'customer'){
    header('location:index.php');
		die();
  }
  extract($_GET);
  try {
    require('scripts/connection.php');
    $sql = "select * from branch where bid = ? and rid = ?";
    $stmt1 = $db->prepare($sql);
    $stmt1->execute(array($bid,$arr[2]));
    $result = $stmt1->fetch(PDO::FETCH_OBJ);
    $db = null;
  }
  catch (PDOException $ex) {
    die($ex->getMessage());
  }
	extract($_POST);
  $flag = true;
	if(isset($s1)){
    if(trim($location) == ""){
			$location = $result->location;
		}
    if(trim($phone) == ""){
      $phone = $result->phone;
    }
    if(trim($openTime) == ""){
      $openTime = $result->openTime;
    }
    if(trim($closeTime) == ""){
      $closeTime = $result->closeTime;
    }

            $patternLC = "/^([\s\w]{2,14})+$/i";
            $patternPH = "/^((00|\+)973)?\d{8}$/i";

            if (!preg_match($patternLC,$location))
                $flag = false;

            if (!preg_match($patternPH,$phone))
                $flag = false;


      try{
        require('scripts/connection.php');
        $sql = "select * from branch where rid=? and location=? and phone=? and openTime=? and closeTime=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($arr[2],$phone,$location,$openTime,$closeTime));
        if($stmt->rowCount() != 0){
          $exist = "<p class = '' style='color : red;'>Already exist branch</p>";
          $flag = false;
        }
        if($flag){
          $sql = "UPDATE branch SET location=?,phone=?,openTime=?,closeTime=? where bid = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($location,$phone,$openTime,$closeTime,$bid));
					header("location:../newT/profile.php");
        }
        $db = null;
			}
			catch(PDOException $ex){
				echo $ex->getMessage();
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

    <title>Food Ordering - Update Branch</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

	<!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script language='javascript'>
            var lcF = false;
            var phF = false;

            function checkLC(location)
            {
                var LCExp = /^([\s\w]{2,14})+$/i;
                if (LCExp.test(location))
                {
                    document.getElementById('lcmsg').innerHTML = "Valid Location Name";
                    document.getElementById('lcmsg').style = "color:green";
                    var lcF = true;
                }
                else
                {
                    document.getElementById('lcmsg').innerHTML = "Invalid Location Name";
                    document.getElementById('lcmsg').style = "color:red";
                    var lcF = false;
                }
            }

            function checkPH(ph)
            {
                var PHExp = /^((00|\+)973)?\d{8}$/i;
                if (PHExp.test(ph))
                {
                    document.getElementById('phmsg').innerHTML = "Valid Phone Number";
                    document.getElementById('phmsg').style = "color:green";
                    var phF = true;
                }
                else
                {
                    document.getElementById('phmsg').innerHTML = "Invalid Phone Number";
                    document.getElementById('phmsg').style = "color:red";
                    var phF = false;
                }
            }

            function validateMyInputs()
            {
                document.forms[0].JSF.value = 'true';
                return (lcF && phF);
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
        <li class="breadcrumb-item active">Update branch</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				<br/>
				  <div class="col-lg-8 mb-4">
				    <h3>Update branch</h3>
						<div id="success">
							<?php
								if (isset($exist))
									echo $exist;
							?>
						</div>
				    <form name="sentMessage" id="addressForm" method="post" enctype="multipart/form-data" onsubmit="return validateMyInputs();">
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Location</label>
				          <input type="text" class="form-control" id="location" name='location' required data-validation-required-message="Enter location of branch." value = '<?php echo $result->location;?>' onKeyUp='checkLC(this.value)' /> <span id='lcmsg'> </span>
				          <p class="help-block"></p>
				        </div>
				      </div>
							<div class="control-group form-group">
 							 <div class="controls">
 								 <label>Phone</label>
 								 <input type="text" class="form-control" id="phone" name='phone' required data-validation-required-message="Enter branch phone number." value = '<?php echo $result->phone;?>' onKeyUp='checkPH(this.value)' /> <span id='phmsg'> </span>
 								 <p class="help-block"></p>
 							 </div>
 						 </div>
 						 <div class="control-group form-group">
 							 <div class="controls">
 								 <label>Open Time</label>
 								 <input type="time" class="form-control" id="openTime" name='openTime' required data-validation-required-message="Enter open time of branch." value = '<?php echo $result->openTime;?>'>
 								 <p class="help-block"></p>
 							 </div>
 						 </div>
							<div class="control-group form-group">
							 <div class="controls">
								 <label>Close Time</label>
								 <input type="time" class="form-control" id="closeTime" name='closeTime' required data-validation-required-message="Enter close time of branch." value = '<?php echo $result->closeTime;?>'>
								 <p class="help-block"></p>
							 </div>
						 </div>
                        <input type='hidden' name='JSF' value='false' />
						 <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Update"/>
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
