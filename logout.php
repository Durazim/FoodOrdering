<?php
	session_start();
	if(isset($_SESSION['login']))
		unset($_SESSION['login']);
	if (isset($_COOKIE['login']))
		setcookie("login","",time() - 3600);
		unset($_SESSION['order']);
	header('location:index.php');
?>
