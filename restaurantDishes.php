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
      $sql = "select * from dish where rid = ?";
      $stmt4 = $db->prepare($sql);
      $stmt4->execute(array($arr[2]));
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
        <li class="breadcrumb-item active">Dishes</li>
      </ol>
      <!-- Portfolio Item Row -->
        <div class="row">
           <div class="col-md-12">
             <h3>Restaurant Dishes</h3>
             <br/>
             <?php
               if($stmt4->rowCount() == 0){
                 echo "no dishes recorded<br/><br/>";
               }
             else{
             ?>
             <div class="table-responsive">
               <table class="table">
                 <thead>
                 <tr>
                   <th>Image</th>
                   <th>Dish Name</th>
                   <th>Price</th>
									 <th>Type</th>
                   <th>Description</th>
									 <th>Rating</th>
                   <th></th>
                   <th></th>
                 </tr>
               </thead>
               <?php
                 while($row = $stmt4->fetch(PDO::FETCH_OBJ)){
                   ?>
                   <tr>
                       <td style="padding-top:18px"><img class="img-fluid" src="img/dish/<?php echo $row->Image;?>" alt="" width="50" height="50"></td>
                       <td style="padding-top:18px"><?php echo $row->DishName; ?></td>
                       <td style="padding-top:18px"><?php echo $row->Price; ?></td>
											 <td style="padding-top:18px"><?php echo $row->Type; ?></td>
                       <td style="padding-top:18px"><?php echo $row->Description; ?></td>
											 <td style="padding-top:18px"><?php echo $row->Rating; ?></td>
                       <td align='center'><a href='scripts/deleteDish.php?did=<?php echo $row->DID;?>'><button type="button" class="btn btn-danger btn-sm">Delete</button></a></td>
                       <td align='center'><a href='updateDish.php?did=<?php echo $row->DID;?>'><button type="button" class="btn btn-sm btn-primary">Update</button></a></td>
                   </tr>
                   <?php
                 }
               ?>
             </table>
             </div>
           <?php } ?>
           <hr/>
           <a href='newDish.php'><button type="button" class="btn btn-primary btn-sm">Add new dish</button></a>
           <hr/>
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
