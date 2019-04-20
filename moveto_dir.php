<?php
//This script moves the client to a new directory branch upon request. It does this by looking at directory_status.txt and adding in the folder name that the client clicked on


session_start();
include "checkpass.php";

foreach($_POST['directory'] as $item) // returns $item as form value, which is folder name
{
	//Get directory path, and add folder name to the end of it
	$directory = $_SESSION["directory"] . "/" . $item;
	
	//Write the new directory path to the folder.
	if(is_dir($directory)) { //if it isnt a dir, dont write it to session
		$_SESSION["directory"] = $directory;
		$_SESSION["selectall"] = "0"; //if the client has selected all, turn it off when switching to new dir
	}

	//Go back to index2.php
	header("Location: index2.php");	
}
?>
