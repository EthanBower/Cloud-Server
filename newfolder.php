<?php
	//This script adds a new folder upon client command.


session_start();
include "checkpass.php";
include_once "safteycheck.php";

	if (isset($_POST["addnewfolder"]) && $_POST["foldername"] != "") {
		//get current directory
		$directory = $_SESSION["directory"];
		
		//add folder 
		if (!file_exists($directory . "/" . $_POST["foldername"]) && userentered_stringCheck($_POST["foldername"]) == true) { //if folder name does not exist in directory, add it in. also check for '../' so that user cant place folder outside of uploads
		mkdir($directory . "/" . $_POST["foldername"], 0777);
		header("Location: index2.php");
		} else { //if folder name does exist, then throw error to prevent overwriting it
		echo "Error: Folder already exists, or illegal characters were added.";
		}
	} else {
		echo "Error: Cannot leave folder name blank.<br>";
	}
		
?>
<html>
	<p>If redirect doesn't work, <a href="index2.php">click here</a>.</p>
</html>
<script>
	setTimeout(function redirect() {
		window.location.href = '/index2.php';
	}, 3000);
</script>
