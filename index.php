<?php
	require('loginTest.php');
	try {
		require('scripts/connection.php');
		if(!isset($arr)){
			$sql = "select * from restaurants order by rating desc limit 5";
			$topRests = $db->prepare($sql);
			$topRests->execute();
			$sql = "select * from dish order by Rating desc limit 5";
			$topDishes = $db->prepare($sql);
			$topDishes->execute();
		}
		else {
			if($arr[0]=="customer"){
				$sql = "select * from restaurants order by rating desc limit 5";
				$topRests = $db->prepare($sql);
				$topRests->execute();
				$sql = "select * from dish order by Rating desc limit 5";
				$topDishes = $db->prepare($sql);
				$topDishes->execute();
			}
			else{
				//select for restaurant home page
				header("location:inProcess.php");
			}
		}
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

    <title>Food Ordering - Home</title>

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
      .restaurant{
        padding: 10px;
      }
      .restaurant:hover{
        background-color: #f6f6f6;
      }
			.dish:hover{
				background-color: #f6f6f6;
			}

    </style>
  </head>

  <body>

    <!-- Navigation -->

	<?php require('navbar.php'); ?>

    <?php
			if(isset($arr)){
				if(isset($_SERVER['HTTP_REFERER'])){
					$back = $_SERVER['HTTP_REFERER'];
					$temp = explode('/',$back);
					if($temp[count($temp) - 1] == 'login.php'){
					?>
					<script>
						alert("<?php echo 'Hello ' . $arr[1]; ?>");
					</script>
					<?php
				}
			}
		}
	?>

    <!-- Page Content -->
    <div class="container">

      <?php
				//visitor
				if(!isset($arr)){
					?>
					<h1 class="my-4">Welcome to Online Food Ordering</h1>
					<div class="row">
						<div class='col-md-12'>
							<h3>Top 5 Restaurants</h3>
						</div>
						<div class='col-md-12' id = 'restaurant'>
							<?php
							while ($row = $topRests->fetch(PDO::FETCH_OBJ)) {
							  try {
							    require('scripts/connection.php');
							    $rid = $row->rid;
							    //get number of rating
							    $stmt1 = $db->prepare("select * from restaurantrating where rid = ?");
							    $stmt1->execute(array($rid));
							    $noOfRating = $stmt1->rowCount();
							    $branchTimes = $db->prepare("select * from branch where rid = ?");
							    $db = null;
							  }
							  catch (PDOException $ex) {
							      die($ex->getMessage());
							  }
							  ?>
								<div class="row restaurant" onclick="location.href='restaurant.php?rid=<?php echo $row->rid;?>&menu=yes&p=1';" style="cursor:pointer;">

				          <div class="col-md-3">
				            <img class="img-fluid" src="img/restaurants/<?php echo $row->profileImage;?>" style="display: block; margin-left: auto;margin-right: auto;" alt="" width='200' height='200'><br/><br/>
										<!-- <?php echo $openedClosed; ?>-->
				          </div>

				              <div class="col-md-9">
				                <h3 class="my-3"><?php echo $row->restaurantname; ?></h3>
				                <h4 class="my-3"><?php echo $row->phone; ?></h4>
				                <h4 class="my-3"><?php echo $row->email; ?></h4>
				                <?php
				                  $rate = $row->rating;
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
				        </div></a>
							  <?php
							}
							?>
						</div>
					</div>
						<hr/>
					<div class="row">
						<div class='col-md-12'>
							<h3>Top 5 Dishes</h3>
						</div>
						<div class="col-md-12">
							<?php
							while ($row = $topDishes->fetch(PDO::FETCH_OBJ)) {
								//get number of rating
								require('scripts/connection.php');
								$stmt1 = $db->prepare("select * from dishrating where DID = ?");
								$stmt1->execute(array($row->DID));
								$noOfRating = $stmt1->rowCount();
								?>
									<div class="row dish" onclick="location.href='dish.php?did=<?php echo $row->DID;?>';" style="cursor:pointer;">
										<div class="col-md-3">
											<h3>&nbsp;</h3>
											<img class="img-fluid" src="img/dish/<?php echo $row->Image;?>" style="display: block; margin-left: auto;margin-right: auto;" alt="" width='200' height='200'><br/><br/>
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
						</div>
					</div>
					<?php
				}
				else {
					//customer
					if($arr[0] == "customer"){
						?>
						<h1 class="my-4">Welcome to Online Food Ordering</h1>
						<div class="row">
							<div class='col-md-12'>
								<h3>Top 5 Restaurants</h3>
							</div>
							<div class='col-md-12' id = 'restaurant'>
								<?php
								while ($row = $topRests->fetch(PDO::FETCH_OBJ)) {
								  try {
								    require('scripts/connection.php');
								    $rid = $row->rid;
								    //get number of rating
								    $stmt1 = $db->prepare("select * from restaurantrating where rid = ?");
								    $stmt1->execute(array($rid));
								    $noOfRating = $stmt1->rowCount();
								    $branchTimes = $db->prepare("select * from branch where rid = ?");
								    $db = null;
								  }
								  catch (PDOException $ex) {
								      die($ex->getMessage());
								  }
								  ?>
									<div class="row restaurant" onclick="location.href='restaurant.php?rid=<?php echo $row->rid;?>&menu=yes&p=1';" style="cursor:pointer;">

					          <div class="col-md-3">
					            <img class="img-fluid" src="img/restaurants/<?php echo $row->profileImage;?>" style="display: block; margin-left: auto;margin-right: auto;" alt="" width='200' height='200'><br/><br/>
											<!-- <?php echo $openedClosed; ?>-->
					          </div>

					              <div class="col-md-9">
					                <h3 class="my-3"><?php echo $row->restaurantname; ?></h3>
					                <h4 class="my-3"><?php echo $row->phone; ?></h4>
					                <h4 class="my-3"><?php echo $row->email; ?></h4>
					                <?php
					                  $rate = $row->rating;
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
					        </div></a>
								  <?php
								}
								?>
							</div>
						</div>
							<hr/>
						<div class="row">
							<div class='col-md-12'>
								<h3>Top 5 Dishes</h3>
							</div>
							<div class="col-md-12">
								<?php
								while ($row = $topDishes->fetch(PDO::FETCH_OBJ)) {
									//get number of rating
									require('scripts/connection.php');
									$stmt1 = $db->prepare("select * from dishrating where DID = ?");
									$stmt1->execute(array($row->DID));
									$noOfRating = $stmt1->rowCount();
									?>
										<div class="row dish" onclick="location.href='dish.php?did=<?php echo $row->DID;?>';" style="cursor:pointer;">
											<div class="col-md-3">
												<h3>&nbsp;</h3>
												<img class="img-fluid" src="img/dish/<?php echo $row->Image;?>" style="display: block; margin-left: auto;margin-right: auto;" alt="" width='200' height='200'><br/><br/>
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
							</div>
						</div>
						<?php
					}
					else {
						//restaurant homepage here!!
					}
				}
			?>

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
