<?php
	require('loginTest.php');
	if(isset($arr))
    {
		header('location:index.php');
		die();
	}
	$miss = '';
	$error = '';
    $confError = '';
    $genderError = '';
    $exist = '';
    $existEmail = '';
	$profile = 'profile.png';
	extract($_POST);
	extract($_GET);
    $flag = true;
	if(isset($s1))
    {
		if(trim($fname) == "" || trim($lname) == "" || trim($uname) == "" || trim($email) == "" || trim($phone) == "" ||trim($pass) == "" || trim($confpass) == "" || trim($gender) == "" || trim($dob) == "")
        {
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else
        {
            if($pass != $confpass)
            {
                $confError = "<p class = ' ' style='color : red;'>Error password confirmation</p>";
                $flag = false;
            }
            if($gender != 'Male' && $gender != 'Female')
            {
                $genderError =  "<p class = ' ' style='color : red;'>Error Gender</p>";
                $flag = false;
            }

      try
      {
        require('scripts/connection.php');
        $sql = "select * from customer where username=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($uname));
        if($stmt->rowCount() != 0)
        {
          $exist = "<p class = ' ' style='color : red;'>Already used username by customer</p>";
          $flag = false;
        }

        $sql = "select * from restaurants where username=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($uname));
        if($stmt->rowCount() != 0)
        {
          $exist = "<p class = ' ' style='color : red;'>Already used username by restaurant</p>";
          $flag = false;
        }
        $sql = "select * from customer where email=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        if($stmt->rowCount() != 0)
        {
          $existEmail = "<p class = ' ' style='color : red;'>Already used email by customer</p>";
          $flag = false;
        }
		$sql = "select * from restaurants where email=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        if($stmt->rowCount() != 0)
        {
          $existEmail = "<p class = ' ' style='color : red;'>Already used email by restaurant</p>";
          $flag = false;
        }
        $regDate = Date('d/m/Y');
        $hashpass = md5($pass);
        //type problem
        if (($_FILES["profile"]["size"] < 1024000))
        {
          if ($_FILES["profile"]["error"] > 0)
          {
            $profile = 'profile.png';
          }
          else
          {
                    $tempArr = explode('.',$_FILES["profile"]["name"]);
                $_FILES["profile"]["name"] = $uname . '.' . $tempArr[1];
              move_uploaded_file($_FILES["profile"]["tmp_name"], "img/customers/" . $_FILES["profile"]["name"]);
                    $profile = $_FILES["profile"]["name"];
           }
         }
        else
        {
          $profile = 'profile.png';
        }
        if($flag)
        {
          $sql = "INSERT INTO customer VALUES(?,?,?,?,?,?,?,?,?,NOW(),?)";
          $stmt = $db->prepare($sql);
          $stmt->execute(array(null,$fname,$lname,$uname,$email,$phone,$hashpass,$gender,$dob,$profile));
					$id = $db->lastInsertId();
          $_SESSION['login'] = array("customer",$uname,$id);
					header("location:index.php");
        }
        $db = null;
    }
			catch(PDOException $ex){
				echo $ex->getMessage();
			}
		}
	}
	else if(isset($s2)){
		$profile = 'logo.png';
		if(trim($uname) == "" || trim($rname) == "" || trim($phone) == "" || trim($email) == "" || trim($owname) == "" || trim($pass) == "" ||trim($confpass) == ""){
			$miss = "<p class = '' style='color : red;'>Missed Information</p>";
		}
		else {
      if($pass != $confpass){
        $confError = "<p class = ' ' style='color : red;'>Error password confirmation</p>";
        $flag = false;
      }
      try{
        require('scripts/connection.php');
				$sql = "select * from restaurants where restaurantname=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($rname));
        if($stmt->rowCount() != 0){
          $exist = "<p class = ' ' style='color : red;'>Already registered restaurant name</p>";
          $flag = false;
        }
				$sql = "select * from customer where username=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($uname));
        if($stmt->rowCount() != 0){
          $exist = "<p class = ' ' style='color : red;'>Already used username by customer</p>";
          $flag = false;
        }
				$sql = "select * from restaurants where username=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($uname));
        if($stmt->rowCount() != 0){
          $exist = "<p class = ' ' style='color : red;'>Already used username by restaurant</p>";
          $flag = false;
        }
        $sql = "select * from customer where email=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        if($stmt->rowCount() != 0){
          $existEmail = "<p class = ' ' style='color : red;'>Already used email by customer</p>";
          $flag = false;
        }
				$sql = "select * from restaurants where email=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($email));
        if($stmt->rowCount() != 0){
          $existEmail = "<p class = ' ' style='color : red;'>Already used email by restaurant</p>";
          $flag = false;
        }
        $regDate = Date('d/m/Y');
        $hashpass = md5($pass);
        //type problem
        if (($_FILES["profile"]["size"] < 1048576))
        {
          if ($_FILES["profile"]["error"] > 0)
          {
            $profile = 'logo.png';
          }
          else
          {
                    $tempArr = explode('.',$_FILES["profile"]["name"]);
                $_FILES["profile"]["name"] = $uname . '.' . $tempArr[1];
              move_uploaded_file($_FILES["profile"]["tmp_name"], "img/restaurants/" . $_FILES["profile"]["name"]);
                    $profile = $_FILES["profile"]["name"];
            }
          }
        else
        {
          $profile = 'logo.png';
        }
        if($flag){
					$pass = md5($pass);
          $sql = "INSERT INTO restaurants VALUES(?,?,?,?,?,?,?,?,?,NOW(),?)";
          $stmt = $db->prepare($sql);
          $stmt->execute(array(null,$uname,$rname,$pass,$owname,$phone,$email,0,'n',$profile));
					$id = $db->lastInsertId();
					header("location:waiting.php");
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

				<!-- Rating Star-->
		    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="scripts/Validation.js"></script>

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
                <li class="breadcrumb-item active">Register</li>
            </ol>
            <!-- Marketing Icons Section -->
            <?php
					if(isset($cust)){
						?>
            <div class="col-lg-8 mb-4">
                <h3>Register</h3>
                <div id="success">
                    <?php echo $miss; echo $error; echo $confError; echo $exist; echo $existEmail; ?>
                </div>
                <form name="sentMessage" id="registerForm" method="post" enctype="multipart/form-data" onSubmit="return validateMyInputs();">
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>First name</label>
                            <input type="text" class="form-control" id="fname" name='fname' required data-validation-required-message="Enter your first name." <?php if(isset($fname)) echo 'value='.$fname;?> onKeyUp='checkFN(this.value)' /> <span id='fnmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Last name</label>
                            <input type="text" class="form-control" id="lname" name='lname' required data-validation-required-message="Enter your last name." <?php if(isset($lname)) echo 'value='.$lname;?> onKeyUp='checkLN(this.value)' /> <span id='lnmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Username</label>
                            <input type="text" class="form-control" id="uname" name='uname' required data-validation-required-message="Enter your username." <?php if(isset($uname)) echo 'value='.$uname;?> onKeyUp='checkUN(this.value)' /> <span id='unmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Phone number</label>
                            <input type="phone" class="form-control" id="phone" name='phone' required data-validation-required-message="Enter your phone number." onKeyUp='checkPH(this.value)' <?php if(isset($phone)) echo 'value='.$phone;?>><span id='phmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Email</label>
                            <input type="email" class="form-control" id="email" name='email' required data-validation-required-message="Enter your email." <?php if(isset($email)) echo 'value='.$email;?> onKeyUp='checkEM(this.value)' /> <span id='emmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Password</label>
                            <input type="password" class="form-control" id="pass" name='pass' required data-validation-required-message="Enter password." <?php if(isset($pass)) echo 'value='.$pass;?> onKeyUp='checkPASS(this.value)'/><span id='passmsg1'></span><span id='passmsg2'></span><span id='passmsg3'></span><span id='passmsg4'></span><span id='passmsg5'></span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" id="confpass" name='confpass' required data-validation-required-message="Confirm password." <?php if(isset($confpass)) echo 'value='.$confpass;?> onKeyUp='checkCPASS(this.value)'/><span id='cpassmsg'></span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Gender</label>
                            <select name="gender" class="custom-select">
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Date Of Birth</label>
                            <input type="date" class="form-control" id="example-date-input" name='dob' required data-validation-required-message="Enter password." <?php if(isset($dob)) echo 'value='.$dob;?>>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Profile Image</label>
                            <input type="file" class="form-control" name='profile' <?php if(isset($profile)) echo 'value='.$profile;?>>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <!-- For success/fail messages -->
	            	<input type='hidden' name='JSF' value='false' />
                    <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Register" />
                    <br/>
                </form>
                <br/>
                <p class="">Already register : <a href="login.php">Login</a></p>
            </div>

        </div>
        <!--		</div>-->
        <?php
					}
					else if(isset($rest)){
						?>
            <div class="col-lg-8 mb-4">
                <h3>Register</h3>
                <div id="success">
                    <?php echo $miss; echo $error; echo $confError; echo $existEmail; echo $exist;?>
                </div>
                <form name="sentMessage" id="registerForm" method="post" enctype="multipart/form-data" onSubmit="return validateMyInputs();">
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Username</label>
                            <input type="text" class="form-control" id="uname" name='uname' required data-validation-required-message="Enter your username." <?php if(isset($uname)) echo 'value='.$uname;?> onKeyUp='checkUN(this.value)' /> <span id='unmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Restaurant name</label>
                            <input type="text" class="form-control" id="rname" name='rname' required data-validation-required-message="Enter your restaurant name." <?php if(isset($rname)) echo 'value='.$rname;?> onKeyUp='checkFN(this.value)' /> <span id='fnmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Owner name</label>
                            <input type="text" class="form-control" id="owname" name='owname' required data-validation-required-message="Enter your restaurant name." <?php if(isset($owname)) echo 'value='.$owname;?> onKeyUp='checkLN(this.value)' /> <span id='lnmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Phone</label>
                            <input type="text" class="form-control" id="phone" name='phone' required data-validation-required-message="Enter your phone number." onKeyUp='checkPH(this.value)' <?php if(isset($phone)) echo 'value='.$phone;?>><span id='phmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Email</label>
                            <input type="text" class="form-control" id="email" name='email' required data-validation-required-message="Enter your email." <?php if(isset($email)) echo 'value='.$email;?> onKeyUp='checkEM(this.value)' /> <span id='emmsg'> </span>
                            <p class="help-block"></p>
                        </div>
                    </div>
										<div class="control-group form-group">
                        <div class="controls">
                            <label>Password</label>
                            <input type="password" class="form-control" id="pass" name='pass' required data-validation-required-message="Enter password." <?php if(isset($pass)) echo 'value='.$pass;?> onKeyUp='checkPASS(this.value)'/><span id='passmsg1'></span><span id='passmsg2'></span><span id='passmsg3'></span><span id='passmsg4'></span><span id='passmsg5'></span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Confirm Password</label>
                            <input type="password" class="form-control" id="confpass" name='confpass' required data-validation-required-message="Confirm password." <?php if(isset($confpass)) echo 'value='.$confpass;?> onKeyUp='checkCPASS(this.value)'/><span id='cpassmsg'></span>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>Restaurant Logo</label>
                            <input type="file" class="form-control" name='profile' <?php if(isset($profile)) echo 'value='.$profile;?>>
                            <p class="help-block"></p>
                        </div>
                    </div>
                    <!-- For success/fail messages -->
	            	<input type='hidden' name='JSF' value='false' />
                    <input type="submit" class="btn btn-primary" name='s2' id="sendMessageButton" value="Register" />
                    <br/>
                </form>
                <br/>
                <p class="">Already register : <a href="login.php">Login</a></p>
            </div>

            <!--			</div>-->
            <!--		</div>-->
            <?php
					}
					else {?>
                <form method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <a href='register.php?cust'>
                                    <button type="button" class="btn btn-primary btn-sm">Customer Register</button>
                                </a>
                        </div>
                        <div class="col-md-6">
                            <a href='register.php?rest'>
                                    <button type="button" class="btn btn-primary btn-sm">Restaurant Register</button>
                                </a>
                        </div>
                    </div>
                </form>
                <?php }?>
                <br/>

                </div>
                <!-- /.container -->

                <!-- Footer -->
                <footer class="py-5 bg-dark">
                    <div class="container">
                        <p class="m-0   text-white">Copyright &copy; Your Website 2018</p>
                    </div>
                    <!-- /.container -->
                </footer>

                <!-- Bootstrap core JavaScript -->
                <script src="vendor/jquery/jquery.min.js"></script>
                <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>
