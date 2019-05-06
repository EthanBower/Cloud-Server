<!DOCTYPE html>
<html id = "html">
    <head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> <!-- Open Sans Font -->
		<link rel="stylesheet" href="index2.css">
    </head>
	<body onload="scale();" onresize="scale();">
		
<?php
//This script will move the selected files/folders to a user-specified location


session_start();
include "checkpass.php";
include_once 'filesizes.php'; //this file contains functions needed to get file size

$directory = $_SESSION["directory"];

function directory_scan($directory, $withfiles) { // This function scans for every file in the directory, then places the results in HTML format. $withfolders = true means output folders. False is not.
	$scanned_directory = array_diff(scandir($directory), array("..", ".")); //This removes all of the unessesary results

	for ($i = 0; ($i < count(scandir($directory))) && $withfiles == true; $i = $i + 1) {
		if (isset($scanned_directory[$i]) && (is_dir($directory . "/" . $scanned_directory[$i]) == FALSE)) { // If current item is a file, make it a download link
			echo "<div class='fileDiv'>";
			echo "<div>";
			echo "<input type='checkbox' class='modify' name='movefrom[" . $scanned_directory[$i] . "]' form='moveto'>";
			echo "<img src='Assets/SVG/file.svg' class='foldersvg'/>";
			echo "<p class='fileLink'>" . $scanned_directory[$i] . "</p>";
			echo "</div>";
			echo "<p class='fileinfo'><b>Type</b>: " . mime_content_type($directory. "/" . $scanned_directory[$i]) . " <b>Size</b>: " . getfilesize($directory . "/" . $scanned_directory[$i], 0) . " <b>Last Modified</b>: " . date("F d Y H:i:s.",filectime($directory. "/" . $scanned_directory[$i])) . "</p> ";			
			echo "</div>";
		} else if (isset($scanned_directory[$i])) { // Dont output folders
		}
	}
	
	for ($i = 0; ($i < count(scandir($directory))) && $withfiles == false; $i = $i + 1) { //go through this for loop in order to only output files
		if ($i==0 && $directory != "uploads/" . $_SESSION["username"]) { //when $i gets to its first iteration, output the "back a directory" folder IF not currently in the home folder
			echo "<div class='folderDiv'>";
			echo "<div>";
			echo "<input type='checkbox' class='modify' name='moveto[../]' form='moveto'>";
			echo "<img src='Assets/SVG/folder.svg' class='foldersvg'/>";
			echo "<input type='button' class='folderButton' value='../' name='directory[" . $i . "]'>";
			echo "</div>";
			echo "<p class='folderinfo'><b>Info</b>: Selecting this will move your selection back a folder.</p> ";
			echo "</div>";
		}
		
		if (isset($scanned_directory[$i]) && is_dir($directory . "/" . $scanned_directory[$i]) && $directory . "/" . $scanned_directory[$i] != "uploads/". $_SESSION["username"] . "/Trash") { // Makes this a special dir box if an item is folder
			echo "<div class='folderDiv'>";
			echo "<div>";
			echo "<input type='checkbox' class='modify' name='moveto[" . $scanned_directory[$i] . "]' form='moveto'>";
			echo "<img src='Assets/SVG/folder.svg' class='foldersvg'/>";
			echo "<input type='button' class='folderButton' value='" . $scanned_directory[$i] . "' name='directory[" . $i . "]'>";
			echo "</div>";
			echo "<p class='folderinfo'><b>Type</b>: Folder <b>Size</b>: " . getfilesize($directory . "/" . $scanned_directory[$i], 1) . " <b>Last Modified</b>: " . date("F d Y H:i:s.",filectime($directory. "/" . $scanned_directory[$i])) . "</p> ";
			echo "</div>";
		}
	}

}

