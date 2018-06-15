<?php
session_start();
  if(!isset($_SESSION['isAdmin'])){
    header('location:admin.php');
  }
?>
<?php
	$search = '';
	$rateInput = 0;
  extract($_GET);
	if(!isset($sortBy)){
		$sortBy = "Newest to Oldest";
	}
	//paging restaurants

	if(!isset($p)){
		$p = 1;
	}
	if($p == '' || $p == 1){
		$p1 = 0;
	}
	else {
		$p1 = ($p * 1) - 1;
	}

	//sort by
	if($sortBy == "Newest to Oldest"){
		$orderby = "order by registerDate desc";
	}
	else if ($sortBy == "Oldest to Newest"){
		$orderby = "order by registerDate asc";
	}
	else if ($sortBy == "Rating : Highest to Lowest"){
		$orderby = "order by rating desc";
	}
	else if ($sortBy == "Rating : Lowest to Highest"){
		$orderby = "order by rating asc";
	}
	else if ($sortBy == "Name : A-Z"){
		$orderby = "order by restaurantname asc";
	}
	else if ($sortBy == "Name : Z-A"){
		$orderby = "order by restaurantname desc";
	}
	else {
		$sortBy = "order by registerDate desc";
	}

	//end sort by

  try {
      require('scripts/connection.php');
			//get all types
			$allTypes = $db->prepare("select DISTINCT type from restaurantstypes");
			$allTypes->execute();
			//get all branch locations
			$allLocations = $db->prepare("select DISTINCT location from branch");
			$allLocations->execute();
			//filter types
			$ridInSql = '';
			if(isset($types)){
				$typeSqlStmt = '';
				for ($i=0; $i < count($types); $i++) {
					$typeSqlStmt .= "type = '$types[$i]'";
					if ($i < count($types) - 1 ) {
						$typeSqlStmt .= ' or ';
					}
				}
				$typeSql = "select * from restaurantstypes where ";
				$typeSql .= $typeSqlStmt;
				$resultRid = $db->prepare($typeSql);
				$resultRid->execute();
				$ridInSql = "and (";
				$count = $resultRid->rowCount();
				$i = 0;
				while ($row = $resultRid->fetch(PDO::FETCH_OBJ)) {
					$ridInSql .= "rid = $row->rid";
					if ($i < $count - 1) {
						$ridInSql .= ' or ';
					}
					$i++;
				}
				$ridInSql .= ')';
			}
			//end filter types
			//filter branches
			$ridInSqlBranch = '';
			if(isset($locations)){
				$branchSqlStmt = '';
				for ($i=0; $i < count($locations); $i++) {
					$branchSqlStmt .= "location = '$locations[$i]'";
					if ($i < count($locations) - 1 ) {
						$branchSqlStmt .= ' or ';
					}
				}
				$branchSql = "select * from branch where ";
				$branchSql .= $branchSqlStmt;
				$resultRidBranch = $db->prepare($branchSql);
				$resultRidBranch->execute();
				$ridInSqlBranch = "and (";
				$count = $resultRidBranch->rowCount();
				$i = 0;
				while ($row = $resultRidBranch->fetch(PDO::FETCH_OBJ)) {
					$ridInSqlBranch .= "rid = $row->rid";
					if ($i < $count - 1) {
						$ridInSqlBranch .= ' or ';
					}
					$i++;
				}
				$ridInSqlBranch .= ')';
			}
			//end filter types
      $sql = "select * from restaurants where restaurantname like ? $ridInSql $ridInSqlBranch and rating >= ? $orderby limit $p1,1";
      $stmt = $db->prepare($sql);
      $stmt->execute(array("$search%",$rateInput));
			$pages = $db->prepare("select * from restaurants where restaurantname like ? $ridInSql $ridInSqlBranch and rating >= ? $orderby ");
			$pages->execute(array("$search%",$rateInput));
			$pages = $pages->rowCount();
			$pages = ceil($pages / 1);
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

    <title>Food Ordering - Restaurants</title>

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
    </style>

		<!-- ajax -->
		<script>
			function GetXmlHttpObject() {
				var xmlHttp=null;
				try{ // Firefox, Opera 8.0+, Safari
					xmlHttp=new XMLHttpRequest();
				}
				catch (e) { // Internet Explorer
					try {
						xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
					}
					catch (e) {
						xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
				}
				return xmlHttp;
			}
			function sortRestaurant(orderType) {
				url = "scripts/orderRestaurants.php?search=";
				url += document.getElementById('search').value;
				//pass array of check types
				typesIndex = 0;
				var checkedTypes =  document.getElementsByName("types[]");
				var checkedTypesLength = checkedTypes.length;
				for(i=0;i< checkedTypesLength;i++){
    			if(checkedTypes[i].checked){
						url += "&types[" + "]=" + checkedTypes[i].value;
						typesIndex++;
					}
				}
				//pass array of check locations
				locationsIndex = 0;
				var checkedLocations =  document.getElementsByName("locations[]");
				var checkedLocationsLength = checkedLocations.length;
				for(i=0;i< checkedLocationsLength;i++){
    			if(checkedLocations[i].checked){
						url += "&locations[" + "]=" + checkedLocations[i].value;
						locationsIndex++;
					}
				}
				//pass ratingResturant
				locationsIndex = 0;
				var ratingRadios =  document.getElementsByName("rateInput");
				var ratingRadiosLength = ratingRadios.length;
				for(i=0;i< ratingRadiosLength;i++){
    			if(ratingRadios[i].checked){
						url += "&rateInput=" + ratingRadios[i].value;
						break;
					}
				}
				url += "&sortBy=" + orderType;
				//getPageNumber
				var parts = window.location.search.substr(1).split("&");
				var $_GET = {};
				for (var i = 0; i < parts.length; i++) {
				    var temp = parts[i].split("=");
				    $_GET[decodeURIComponent(temp[0])] = temp[1];
				}
				//end get page
				//try to change
				if($_GET['p'] > 1){
					getUrl = "restaurants.php?search="+document.getElementById('search').value;
					//pass array of check types
					typesIndex = 0;
					var checkedTypes =  document.getElementsByName("types[]");
					var checkedTypesLength = checkedTypes.length;
					for(i=0;i< checkedTypesLength;i++){
	    			if(checkedTypes[i].checked){
							getUrl += "&types[" + "]=" + checkedTypes[i].value;
							typesIndex++;
						}
					}
					//pass array of check locations
					locationsIndex = 0;
					var checkedLocations =  document.getElementsByName("locations[]");
					var checkedLocationsLength = checkedLocations.length;
					for(i=0;i< checkedLocationsLength;i++){
	    			if(checkedLocations[i].checked){
							getUrl += "&locations[" + "]=" + checkedLocations[i].value;
							locationsIndex++;
						}
					}
					//pass ratingResturant
					locationsIndex = 0;
					var ratingRadios =  document.getElementsByName("rateInput");
					var ratingRadiosLength = ratingRadios.length;
					for(i=0;i< ratingRadiosLength;i++){
	    			if(ratingRadios[i].checked){
							getUrl += "&rateInput=" + ratingRadios[i].value;
							break;
						}
					}
					getUrl += "&sortBy=" + orderType;
					window.location.href = encodeURI(getUrl);
				}
				else {
				var xmlHttp= GetXmlHttpObject();
				xmlHttp.onreadystatechange=function(){
					if(xmlHttp.readyState==4) {
						document.getElementById('restaurant').innerHTML=xmlHttp.responseText;
						}
					}
				xmlHttp.open("GET",url,true);
				xmlHttp.send(null);
				}
			}
		</script>

  </head>

  <body>

    <!-- Navigation -->

	<?php require('adminNav.php'); ?>


    <!-- Page Content -->
    <div class="container">

      <br/>

      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="adminIndex.php">Home</a>
        </li>
        <li class="breadcrumb-item active">Restaurants</li>
      </ol>
			<form method="get">
      <div class='row'>
        <div class='col-md-10'>
					<div class="input-group stylish-input-group">
						<input type="text" class="form-control"  placeholder="Search" name='search' id = 'search' value="<?php echo $search;?>">
					</div>
        </div>
				<div class='col-md-2'>
					<div class="input-group stylish-input-group">
						<input type="submit" class="form-control btn-primary" value="Search">
					</div>
				</div>
      </div>
			<hr/>
      <div class='row'>
        <div class='col-md-3' style="background-color:#f6f6f6;padding:20px;">
					<div class="form-group">
				    <label for="exampleFormControlSelect1">Sort By</label>
				    <select class="form-control" id="exampleFormControlSelect1" name='sortBy' onchange="sortRestaurant(this.value)">
							<option <?php if ($sortBy=="Newest to Oldest") echo "selected";?>>Newest to Oldest</option>
							<option <?php if ($sortBy=="Oldest to Newest") echo "selected";?>>Oldest to Newest</option>
							<option <?php if ($sortBy=="Rating : Highest to Lowest") echo "selected";?>>Rating : Highest to Lowest</option>
							<option <?php if ($sortBy=="Rating : Lowest to Highest") echo "selected";?>>Rating : Lowest to Highest</option>
				      <option <?php if ($sortBy=="Name : A-Z") echo "selected";?>>Name : A-Z</option>
				      <option <?php if ($sortBy=="Name : Z-A") echo "selected";?>>Name : Z-A</option>
				    </select>
				  </div>
					<hr/>
					<h4>Type</h4>
					<?php
						while ($type = $allTypes->fetch(PDO::FETCH_OBJ)) {
							$check = '';
							if(isset($types)){
								if(in_array($type->type,$types)){
									$check = 'checked';
								}
							}
							?>
								<label class="checkbox-inline"><input class = 'types' type="checkbox" <?php echo $check; ?> name='types[]' value='<?php echo $type->type; ?>'> <?php echo $type->type; ?></label><br/>
							<?php
						}
					?>
					<hr/>
					<h4>Branch</h4>
					<?php
						while ($location = $allLocations->fetch(PDO::FETCH_OBJ)) {
							$check = '';
							if(isset($locations)){
								if(in_array($location->location,$locations)){
									$check = 'checked';
								}
							}
							?>
								<label class="checkbox-inline"><input class='locations' type="checkbox" <?php echo $check; ?> name='locations[]' value='<?php echo $location->location; ?>'> <?php echo $location->location; ?></label><br/>
							<?php
						}
					?>
					<hr/>
					<h4>Rating</h4>
					<?php
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
					<div class="input-group stylish-input-group">
						<input type="submit" class="form-control btn-primary" value="Apply">
					</div>
        </div>
				<div class='col-md-1'>
        </div>
        <div class='col-md-8' id = 'restaurant'>
        <!-- Portfolio Item Row -->
        <?php
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
					//$openedClosed = "<h4 style='text-align:center;color:green'>Close</h4>";
          try {
            require('scripts/connection.php');
            $rid = $row->rid;
            //get types
            $sql = "select * from restaurantstypes where rid = ?";
            $types = $db->prepare($sql);
            $types->execute(array($rid));
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

              <div class="col-md-6">
                <h3 class="my-3"><?php echo $row->restaurantname; ?></h3>
                <h4 class="my-3"><?php echo $row->phone; ?></h4>
                <h4 class="my-3"><?php echo $row->email; ?></h4>
                <h4 class="my-3">
                  <?php
                    $i = 1;
                    $count = $types->rowCount();
                    while ($type = $types->fetch(PDO::FETCH_OBJ)) {
                      echo "$type->type ";
                      if ($i != $count) {
                        echo ", ";
                      }
                      $i++;
                    }
                  ?>
                </h4>
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
			<div class="col-md-3">
				<br/><br/><br/><br/>
				<div class="input-group stylish-input-group">
						<a href='adminDeleteRestaurant.php?Rid=<?php echo $row->rid;?>'><button type="button" class="btn btn-danger btn-sm">Delete</button></a>
				</div>
			</div>
        </div></a>
        <hr/>
      <?php
      }
    ?>
    </div>
		<div class='col-md-3'>
		</div>
		<div class='col-md-1'>
		</div>
		<!-- paging -->
		<?php
			$url = $_SERVER['REQUEST_URI'];
		?>
		<div class='col-md-8'>
			<nav aria-label="Page navigation example">
				<ul class="pagination">
					<?php
						for($i = 1; $i <= $pages; $i++){
							?>
								<li class="page-item <?php if($i == $p) echo 'active';?>"><input name = 'p' type='submit' class="page-link" value="<?php echo $i;?>"/></li>
				<?php } ?>
				</ul>
			</nav>
		</div>
  </div>
</form>
      <!-- /.row -->
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
