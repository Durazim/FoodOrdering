<?php
	extract($_GET);
	try {
		require('scripts/connection.php');
		$pending = $db->prepare("update restaurants set accepted='y' where rid=?");
		$pending->execute(array($rid));
		$db = null ;
	}
	catch (PDOException $ex) {
        die($ex->getMessage());
    }
	header('location:adminIndex.php');
?>