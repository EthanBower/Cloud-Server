<?php
	//This script places the current directory onto the webpage for the client to see for easy navigation. It artifically replaces the directory name 'uploads' with home.
	//This script also contains relevent info of the directory, like how many files are in it and how much space is being used
	
include "checkpass.php";
include_once 'filesizes.php'; //this file contains functions needed to get file size

$directory = $_SESSION["directory"];
$fileCount = count(scandir($directory)) - 2; //get the amount of files in directory. since scandir includes "." and "..", subtract 2
	
echo "<p id='current_dir'>Home" . substr($directory, strlen("uploads/" . $_SESSION["username"])) . "</p>"; //get rid of "uploads" in directory string.
echo "<p id='filecount' style='margin-bottom:0px;'><b>Total items</b>: " . $fileCount . "</p>";
echo "<p id='filecount'><b>Total Folder Size</b>: " . getfilesize($directory,1) . "</p>";
	//echo "Basename: " . basename($directory) . "<br>";
?>
