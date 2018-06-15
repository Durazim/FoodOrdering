<?php
	if(isset($arr)){
		if($arr[0] == 'customer')
			require('navbar/customerNav.php');
		else if($arr[0] == 'restaurant')
			require('navbar/restaurantNav.php');
	}
	else
		require('navbar/mainNav.php');
?>