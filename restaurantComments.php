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
    //paging reviews
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
    //get reviews
    try {
      require('scripts/connection.php');
      $sql = "select * from restaurantreviews where rid = ? limit $p1,10";
      $reviews = $db->prepare($sql);
      $reviews->execute(array($arr[2]));
      //paging reviews
      $reviewpages = $db->prepare("select * from restaurantreviews where rid = ?");
      $reviewpages->execute(array($arr[2]));
      $reviewpages = $reviewpages->rowCount();
      $reviewpages = ceil($reviewpages / 10);
    }
    catch (PDOException $e) {
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
</style>

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
          <a href="restaurantCommentHome.php">Comments</a>
        </li>
        <li class="breadcrumb-item active">Restaurant Comments</li>
      </ol>
      <!-- Portfolio Item Row -->
      <div class='row'>
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
	                        echo "<a href='customerInfo.php?username=$re->username'>$re->username</a>";
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
									<form name="sentMessage" id="commentForm" method="post" enctype="multipart/form-data" action="scripts/replayRestaurant.php">
                  <div class='row'>
                    <div class='col-md-12'>
											<?php
                        if(isset($_GET['commid'])){
                          if($_GET['commid'] == $row->commid){
                            if(isset($_GET['missComment'])){
                              echo "<p class = ' ' style='color : red;'>Please enter your replay!</p>";
                            }
                            elseif (isset($_GET['comment'])) {
                              echo "<p class = ' ' style='color : blue;'>Replay added successfully</p>";
                            }
                          }
                        }
                       ?>
                      <div class="control-group form-group">
                        <div class="controls">
                          <label for="comment">Repaly:</label>
                          <textarea class="form-control" rows="3" id="replay" name='replay'></textarea>
                        </div>
                      </div>
                      <input type="hidden" name='commid' value='<?php echo $row->commid; ?>'/>
                      <!-- For success/fail messages -->
                      <input type="submit" class="btn btn-primary" name='s1' id="sendMessageButton" value="Replay"/>
                    </div>
	                </div>
                </form>
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
