<?php
	//This script simply refreshes the main page by getting redirected to this script, and then redirected back. 
	//While JS is slightly easier to refresh the page, this PHP method makes it so that JS isn't required, which is preferred for better compatibilty across many devices
	

	session_start();
	include "checkpass.php";
	$_SESSION["selectall"] = "0";
	header("Location: index2.php");
?>
