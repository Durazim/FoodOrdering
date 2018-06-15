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
	extract($_POST);
    $flag = true;
    $Image = '$dish.png';
	$exist = '';
	try{
		require('scripts/connection.php');
		$types = $db->prepare("select * from restaurantstypes where rid = ?");
		$types->execute(array($arr[2]));
		$db = null;
	}
	catch(PDOException $ex){

	}
	if(isset($s1)){
		if(trim($dname) == "" || trim($dprice) == "" || trim($desc) == "" || trim($type) == "")
        {
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else
        {
            $patternDN = "/^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i";
            $patternPC = "/^[\d]{1,5}(\.[\d]{0,3})?$/i";
            $patternTP = "/^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i";
            $patternDC = "/^([\s\w\.\-\+]{2,14})+$/i";

            if (!preg_match($patternDN,$dname))
                $flag = false;

            if (!preg_match($patternPC,$dprice))
                $flag = false;

            if (!preg_match($patternTP,$type))
                $flag = false;

            if (!preg_match($patternDC,$Desc))
                $flag = false;

            try
            {
                require('scripts/connection.php');
                $sql = "select * from dish where rid=? and DishName=?";
                $stmt = $db->prepare($sql);
                $stmt->execute(array($arr[2],$dname));
                if($stmt->rowCount() != 0)
                {
                  $exist = "<p class = '' style='color : red;'>Already exist dish</p>";
                  $flag = false;
                }

                if (($_FILES["Image"]["size"] < 1048576))
                {
                  if ($_FILES["Image"]["error"] > 0)
                  {
                    $Image = 'dish.png';
                  }
                  else
                  {
                    $tempArr = explode('.',$_FILES["Image"]["name"]);
                    $_FILES["Image"]["name"] = $arr[2] . '-' . $dname . '.' . $tempArr[1];
                    move_uploaded_file($_FILES["Image"]["tmp_name"], "img/dish/" . $_FILES["Image"]["name"]);
                    $Image = $_FILES["Image"]["name"];
                   }
                }
                else
                {
                  $Image = '$dish.png';
                }

                if($flag)
                {
                  $sql = "INSERT INTO dish VALUES(?,?,?,?,?,?,?,?)";
                  $stmt = $db->prepare($sql);
                  $stmt->execute(array($arr[2],null,$dname,$dprice,$desc,$type,$Image,0));
                  header("location:restaurantDishes.php");
                }
                $db = null;
            }
            catch(PDOException $ex)
            {
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

    <title>Food Ordering - Add Dish</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

    <script language='javascript'>
            var dcF = false;
            var tpF = false;
            var pcF = false;
            var dnF = false;

            function checkDN(dn)
            {
                var DNExp = /^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i;
                if (DNExp.test(dn))
                {
                    document.getElementById('dnmsg').innerHTML = "";
                    dnF = true;
                }
                else
                {
                    document.getElementById('dnmsg').innerHTML = "Invalid Name";
                    document.getElementById('dnmsg').style = "color:red";
                    dnF = false;
                }
            }

            function checkPC(price)
            {
                var PCExp = /^[\d]{1,5}(\.[\d]{0,3})?$/i;
                if (PCExp.test(price))
                {
                    document.getElementById('pcmsg').innerHTML = "";
                    pcF = true;
                }
                else
                {
                    document.getElementById('pcmsg').innerHTML = "Invalid value";
                    document.getElementById('pcmsg').style = "color:red";
                    pcF = false;
                }
            }

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

            function checkDC(desc)
            {
                var DCExp = /^([\s\w\.\-\+]{2,14})+$/i;
                if (DCExp.test(desc))
                {
                    document.getElementById('dcmsg').innerHTML = "";
                    dcF = true;
                }
                else
                {
                    document.getElementById('dcmsg').innerHTML = "Invalid Value";
                    document.getElementById('dcmsg').style = "color:red";
                    dcF = false;
                }
            }

            function validateMyInputs()
            {
                document.forms[0].JSF.value = 'true';
                return (dcF && tpF && pcF && dnF);
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
          <a href="restaurantDishes.php">Dishes</a>
        </li>
        <li class="breadcrumb-item active">Add new dish</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
				<br/>
				  <div class="col-lg-8 mb-4">
				    <h3>Add New Dish</h3>
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
				          <label>Dish Name</label>
				          <input type="text" class="form-control" id="dname" name='dname' required data-validation-required-message="Enter name of dish." onKeyUp='checkDN(this.value)' /> <span id='dnmsg'> </span>
				          <p class="help-block"></p>
				        </div>
				      </div>

				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Price</label>
				          <input type="text" class="form-control" id="dprice" name='dprice' required data-validation-required-message="Enter price of dish." onKeyUp='checkPC(this.value)' /> <span id='pcmsg'> </span>
				          <p class="help-block"></p>
				        </div>
				      </div>
							<div class="form-group">
						    <label for="exampleFormControlSelect1">Type</label>
						    <input type="text" class="form-control" id="type" name='type' required data-validation-required-message="Enter type of dish." onKeyUp='checkTP(this.value)' /> <span id='tpmsg'> </span>
						  </div>
				      <div class="control-group form-group">
				        <div class="controls">
				          <label>Description</label>
                            <textarea class="form-control" rows="5" id="desc" name='desc' onKeyUp='checkDC(this.value)'></textarea><span id='dcmsg'> </span>
				          <p class="help-block"></p>
				        </div>
				      </div>
				      <div class="control-group form-group">
                        <div class="controls">
                            <label>Image</label>
                            <input type="file" class="form-control" name='Image' <?php if(isset($Image)) echo 'value='.$Image;?>>
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
