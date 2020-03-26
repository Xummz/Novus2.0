<?php

	session_start();

	if(!isset($_SESSION["UserId"]) || empty($_SESSION['UserId'])) {
		header('Location: Login.php');
	}
	
?>