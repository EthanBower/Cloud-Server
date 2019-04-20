<?php
	//This script will log the user out

	session_start();
	
	if(isset($_SESSION["loginStatus"])) {
		session_destroy();
	}
	
	header("Location: index.php");
?>
