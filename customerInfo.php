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
  if(!isset($username)){
    header("location:restaurantComments.php");
    die();
  }
  if(trim($username) == ''){
    header("location:restaurantComments.php");
    die();
  }
  try {
    require('scripts/connection.php');
    $sql = "select * from customer where username = ?";
    $stmt1 = $db->prepare($sql);
    $stmt1->execute(array($username));
    $result = $stmt1->fetch(PDO::FETCH_OBJ);
    $sql = "select * from customeraddress where cid = ?";
    $stmt2 = $db->prepare($sql);
    $stmt2->execute(array($result->CID));
    $db = null;
  }
  catch (PDOException $ex) {
    die($ex->getMessage());
  }
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - Profile</title>

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
        <li class="breadcrumb-item">
          <a href="restaurantComments.php">Comments</a>
        </li>
        <li class="breadcrumb-item active"><?php $username ?>Profile</li>
      </ol>
      <div class="row">
        <div class="col-md-3">
          <img class="img-fluid" src="img/customers/<?php echo $result->profileImage;?>" alt="" width="200" height="200"><br/>
        </div>
        <div class="col-md-9">
          <div class="control-group form-group">
            <div class="controls">
              <label>Name</label>
              <input value="<?php echo $result->firstName . " " . $result->lastName; ?>" type="text" class="form-control" id="fname" name='fname' required data-validation-required-message="Enter your first name.">
              <p class="help-block"></p>
            </div>
          </div>
          <div class="control-group form-group">
            <div class="controls">
              <label>Username</label>
              <input value="<?php echo $result->username; ?>" type="text" class="form-control" id="uname" name='uname' required data-validation-required-message="Enter your username.">
              <p class="help-block"></p>
            </div>
          </div>
          <div class="control-group form-group">
            <div class="controls">
              <label>Phone number</label>
              <input value="<?php echo $result->phone; ?>" type="phone" class="form-control" id="phone" name='phone' required data-validation-required-message="Enter your phone number.">
              <p class="help-block"></p>
            </div>
          </div>
          <div class="control-group form-group">
            <div class="controls">
              <label>Email</label>
              <input value="<?php echo $result->email; ?>" type="email" class="form-control" id="email" name='email' required data-validation-required-message="Enter your email.">
              <p class="help-block"></p>
            </div>
          </div>
        </div>
      </div>
      <hr/>
      <br/>
      <div class="row">
        <div class="col-md-8">
          <h3>Addresses</h3>
          <br/>
          <?php
          if($stmt2->rowCount() == 0){
            echo "no address recorded<br/><br/>";
          }
          else{
          ?>
          <div class="table-responsive">
            <table class="table">
              <thead>
              <tr>
                <th>City</th>
                <th>Building</th>
                <th>Block</th>
                <th>Road</th>
              </tr>
            </thead>
              <?php
                while($row = $stmt2->fetch(PDO::FETCH_OBJ)){
                  ?>
                  <tr>
                      <td style="padding-top:18px"><?php echo $row->City; ?></td>
                      <td style="padding-top:18px"><?php echo $row->Building; ?></td>
                      <td style="padding-top:18px"><?php echo $row->Block; ?></td>
                      <td style="padding-top:18px"><?php echo $row->Road; ?></td>
                  </tr>
                  <?php
                }
              ?>
            </table>
            <?php } ?>
          </div>
        </div>
      </div>
      <br/>
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
