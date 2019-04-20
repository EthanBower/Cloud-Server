<?php
// This script allows the user to re-name a folder or file upon request. 
//IMPORTANT: the form that activates this is NOT generated in the main index2.php file. It is instead generated in show_dir.php.


session_start();
include "checkpass.php";
include_once 'safteycheck.php';

foreach($_POST['folderrename_name'] as $i => $item) { // returns $item as form value, which is the new folder name. $i is iteration count
		if (!empty($_POST['folderrename_name'][$i]) && userentered_stringCheck($_POST['folderrename_name'][$i]) == true) { //if the post array is not empty, then it must be the folder/file the user want to rename. every other form will return NULL.
			
		//get current directory
		$directory = $_SESSION["directory"];
		
		$scanned_directory = array_diff(scandir($directory), array("..", ".")); //This removes all of the unessesary results
		
		//add folder
		if (!file_exists($directory . "/" . $item)) { //if folder/file name does not exist in directory, add it in
			
			if (isset($_POST['maintainext'])) { //if 'maintain current extension' is checked, get the extension of the soon-to-be renamaned file
				$item = $item . "." . end(explode(".", $scanned_directory[$i]));
			}
			
			rename($directory . "/" . $scanned_directory[$i],$directory . "/" . $item);
			header("Location: index2.php");
		} else {
			echo "Error: File already exists.<br>";
		}
		} else {
			echo "Error: Something went wrong. Check for illegal characters being entered.";
		}
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
