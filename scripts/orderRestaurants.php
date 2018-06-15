<?php
  extract($_GET);
  //page1
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
      require('connection.php');
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
      $sql = "select * from restaurants where restaurantname like ? $ridInSql $ridInSqlBranch and rating >= ? $orderby limit 0,10";
      $stmt = $db->prepare($sql);
      $stmt->execute(array("$search%",$rateInput));
			$pages = $db->prepare("select * from restaurants where restaurantname like ? $ridInSql $ridInSqlBranch and rating >= ? $orderby ");
			$pages->execute(array("$search%",$rateInput));
      $db = null;
    }
    catch (PDOException $ex) {
        die($ex->getMessage());
    }
?>

<!-- Portfolio Item Row -->
<?php
while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
  //$openedClosed = "<h4 style='text-align:center;color:green'>Close</h4>";
  try {
    require('connection.php');
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

      <div class="col-md-9">
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
</div></a>
<hr/>
<?php
}
?>
