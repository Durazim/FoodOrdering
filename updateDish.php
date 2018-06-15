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
    $flag = true;
  extract($_GET);
  try {
    require('scripts/connection.php');
    $sql = "select * from dish where DID = ? and rid = ?";
    $stmt1 = $db->prepare($sql);
    $stmt1->execute(array($did,$arr[2]));
    $result = $stmt1->fetch(PDO::FETCH_OBJ);
		$types = $db->prepare("select * from restaurantstypes where rid = ?");
		$types->execute(array($arr[2]));
    $db = null;
  }
  catch (PDOException $ex)
  {
    die($ex->getMessage());
  }
	extract($_POST);
  $flag = true;
	if(isset($s1)){
    if(trim($dname) == ""){
			$dname = $result->DishName;
		}
    if(trim($dprice) == ""){
      $dprice = $result->Price;
    }
    if(trim($type) == ""){
      $dprice = $result->Type;
    }
    if(trim($Desc) == ""){
      $Desc = $result->Description;
    }
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


      try{
        require('scripts/connection.php');
        $sql = "select * from dish where rid=? and DishName=? and Price=? and Description=? and Image=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($arr[2],$dname,$dprice,$Desc,$Image));
        if($stmt->rowCount() != 0)
        {
          $exist = "<p class = '' style='color : red;'>Already exist dish</p>";
          $flag = false;
        }

        if (($_FILES["Image"]["size"] < 1048576))
        {
          if ($_FILES["Image"]["error"] > 0)
          {
            $Image = $result->Image;
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
            $Image = $result->Image;
        }

        if($flag){
          $sql = "UPDATE dish SET DishName=?,Price=?,Description=?,Image=?,Type=? where DID = ?";
          $stmt = $db->prepare($sql);
          $stmt->execute(array($dname,$dprice,$Desc,$Image,$type,$did));
					header("location:../newT/restaurantDishes.php");
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

    <title>Food Ordering - Update Dish</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

	<!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

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
        <li class="breadcrumb-item active">Update dish</li>
      </ol>
      <!-- Marketing Icons Section -->
      <div class="row">
        <br/>
          <div class="col-lg-8 mb-4">
            <h3>Update Dish</h3>
                <div id="success">
                    <?php
                        if (isset($exist))
                            echo $exist;
                    ?>
                </div>
            <form name="sentMessage" id="addressForm" method="post" enctype="multipart/form-data"  onsubmit="return validateMyInputs();">
              <img class="img-fluid" src='img/dish/<?php echo $result->Image;?>' alt="" width="300" height="300"><br/>
                <br/>
                <input type='file' name='Image' width="100%"/>
                <br/>
                <br/>
              <div class="control-group form-group">
                <div class="controls">
                  <label>Dish Name</label>
                  <input type="text" class="form-control" id="dname" name='dname' required data-validation-required-message="Enter name of dish." value = '<?php echo $result->DishName; ?>'  onKeyUp='checkDN(this.value)' /> <span id='dnmsg'>
                  <p class="help-block"></p>
                </div>
              </div>
                <div class="control-group form-group">
                 <div class="controls">
                     <label>Price</label>
                     <input type="text" class="form-control" id="dprice" name='dprice' required data-validation-required-message="Enter price of dish." value = '<?php echo $result->Price;?>' onKeyUp='checkPC(this.value)' /> <span id='pcmsg'> </span>
                     <p class="help-block"></p>
                 </div>
                 </div>
                  <div class="control-group form-group">
                 <div class="controls">
                     <label>Type</label>
                     <input type="text" class="form-control" id="type" name='type' required data-validation-required-message="Enter type of dish." value = '<?php echo $result->Type;?>' onKeyUp='checkTP(this.value)' /> <span id='tpmsg'> </span>
                     <p class="help-block"></p>
                 </div>
                 </div>
                <div class="control-group form-group">
                 <div class="controls">
                     <label>Description</label>
                     <input type="text" class="form-control" id="Desc" name='Desc' required data-validation-required-message="Enter description of dish." value = '<?php echo $result->Description;?>' onKeyUp='checkDC(this.value)'><span id='dcmsg'> </span>
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
