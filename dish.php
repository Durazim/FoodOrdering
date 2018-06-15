<?php
	require('loginTest.php');
  if(isset($arr)){
    if ($arr[0] == 'restaurant') {
      header('location:index.php');
      die();
    }
  }
	extract($_GET);
	if(!isset($p)){
		$p = 1;
	}
	if($p == '' || $p == 1){
		$p1 = 0;
	}
	else {
		$p1 = ($p * 1) - 1;
	}
  if (isset($did)) {
    if(trim($did) != ''){
      try{
        require ('scripts/connection.php');
        $sql = "select * from dish where DID = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute(array($did));
        if($stmt->rowCount() == 0){
          header('location:restaurants.php');
          die();
        }
        else {
          $result = $stmt->fetch(PDO::FETCH_OBJ);
          $stmt = $db->prepare("select * from restaurants where rid  = ?");
          $stmt->execute(array($result->rid));
          $restaurantRes = $stmt->fetch(PDO::FETCH_OBJ);
					//get reviews of dish
	        $reviewsSql = "select * from dishreviews where DID = ? limit $p1,10";
	        $reviews = $db->prepare($reviewsSql);
	        $reviews->execute(array($did));
					//paging reviews
					$reviewpages = $db->prepare("select * from dishreviews where DID = ?");
					$reviewpages->execute(array($did));
					$reviewpages = $reviewpages->rowCount();
					$reviewpages = ceil($reviewpages / 10);
					//get number of rating
					$stmt = $db->prepare("select * from dishrating where DID = ?");
	        $stmt->execute(array($did));
	        $noOfRating = $stmt->rowCount();
					//check if user order this dish or not
					if(isset($arr)){
						$orderOrNotFlag = false;
						$sql = "select * from orderslist where cid = ?";
						$orderOrNot = $db->prepare($sql);
						$orderOrNot->execute(array($arr[2]));
						while ($row = $orderOrNot->fetch(PDO::FETCH_OBJ)) {
							$sql = "select * from orderdishes where oid = ? and did = ?";
							$check = $db->prepare($sql);
							$check->execute(array($row->oid,$did));
							if($check->rowCount() != 0){
								$orderOrNotFlag = true;
							}
						}
	        }
				}
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
            var CMExp = /^([\w\s]{1,14})+$/i;
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
          <a href="restaurants.php">Restaurants</a>
        </li>
        <li class="breadcrumb-item">
          <a href="restaurant.php?rid=<?php echo $result->rid;?>&menu=yes&p=1"><?php echo $restaurantRes->restaurantname;?></a>
        </li>
        <li class="breadcrumb-item active"><?php echo $result->DishName; ?></li>
      </ol>
      <!-- Portfolio Item Row -->
      <div class="row">

        <div class="col-md-3">
					<h3>&nbsp;</h3>
          <img class="img-fluid" style="display: block; margin-left: auto;margin-right: auto;" src="img/dish/<?php echo $result->Image; ?>" alt="" width='200' height='200'><br/>
					<?php if(isset($openOrClose)) echo $openOrClose; ?>
        </div>

        <div class="col-md-6">
          <h3 class="my-3"><?php echo $result->DishName; ?></h3>
          <h4 class="my-3"><?php echo $result->Price . " BD"; ?></h4>
					<h4 class="my-3"><?php echo $result->Type; ?></h4>
					<p class="my-3"><?php echo $result->Description; ?></p>
          <!--type and rating!!! -->
          <?php
            $rate = $result->Rating;
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
        <?php
        if(isset($arr)){
          if($arr[0] == 'customer'){
            ?>
          <div class="col-md-3">
            <h3>Add to cart</h3>
            <form method="post" action="addToOrder.php">
              <input type="hidden" name='ridDid' value="<?php echo $result->rid; ?>#<?php echo $did; ?>"/>
              <div class="form-group">
                <label>Quantity</label>
                <select class="form-control" id="exampleFormControlSelect1" name='qty'>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
              </div>
              <input type="submit" class="form-control btn-primary" value="Add to Cart">
            </form>
          </div>
          <?php
        }
      }
      ?>
      </div>
      <hr/>
			<div class='row'>
				<?php
					if($reviews->rowCount() == 0){
						?>
							<div class='col-md-12'>
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
									<?php
									if(trim($row->replaycomment) != ""){
									?>
										<div class='col-md-12'>
											<b style="color:blue"><?php echo $restaurantRes->restaurantname; ?></b> repaly saying : <?php echo $row->replaycomment;?>
										</div>
									<?php
									}
									?>
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
      if(isset($arr) && $orderOrNotFlag){
        if($arr[0] == 'customer'){
          ?>
      <div class='row'>
        <div class='col-md-8'>
          <form name="sentMessage" id="commentForm" method="post" enctype="multipart/form-data" action="scripts/commentDish.php">
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
                <textarea class="form-control" rows="5" id="comment" name='comment' onKeyUp='checkCM(this.value)'></textarea>
								<span id='commsg'></span>
              </div>
            </div>
            <input type="hidden" name='did' value='<?php echo $did; ?>'/>
            <!-- For success/fail messages -->
            <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Comment"/>
            <br/>
          </form>
        </div>
        <div class='col-md-4'>
          <h4>Rating of the dish</h4>
          <form name="sentMessage" id="commentForm" method="post" enctype="multipart/form-data" action="scripts/ratingDish.php">
            <div class="control-group form-group">
              <div class="controls">
								<!-- database -->
                <label for="Rating">Rating:</label><br/>
                <?php
                  for($j = 0; $j <= 5; $j++){
                    echo "<button name='rating' value='$j#$did'>";
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
        <?php
      }
    }
		else {
			?>
					<h4>You must order to comment or rate</h4>
			<?php
		}
    ?>
      </div>
      <!-- /.row -->
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
