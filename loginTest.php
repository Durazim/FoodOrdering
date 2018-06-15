<?php
	session_start();
	if(isset($_SESSION['login'])){
		$arr[0] = $_SESSION['login'][0];
		$arr[1] = $_SESSION['login'][1];
		$arr[2] = $_SESSION['login'][2];
	}
	else if(isset($_COOKIE['login'])){
		$x = $_COOKIE['login'];
		$arr = explode('#',$x);
	}
?>