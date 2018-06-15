<?php
	require('loginTest.php');
  if(isset($arr)){
    if ($arr[0] == 'restaurant') {
      header('location:index.php');
      die();
    }
  }
	date_default_timezone_set('Asia/Bahrain');
	$now = time();
	$search = '';
	$fromPrice = 0;
	$toPrice = 100000;
	$rateInput = 0;
  extract($_GET);
	if($fromPrice == '') $fromPrice = 0;
	if($toPrice == '') $toPrice = 100000;
  if (isset($rid)) {
    if(trim($rid) != ''){
      try {
        require('scripts/connection.php');
				//paging menus,reviews,branches
				if(!isset($p)){
					$p = 1;
				}
				if($p == '' || $p == 1){
					$p1 = 0;
				}
				else {
					$p1 = ($p * 1) - 1;
				}
				//end paging
        $sql = "select * from restaurants where rid = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($rid));
        if($stmt->rowCount() == 0){
          header('location:restaurants.php');
          die();
        }
        else {
          $result = $stmt->fetch(PDO::FETCH_OBJ);
        }
				//get menus
				if (isset($menu)){
					//filter types
					$typeSql = '';
					//type sql
					if(isset($types)){
						$typeSql = "and Type IN (";
						for ($i=0; $i <count($types) ; $i++) {
							$typeSql .= "'".$types[$i] . "'";
							if($i != count($types) - 1){
								$typeSql .= ",";
							}
						}
						$typeSql .= ")";
					}
					//get menus
	        $sql = "select * from dish where rid = ? and DishName like ? and price >= ? and price <= ? and rating >= ? $typeSql limit $p1,10";
	        $menus = $db->prepare($sql);
	        $menus->execute(array($rid,"$search%",$fromPrice,$toPrice,$rateInput));
					//paging menus
					$menuspages = $db->prepare("select * from dish where rid = ? and DishName like ? and price >= ? and price <= ? and rating >= ?");
					$menuspages->execute(array($rid,"$search%",$fromPrice,$toPrice,$rateInput));
					$menuspages = $menuspages->rowCount();
					$menuspages = ceil($menuspages / 10);
					//get all types
					$allTypes = $db->prepare("select DISTINCT Type from dish where rid = ? order by Type asc");
					$allTypes->execute(array($rid));
				}
				else if(isset($branch)){
	        //get branches
	        $sql = "select * from branch where rid = ? limit $p1,10";
	        $branches = $db->prepare($sql);
	        $branches->execute(array($rid));
					//paging branches
					$branchpages = $db->prepare("select * from branch where rid = ?");
					$branchpages->execute(array($rid));
					$branchpages = $branchpages->rowCount();
					$branchpages = ceil($branchpages / 10);
				}
				else if (isset($review)){
	        //get reviews
	        $sql = "select * from restaurantreviews where rid = ? limit $p1,10";
	        $reviews = $db->prepare($sql);
	        $reviews->execute(array($rid));
            //paging reviews
            $reviewpages = $db->prepare("select * from restaurantreviews where rid = ?");
            $reviewpages->execute(array($rid));
            $reviewpages = $reviewpages->rowCount();
            $reviewpages = ceil($reviewpages / 10);
            //check if user order from this restaurant or not
            if(isset($arr)){
                $sql = "select * from orderslist where cid = ? and rid = ?";
                $orderOrNot = $db->prepare($sql);
                $orderOrNot->execute(array($arr[2],$rid));
                if($orderOrNot->rowCount() == 0){
                    $orderOrNotFlag = false;
                }
                else {
                    $orderOrNotFlag = true;
                }
            }
        }
        //get number of rating
        $stmt = $db->prepare("select * from restaurantrating where rid = ?");
        $stmt->execute(array($rid));
        $noOfRating = $stmt->rowCount();
        $db = null;
      }
      catch (PDOException $ex) {
          die($ex->getMessage());
      }

    }
    else{
        //change index to restaurants
        header('location:restaurants.php');
        die();
    }
  }
  else {
    //change index to restaurants
    header('location:restaurants.php');
    die();
  }
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Food Ordering - Restaurant</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/modern-business.css" rel="stylesheet">

    <!-- Rating Star-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
      .checked {
        color: orange;
      }
			.dish:hover{
        background-color: #f6f6f6;
      }
    </style>

    <script language='javascript'>
        var cmF = false;

        function checkCM(cm)
        {
            var CMExp = /^([\s\w\.\-\+]{2,14})+$/i;
            if (CMExp.test(cm))
            {
                document.getElementById('commsg').innerHTML = "";
                cmF = true;
            }
            else
            {
                document.getElementById('commsg').innerHTML = "Invalid comment";
                document.getElementById('commsg').style = "color:red";
                cmF = false;
            }
        }

        function validateMyInputs()
        {
            document.forms[0].JSF.value = 'true';
            return (cmF);
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
          <a href="restaurant.php">Restaurants</a>
        </li>
        <li class="breadcrumb-item active"><?php echo $result->restaurantname; ?></li>
      </ol>
      <!-- Portfolio Item Row -->
      <div class="row">
        <div class="col-md-3">
          <img class="img-fluid" style="display: block; margin-left: auto;margin-right: auto;" src="img/restaurants/<?php echo $result->profileImage; ?>" alt="" width='200' height='200'><br/>
					<?php if(isset($openOrClose)) echo $openOrClose; ?>
        </div>

        <div class="col-md-3">
          <h3 class="my-3"><?php echo $result->restaurantname; ?></h3>
          <h4 class="my-3"><?php echo $result->phone; ?></h4>
          <h4 class="my-3"><?php echo $result->email; ?></h4>
          <?php
            $rate = $result->rating;
            for($i = 1; $i <= 5; $i++){
              if($i <= $rate && $rate != 0){
                echo "<span class='fa fa-star checked'></span>";
              }
              else {
                echo "<span class='fa fa-star'></span>";
              }
            }
            echo "($noOfRating)";
          ?>

        </div>

      </div>
      <!-- /.row -->
      <hr/>
        <form method="post">
          <div class="row">
            <div class="col-md-4">
              <a href='restaurant.php?rid=<?php echo $result->rid;?>&menu=yes&p=1'><button type="button" class="btn btn-<?php if(isset($menu)) echo 'danger'; else echo 'primary';?> btn-sm">Menus</button></a>
            </div>
            <div class="col-md-4">
              <a href='restaurant.php?rid=<?php echo $result->rid;?>&review=yes&p=1'><button type="button" class="btn btn-<?php if(isset($review)) echo 'danger'; else echo 'primary';?> btn-sm">Reviews</button></a>
            </div>
            <div class="col-md-4">
              <a href='restaurant.php?rid=<?php echo $result->rid;?>&branch=yes&p=1'><button type="button" class="btn btn-<?php if(isset($branch)) echo 'danger'; else echo 'primary';?> btn-sm">Branches</button></a>
            </div>
          </div>
				</form>
      <hr/>
      <div class='row'>
        <?php
          if(isset($menu)){
						?>
                        <div class='col-md-3' style="background-color:#f6f6f6;padding:20px;">
                            <form method="get">
                                <input type="hidden" name='rid' value="<?php echo $rid;?>"/>
                                <input type="hidden" name='menu' value="yes"/>
                            <div class="input-group stylish-input-group">
                                <input type="text" class="form-control"  placeholder="Search" name='search' value="<?php echo $search;?>">
                            </div>
                            <hr/>
                            <h4>Type</h4>
                            <?php
                                while ($type = $allTypes->fetch(PDO::FETCH_OBJ)) {
                                    $check = '';
                                    if(isset($types)){
                                        if(in_array($type->Type,$types)){
                                            $check = 'checked';
                                        }
                                    }
                                    ?>
                                        <label class="checkbox-inline"><input class = 'types' type="checkbox" <?php echo $check; ?> name='types[]' value='<?php echo $type->Type; ?>'> <?php echo $type->Type; ?></label><br/>
                                    <?php
                                }
                            ?>
                            <hr/>
                    <h4>Rating</h4>
                    <?php
                    //get from database
                    for ($i=0; $i <= 5 ; $i++) {
                            $check = '';
                            if($rateInput == $i){
                                $check = 'checked';
                            }
                            if ($i == 0){
                                ?>
                                <label class="checkbox-inline"><input type="radio" <?php echo $check; ?> name='rateInput' value='<?php echo $i; ?>'> None</label><br/>
                                <?php
                            }
                            else {
                            ?>
                            <label class="checkbox-inline"><input type="radio" <?php echo $check; ?> id = 'rate' name='rateInput' value='<?php echo $i; ?>'>
                                 <?php
                                 for($j = 0; $j < 5; $j++){
                                         if($j < $i){
                                             echo "<span class='fa fa-star checked'></span>";
                                         }
                                         else {
                                             echo "<span class='fa fa-star'></span>";
                                         }
                                 }
                                 echo " or more";
                                 ?>
                             </label><br/>
                            <?php
                        }
                    }
                    ?>
                    <hr/>
                    <h4>Price</h4>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>From</label>
                            <input type="text" class="form-control" id="fname" name='fromPrice' value="<?php if (isset($fromPrice)) echo $fromPrice;?>"/> <span id='fnmsg'> </span>
                        </div>
                    </div>
                    <div class="control-group form-group">
                        <div class="controls">
                            <label>To</label>
                            <input type="text" class="form-control" id="fname" name='toPrice' value="<?php if (isset($toPrice)) echo $toPrice;?>"/> <span id='fnmsg'> </span>
                        </div>
                    </div>
                    <div class="input-group stylish-input-group">
                        <input type="submit" class="form-control btn-primary" value="Apply">
                    </div>
                </form>
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-8">
                        <?php
                            if($menus->rowCount()==0){
                                echo "<h4 style='color:red;'>No dishes available</h4>";
                            }
                            else {
                                while ($row = $menus->fetch(PDO::FETCH_OBJ)) {
                                    //get number of rating
                                    require('scripts/connection.php');
                        $stmt1 = $db->prepare("select * from dishrating where DID = ?");
                        $stmt1->execute(array($row->DID));
                        $noOfRating = $stmt1->rowCount();
                                    ?>
                                        <div class="row dish" onclick="location.href='dish.php?did=<?php echo $row->DID;?>';" style="cursor:pointer;">
                                            <div class="col-md-3">
                                                <h3>&nbsp;</h3>
                                    <img class="img-fluid" src="img/dish/<?php echo $row->Image;?>" style="display: block; margin-left: auto;margin-right: auto;" alt="" width='100' height='100'><br/><br/>
                                  </div>
                                            <div class="col-md-8">
                                <h3 class="my-3"><?php echo $row->DishName; ?></h3>
                                <h4 class="my-3"><?php echo $row->Price; ?></h4>
                                                <h4 class="my-3"><?php echo $row->Type; ?></h4>
                                <h4 class="my-10"><?php echo $row->Description; ?></h4>
                                                <?php
                                  $rate = $row->Rating;
                                  for($i = 1; $i <= 5; $i++){
                                    if($i <= $rate){
                                      echo "<span class='fa fa-star checked'></span>";
                                    }
                                    else {
                                      echo "<span class='fa fa-star'></span>";
                                    }
                                  }
                                  echo "($noOfRating)";
                                ?>
                                            </div>
                                        </div>
                                        <hr/>
                                    <?php
                                }
                                ?>
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-1">
                                </div>
                                <div class='col-md-8'>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <?php
                                            for($i = 1; $i <= $menuspages; $i++){
                                                ?>
                                                    <li class="page-item <?php if($i == $p) echo 'active';?>">
                                                        <li class="page-item <?php if($i == $p) echo 'active';?>"><a class="page-link" href="restaurant.php?rid=<?php echo $result->rid;?>&menu=yes&p=<?php echo $i;?>"><?php echo $i;?></a></li>
                                                    </li>
                                        <?php } ?>
                                        </ul>
                                    </nav>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
						<?php
						?></div>
						<?php
          }
          else if (isset($review)){
            ?>
            <?php
						if($reviews->rowCount() == 0){
							?><div class='col-md-12'>
									<h4>No reviews</h4>
								</div>
							<?php
						}
						else {
	            while($row = $reviews->fetch(PDO::FETCH_OBJ)){
	              ?>
	              <div class='col-md-12' style="border:1px solid; padding:10px;margin:5px;">
	                <div class='row'>
	                  <div class='col-md-6'>
	                    <?php
	                      try {
	                        require('scripts/connection.php');
	                        $cust = $db->prepare("select * from customer where CID = ?");
	                        $cust->execute(array($row->CID));
	                        $re = $cust->fetch(PDO::FETCH_OBJ);
	                        echo $re->username;
	                      }
	                      catch(PDOException $ex){
	                        die($ex->getMessage());
	                      }
	                    ?>
	                  </div>
	                  <div class='col-md-6' style='text-align:right;'>
	                    <?php echo $row->date; ?>
	                  </div>
	                </div>
	                <div class='row'>
	                  <div class='col-md-12'>
	                    <b><?php echo $row->comment; ?></b>
	                  </div>
	                </div>
	              </div>
	              <?php
	            }
                    ?>
                    <div class='col-md-12'>
                    <nav aria-label="Page navigation example">
                    <ul class="pagination">
                    <?php
                    for($i = 1; $i <= $reviewpages; $i++){
                    ?>
                    <li class="page-item <?php if($i == $p) echo 'active';?>">
                        <li class="page-item <?php if($i == $p) echo 'active';?>"><a class="page-link" href="restaurant.php?rid=<?php echo $result->rid;?>&review=yes&p=<?php echo $i;?>"><?php echo $i;?></a></li>
                    </li>
                    <?php } ?>
                    </ul>
                    </nav>
                    </div>
                    <?php
						}
						?>
					</div>
					<hr/>
					<?php
						if(isset($arr)){
		          if($arr[0] == 'customer'){
		        ?>
		            <div class='row'>
                <div class='col-md-8'>
                    <form name="sentMessage" id="commentForm" method="post" enctype="multipart/form-data" action="scripts/commentResturant.php">
                    <h4>Leave a comment</h4>
                    <?php
                        if(isset($_GET['missComment'])){
                            echo "<p class = ' ' style='color : red;'>Please enter your comment!</p>";
                        }
                        elseif (isset($_GET['comment'])) {
                            echo "<p class = ' ' style='color : blue;'>Comment added successfully</p>";
                        }
                     ?>
                  <div class="control-group form-group">
                    <div class="controls">
                        <label for="comment">Comment:</label>
                        <textarea class="form-control" rows="5" id="comment" name='comment' onKeyUp='checkCM(this.value)'></textarea><span id='commsg'></span>
                    </div>
                  </div>
                        <input type="hidden" name='rid' value='<?php echo $rid; ?>'/>
                  <!-- For success/fail messages -->
                  <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Comment"/>
                        <br/>
                </form>
                </div>
                <div class='col-md-4'>
                    <h4>Rating of the restaurant</h4>
                    <form name="sentMessage" id="commentForm" method="post" enctype="multipart/form-data" action="scripts/ratingResturant.php">
                    <div class="control-group form-group">
                        <div class="controls">
                                <label for="Rating">Rating:</label><br/>
                                <?php
                                    for($j = 0; $j <= 5; $j++){
                                        echo "<button name='rating' value='$j#$rid'>";
                                        for($i = 0; $i < 5; $i++){
                                                if($i < $j){
                                                    echo "<span class='fa fa-star checked'></span>";
                                                }
                                                else {
                                                    echo "<span class='fa fa-star'></span>";
                                                }
                                        }
                                        echo "</button>";
                                        echo "<br/>";
                                    }
                                ?>
                            </div>
                          </div>
                            </form>
                            </div>
								</div>
		            <!-- rating -->
		        <?php
		        }
					}
		        ?>
					<?php
          }
          else if ($branch){
            ?>
              <div class='col-md-12'>
								<?php if($branches->rowCount() != 0){ ?>
                <div class="table-responsive">
                  <table class="table" width='100%'>
                    <thead>
                    <tr>
                      <th>Branch No.</th>
                      <th>location</th>
                      <th>Phone</th>
                      <th>Open time</th>
                      <th>Close time</th>
                    </tr>
                  </thead>
                  <?php
	                    $i = 1;
	                    while($row = $branches->fetch(PDO::FETCH_OBJ)){
	                      ?>
	                      <tr>
	                          <td style="padding-top:18px"><?php echo $i;$i++; ?></td>
	                          <td style="padding-top:18px"><?php echo $row->location; ?></td>
	                          <td style="padding-top:18px"><?php echo $row->phone; ?></td>
	                          <td style="padding-top:18px"><?php echo $row->openTime; ?></td>
	                          <td style="padding-top:18px"><?php echo $row->closeTime; ?></td>
	                      </tr>
	                      <?php
	                    }
                            ?>
                        </table>
                        </div>
                        <div class='col-md-12'>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php
                                        for($i = 1; $i <= $branchpages; $i++){
                                            ?>
                                                <li class="page-item <?php if($i == $p) echo 'active';?>">
                                                    <li class="page-item <?php if($i == $p) echo 'active';?>"><a class="page-link" href="restaurant.php?rid=<?php echo $result->rid;?>&branch=yes&p=<?php echo $i;?>"><?php echo $i;?></a></li>
                                                </li>
                                <?php } ?>
                                </ul>
                            </nav>
                        </div>
                            <?php
                        }
                        else {
                            echo "<h4>No branches</h4>";
                        }
                  ?>
              </div>
            <?php
						?>
					</div>
					<?php
          }
        ?>
        </div>
        <hr/>
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
