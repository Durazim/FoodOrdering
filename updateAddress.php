<?php
	require('loginTest.php');
	if(!isset($arr)){
		header('location:login.php');
		die();
	}
  if($arr[0] == 'restaurant'){
		header('location:index.php');
		die();
  }
  extract($_GET);
  try {
    require('scripts/connection.php');
    $sql = "select * from customeraddress where AID = ? and CID = ?";
    $stmt1 = $db->prepare($sql);
    $stmt1->execute(array($aid,$arr[2]));
    $result = $stmt1->fetch(PDO::FETCH_OBJ);
    $db = null;
  }
  catch (PDOException $ex) {
    die($ex->getMessage());
  }
	extract($_POST);
  $flag = true;

	if(isset($s1)){
		if(trim($city) == ""){
			$city = $result->City;
		}
    if(trim($building) == ""){
      $building = $result->Building;
    }
    if(trim($block) == ""){
      $block = $result->Block;
    }
    if(trim($road) == ""){
      $road = $result->Road;
    }

            $patternCT = "/^([\w\s]{2,14})+$/i";
            $patternBD = "/^([\w\s]{2,14})+$/i";
            $patternBK = "/^([\w\s]{2,14})+$/i";
            $patternRD = "/^([\w\s]{2,14})+$/i";

            if (!preg_match($patternCT,$city))
                $flag = false;

            if (!preg_match($patternBD,$building))
                $flag = false;

            if (!preg_match($patternBK,$block))
                $flag = false;

            if (!preg_match($patternRD,$road))
                $flag = false;


      try{
        require('scripts/connection.php');
        $sql = "select * from customeraddress where CID=? and City=? and Building=? and Block=? and Road=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($arr[2],$city,$building,$block,$road));
        if($stmt->rowCount() != 0){
          $exist = "<p class = '' style='color : red;'>Already exist address</p>";
          $flag = false;
        }
        if($flag){
          $sql = "UPDATE customeraddress SET City = ? , Building = ? , Block = ? , Road = ? where AID = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($city,$building,$block,$road,$aid));
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

    <title>Food Ordering - Register</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

	<!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script language='javascript'>
        var ctF = false;
        var bdF = false;
        var bkF = false;
        var rdF = false;

        function checkCT(ct)
        {
            var CTExp = /^([\w\s]{2,14})+$/i;
            if (CTExp.test(ct))
            {
                document.getElementById('ctmsg').innerHTML = "";
                ctF = true;
            }
            else
            {
                document.getElementById('ctmsg').innerHTML = "Invalid City Name";
                document.getElementById('ctmsg').style = "color:red";
                ctF = false;
            }
        }

        function checkBD(bd)
        {
            var BDExp = /^([\w\s]{2,14})+$/i;
            if (BDExp.test(bd))
            {
                document.getElementById('bdmsg').innerHTML = "";
                bdF = true;
            }
            else
            {
                document.getElementById('bdmsg').innerHTML = "Invalid Bulding Name";
                document.getElementById('bdmsg').style = "color:red";
                bdF = false;
            }
        }

        function checkBK(bk)
        {
            var BKExp = /^([\w\s]{2,14})+$/i;
            if (BKExp.test(bk))
            {
                document.getElementById('bkmsg').innerHTML = "";
                bkF = true;
            }
            else
            {
                document.getElementById('bkmsg').innerHTML = "Invalid Block Name";
                document.getElementById('bkmsg').style = "color:red";
                bkF = false;
            }
        }

        function checkRD(rd)
        {
            var RDExp = /^([\w\s]{2,14})+$/i;
            if (RDExp.test(rd))
            {
                document.getElementById('rdmsg').innerHTML = "";
                rdF = true;
            }
            else
            {
                document.getElementById('rdmsg').innerHTML = "Invalid Road Name";
                document.getElementById('rdmsg').style = "color:red";
                rdF = false;
            }
        }

        function validateMyInputs()
        {
            document.forms[0].JSF.value = 'true';
            return (ctF && bdF && bkF && rdF);
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
        <li class="breadcrumb-item active">Update address</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				<br/>
				  <div class="col-lg-8 mb-4">
				    <h3>Update address address</h3>
						<div id="success">
							<?php
								if (isset($exist))
									echo $exist;
							?>
						</div>
				    <form name="sentMessage" id="addressForm" method="post" enctype="multipart/form-data" onsubmit="return validateMyInputs();">
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>City</label>
				          <input type="text" class="form-control" id="city" name='city' required data-validation-required-message="Enter your first name." value= '<?php echo $result->City;?>' onKeyUp='checkCT(this.value)' /><span id='ctmsg'></span>
				          <p class="help-block"></p>
				        </div>
				      </div>
							<div class="control-group form-group">
 							 <div class="controls">
 								 <label>Building</label>
 								 <input type="text" class="form-control" id="building" name='building' required data-validation-required-message="Enter your building number." value= '<?php echo $result->Building;?>' onKeyUp='checkBD(this.value)' /><span id='bdmsg'></span>
 								 <p class="help-block"></p>
 							 </div>
 						 </div>
 						 <div class="control-group form-group">
 							 <div class="controls">
 								 <label>Block</label>
 								 <input type="text" class="form-control" id="block" name='block' required data-validation-required-message="Enter your block number." value= '<?php echo $result->Block;?>' onKeyUp='checkBK(this.value)' /><span id='bkmsg'></span>
 								 <p class="help-block"></p>
 							 </div>
 						 </div>
							<div class="control-group form-group">
							 <div class="controls">
								 <label>Road</label>
								 <input type="text" class="form-control" id="road" name='road' required data-validation-required-message="Enter your road number." value= '<?php echo $result->Road;?>' onKeyUp='checkRD(this.value)' /><span id='rdmsg'></span>
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
