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
	$exist = '';
	extract($_POST);
  $flag = true;
	if(isset($s1)){
		if(trim($type) == ""){
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else {

            $patternTP = "/^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i";

            if (!preg_match($patternTP,$type))
                $flag = false;

      try{
        require('scripts/connection.php');
        $sql = "select * from restaurantstypes where rid=? and type=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($arr[2],$type));
        if($stmt->rowCount() != 0){
          $exist = "<p class = '' style='color : red;'>Already exist type</p>";
          $flag = false;
        }
        if($flag){
          $sql = "INSERT INTO restaurantstypes VALUES(?,?,?)";
          $stmt = $db->prepare($sql);
          $stmt->execute(array(null,$arr[2],$type));
					header("location:../newT/profile.php");
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
            var tpF = false;

            function checkTP(type)
            {
                var TPExp = /^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i;
                if (TPExp.test(type))
                {
                    document.getElementById('tpmsg').innerHTML = "";
                    tpF = true;
                }
                else
                {
                    document.getElementById('tpmsg').innerHTML = "Invalid Name";
                    document.getElementById('tpmsg').style = "color:red";
                    tpF = false;
                }
            }

            function validateMyInputs()
            {
                document.forms[0].JSF.value = 'true';
                return tpF;
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
        <li class="breadcrumb-item active">Add new type</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				<br/>
				  <div class="col-lg-8 mb-4">
				    <h3>Add new type</h3>
						<div id="success">
							<?php
								if (isset($miss))
									echo $miss;
								if (isset($exist))
									echo $exist;
							?>
						</div>
				    <form name="sentMessage" id="addressForm" method="post" enctype="multipart/form-data" onsubmit="return validateMyInputs();">
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Type</label>
				          <input type="text" class="form-control" id="type" name='type' required data-validation-required-message="Enter type." onKeyUp='checkTP(this.value)' /> <span id='tpmsg'> </span>
				          <p class="help-block"></p>
				        </div>
				      </div>
                        <input type='hidden' name='JSF' value='false' />
						<input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Add"/>
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