function copyfile($POSTfrom, $POSTto, $currentdir) { //This function will move the selected files/folders to where the user specifies it
	if(count($POSTto) == 1) { //if the user selected only one "move to" location
		foreach($POSTfrom as $fromItem => $n1) { //$fromItem returns selected file/folder name to be moved, $n is checkbox status
			foreach($POSTto as $toItem => $n2) {//$toItem returns selected folder to have the files put in
				if(!file_exists($currentdir . "/" . $toItem . "/" . $fromItem)) {
					if(copy($currentdir . "/" . $fromItem, $currentdir . "/" . $toItem . "/" . $fromItem)) { //if rename worked, do nothing. Else, echo an error. Since rename() can rename a folder, it is possible to rename a file/folder to its new directory and have linux move it accourdingly
						continue;
					} else if ($fromItem == $toItem) { //if user tries to move the same folder into its self
						echo "<p><b>Error</b>: Could not move file or folder <b>" . $fromItem . "</b>. You cannot move a file into its self. Everything before it was moved.</p>";
						return false;
					} else  {
						echo "<p><b>Error</b>: Could not move file or folder <b>" . $fromItem . "</b>. Everything before it was moved.</p>";
						return false;
					}
				} else {
					echo "<p><b>Error</b>: File already exists in the folder you want to copy it to. Everything before <b>" . $fromItem . "</b> has been moved.</p>";
					echo "<p>Rename file if you want to move it.</p>";
					return false;
				}
			}
		}
	} else {
		echo "<p><b>Error</b>: You selected more than one destination folder, or none at all. Or, you might have not selected anything to move.</p>";
		return false;
	}
	
	return true; // If everything worked out, return true
}

function errortimeout($redirectfile) { //This function allows the client to see an error for 3 seconds before redirect. The one and only parameter is the redirect destination
	echo "<p>If redirect doesn't work, <a href='" . $redirectfile . "'>click here</a>.</p>"; 
	echo "<script>
			setTimeout(function redirect() {
				window.location.href = '/" . $redirectfile . "';
			}, 6000);
		  </script>";
}

if (is_dir($directory)) { // if the current directory exists, do a scan
	if (!isset($_POST["move"]) && !isset($_POST["gohome"])) { //if move button has not been pressed, display directory contents to be moved
		echo "<div id='topcontainer_moveto'>";
		
		include "current_dir.php";//Display the current directory
		
		echo "<form action='copyto_file.php' method='post' id='movetoHome' enctype='multipart/form-data'>"; //display form button
		echo "<input type='submit' class='folderButton' value='Back' name='gohome'>";
		echo "</form>";
		echo "</div>";
		
		
		echo "<div id='movecontainermaster'><div id='movecontainer'>"; //movecontainermaster is to set a max height
		echo "<div id='movefromcontainer'><p class='movetext'>Item to copy:</p>";
		directory_scan($directory, true);
		echo "</div>";
		
		echo "<div id='movetoarrowcontainer'><img src='Assets/SVG/arrow.svg'/></div>";
		
		echo "<div id='movetocontainer'><p class='movetext'>Folder to copy to:</p>";
		directory_scan($directory, false);
		echo "</div>";
		echo "</div></div>";
		
		echo "<div id='bottomcontainer_moveto'>";
		echo "<form action='copyto_file.php' method='post' id='moveto' enctype='multipart/form-data'>"; //display form button
		echo "<input type='submit' class='folderButton' value='Copy' name='move'>";
		echo "</form>";
		echo "</div>";
	} else if (isset($_POST["move"])){
		if(copyfile($_POST["movefrom"], $_POST["moveto"], $directory) == true) { // parameters: (from, to, currentdir)
			header("Location: index2.php");	//if the movefile function worked, return back to normal page
		} else { //if it didnt work out, display error (given by the movefile function) and wait 3 sec before returning home
			errortimeout("copyto_file.php");
		}
	} else if (isset($_POST["gohome"])) {
		header("Location: index2.php");
	}
} else { //if not dir
	echo "<p><b>Error</b>: Current directory does not exist.</p>";
	errortimeout("index2.php"); //go home
}
?>

</body>
<script>
	function scale() {
		var html = document.getElementById("html"),
		top = document.getElementById("topcontainer_moveto"),
		mid = document.getElementById("movecontainermaster"),
		bottom = document.getElementById("bottomcontainer_moveto");
		
		if (mid.offsetHeight >= 330) { // This make it so that the selection window (and everything else) will always fit in the viewport
			mid.style.height = html.offsetHeight - top.offsetHeight - bottom.offsetHeight - 35 + "px";
		} 
		
		if (mid.offsetHeight < 330) { //If the selection window gets too small, make it a set height. Even though it will no longer fit the viewport
			mid.style.height = "330px";
		}
	}
</script>
</html>
