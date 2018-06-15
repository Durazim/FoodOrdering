<?php
  try {
    require('scripts/connection.php');
    $sql = "select * from customer where cid = ?";
    $stmt1 = $db->prepare($sql);
    $stmt1->execute(array($arr[2]));
    $result = $stmt1->fetch(PDO::FETCH_OBJ);
    $sql = "select * from customeraddress where cid = ?";
    $stmt2 = $db->prepare($sql);
    $stmt2->execute(array($arr[2]));
    $db = null;
  }
  catch (PDOException $ex)
  {
    die($ex->getMessage());
  }
?>

<?php
$flag = true;
extract($_POST);
$update ='';
$exist = '';
$existEmail = '';
if(isset($s1)){
  if(trim($fname) == ""){
    $fname = $result->firstName;
  }
  if(trim($lname) == ""){
    $lname = $result->lastName;
  }
  if(trim($uname) == ""){
    $uname = $result->username;
  }
  if(trim($phone) == ""){
    $phone = $result->phone;
  }
  if(trim($email) == ""){
    $email = $result->email;
  }
            $patternUN = "/^[\w.]{3,12}$/i";
            $patternFN = "/^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i";
            $patternLN = "/^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i";
            $patternEM = "/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/";
            $patternPH = "/^((00|\+)973)?\d{8}$/i";

            if (!preg_match($patternUN,$uname))
                $flag = false;

            if (!preg_match($patternFN,$fname))
                $flag = false;

            if (!preg_match($patternLN,$lname))
                $flag = false;

            if (!preg_match($patternEM,$email))
                $flag = false;

            if (!preg_match($patternPH,$phone))
                $flag = false;

  try{
    require('scripts/connection.php');
    if($uname != $result->username){
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
    }
    if($email != $result->email){
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
    }
      //type problem
      if (($_FILES["profile"]["size"] < 1024000)) {
        if ($_FILES["profile"]["error"] > 0) {
          $profile = $result->profileImage;
        }
        else {
            $tempArr = explode('.',$_FILES["profile"]["name"]);
            $_FILES["profile"]["name"] = $result->CID . '.' . $tempArr[1];
            move_uploaded_file($_FILES["profile"]["tmp_name"], "img/customers/" . $_FILES["profile"]["name"]);
            $profile = $_FILES["profile"]["name"];
          }
        }
      else {
        $profile = $result->profileImage;
      }
    if($flag){
      $sql = "update customer set firstName=? , lastName=? , username=? , email=? , phone=? , profileImage=? where CID = ?";
      $stmt = $db->prepare($sql);
      $stmt->execute(array($fname,$lname,$uname,$email,$phone,$profile,$arr[2]));
      ?>
      <script>
        alert("Update Done");
      </script>
      <?php
      $result->firstName = $fname;
      $result->lastName = $lname;
      $result->username = $uname;
      $result->email = $email;
      $result->phone = $phone;
      $result->profileImage = $profile;
    }
    $db = null;
  }
  catch(PDOException $ex){
    echo $ex->getMessage();
  }
}

