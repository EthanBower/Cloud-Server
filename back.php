<?php
	//This script is allows the user to go back on a directory. It does it by getting the current directory path, then slicing off the last bit.
	
	
	session_start();
	include "checkpass.php";
	
	$directory = $_SESSION["directory"];

	if(strstr(basename($directory), "uploads") == TRUE) { //stop moving back if "uploads" is current directory
		//Nothing
	} else if (is_dir($_SESSION["directory"])) {
	$directory = dirname($directory);
	
	//This updates the directory
	$_SESSION["directory"] = $directory;
	$_SESSION["selectall"] = "0";
	
	} else {
		$_SESSION["directory"] = "uploads";
		$_SESSION["selectall"] = "0";
	}

	header("Location: index2.php");//go back to the main page. the main page will reload show_dir.php which will update the results
?>