?>
<html>
    <head>
        <script language='javascript'>
            var unF = false;
            var fnF = false;
            var lnF = false;
            var emF = false;
            var phF = false;

            function checkUN(un)
            {
                var UNExp = /^[\w.]{3,12}$/i;
                if (UNExp.test(un))
                {
                    document.getElementById('unmsg').innerHTML = "Valid Username";
                    document.getElementById('unmsg').style = "color:green";
                    var unF = true;
                }
                else
                {
                    document.getElementById('unmsg').innerHTML = "Invalid Username";
                    document.getElementById('unmsg').style = "color:red";
                    var unF = false;
                }
            }

            function checkFN(fn)
            {
                var FNExp = /^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i;
                if (FNExp.test(fn))
                {
                    document.getElementById('fnmsg').innerHTML = "";
                    var fnF = true;
                }
                else
                {
                    document.getElementById('fnmsg').innerHTML = "Invalid Name";
                    document.getElementById('fnmsg').style = "color:red";
                    var fnF = false;
                }
            }

            function checkLN(ln)
            {
                var FNExp = /^[a-zA-z]{2,14}(\s[a-zA-z]{2,14})*$/i;
                if (FNExp.test(ln))
                {
                    document.getElementById('lnmsg').innerHTML = "";
                    var lnF = true;
                }
                else
                {
                    document.getElementById('lnmsg').innerHTML = "Invalid Name";
                    document.getElementById('lnmsg').style = "color:red";
                    var lnF = false;
                }
            }

            function checkEM(em)
            {
                var FNExp = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
                if (FNExp.test(em))
                {
                    document.getElementById('emmsg').innerHTML = "Valid Email";
                    document.getElementById('emmsg').style = "color:green";
                    var emF = true;
                }
                else
                {
                    document.getElementById('emmsg').innerHTML = "Invalid Email";
                    document.getElementById('emmsg').style = "color:red";
                    var emF = false;
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
                return (unF && fnF && lnF && phF && emF);
            }
        </script>
    </head>
<body>
<form name="sentMessage" id="updateForm" method="post" enctype="multipart/form-data" onsubmit="return validateMyInputs();">
<div class="row">
  <div class="col-md-3">
    <img class="img-fluid" src="img/customers/<?php echo $result->profileImage;?>" alt="" width="200" height="200"><br/>
    <br/>
    <input type='file' name='profile' width="100%"/>
    <br/>
    <br/>
  </div>
  <div class="col-md-9">
    <div id="success">
      <?php
        echo $exist;
        echo $existEmail;
      ?>
    </div>
      <div class="control-group form-group">
        <div class="controls">
          <label>First name</label>
          <input value="<?php echo $result->firstName; ?>" type="text" class="form-control" id="fname" name='fname' required data-validation-required-message="Enter your first name." onKeyUp='checkFN(this.value)' /><span id='fnmsg'></span>
          <p class="help-block"></p>
        </div>
      </div>
      <div class="control-group form-group">
        <div class="controls">
          <label>Last name</label>
          <input value="<?php echo $result->lastName; ?>" type="text" class="form-control" id="lname" name='lname' required data-validation-required-message="Enter your last name." onKeyUp='checkLN(this.value)' /><span id='lnmsg'></span>
          <p class="help-block"></p>
        </div>
      </div>
      <div class="control-group form-group">
        <div class="controls">
          <label>Username</label>
          <input value="<?php echo $result->username; ?>" type="text" class="form-control" id="uname" name='uname' required data-validation-required-message="Enter your username." onKeyUp='checkUN(this.value)' /> <span id='unmsg'> </span>
          <p class="help-block"></p>
        </div>
      </div>
      <div class="control-group form-group">
        <div class="controls">
          <label>Phone number</label>
          <input value="<?php echo $result->phone; ?>" type="phone" class="form-control" id="phone" name='phone' required data-validation-required-message="Enter your phone number." onKeyUp='checkPH(this.value)' /> <span id='phmsg'> </span>
          <p class="help-block"></p>
        </div>
      </div>
      <div class="control-group form-group">
        <div class="controls">
          <label>Email</label>
          <input value="<?php echo $result->email; ?>" type="email" class="form-control" id="email" name='email' required data-validation-required-message="Enter your email." onKeyUp='checkEM(this.value)' /> <span id='emmsg'> </span>
          <p class="help-block"></p>
        </div>
      </div>
      <!-- For success/fail messages -->
	  <input type='hidden' name='JSF' value='false' />
      <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Update"/>
       <a href='changePass.php'><button type="button" class="btn btn-primary btn-sm">Change password</button></a>
      <br/>
    </div>
    </div>
</form>
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
          <th></th>
          <th></th>
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
                <td align='center'><a href='scripts/deleteAddress.php?aid=<?php echo $row->AID;?>'><button type="button" class="btn btn-danger btn-sm">Delete</button></a></td>
                <td align='center'><a href='updateAddress.php?aid=<?php echo $row->AID;?>'><button type="button" class="btn btn-sm btn-primary">Update</button></a></td>
            </tr>
            <?php
          }
        ?>
      </table>
      <?php } ?>
      <a href='newAddress.php'><button type="button" class="btn btn-primary btn-sm">Add new addrress</button></a>
    </div>
  </div>
</div>
</body>
</html>
