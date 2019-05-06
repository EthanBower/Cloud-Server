<?php
//This script displays the directory contents of the server, and creates a link using the <a> tag for files. 

include "checkpass.php";
include_once 'filesizes.php'; //this file contains functions needed to get file size

if (!is_dir("uploads")) { //if uploads doesnt exisit, make a new directory
	mkdir("uploads", 0777);
}

$i = 0;

$selectall = $_SESSION["selectall"]; //find whether to selectall or not
$directory = $_SESSION["directory"]; //get dir

function directory_scan($directory, $selectall) { // This function scans for every file in the directory, then places the results in HTML format. 
$scanned_directory = array_diff(scandir($directory), array("..", ".")); //This removes all of the unessesary results

for ($i = 0; $i < count(scandir($directory)); $i = $i + 1) {
	if($directory == "uploads/" . $_SESSION["username"] && $i == 0) { //display trash if on home directory upon first iteration of loop
		if (!is_dir("uploads/" . $_SESSION["username"] . "/Trash")) {
			mkdir("uploads/" . $_SESSION["username"] . "/Trash");
		}
		echo "<div class='folderDiv' id='trash'>";
		echo "<div>";
		echo "<img src='Assets/SVG/bin.svg' class='foldersvg'/>";
		echo "<form action='moveto_dir.php' method='post' class='folderLink' enctype='multipart/form-data'>";
		echo "<input type='submit' class='folderButton' value='Trash' name='directory[-1]'>";
		echo "</form>";
		echo "</div>";
		
		echo "<p class='folderinfo'><b>Size</b>: " . getfilesize($directory . "/Trash", 1) . " <b>Last Modified</b>: " . date("F d Y H:i:s.",filectime($directory . "/Trash"));
		
		echo "</div>"; 
	}
	
	if (isset($scanned_directory[$i]) && (is_dir($directory . "/" . $scanned_directory[$i]) == FALSE)) { // If current item is a file, make it a download link
		echo "<div class='fileDiv' id='fileDivID_" . $i . "'>";
		echo "<div>";
		if ($selectall == "1") { // if "select all" button is pressed. both inputs point to the 'delete' form
			echo "<input type='checkbox' id='checkboxID_" . $i . "' class='modify' name='edit[" . $scanned_directory[$i] . "]' form='delete' onclick='checkboxclick(\"fileDivID_". $i . "\", \"fileRenameID_". $i . "\", \"checkboxID_". $i . "\");' checked>";
		} else {
			echo "<input type='checkbox' id='checkboxID_" . $i . "' class='modify' name='edit[" . $scanned_directory[$i] . "]' form='delete' onclick='checkboxclick(\"fileDivID_". $i . "\", \"fileRenameID_". $i . "\", \"checkboxID_". $i . "\");'>";
		}
		echo "<img src='Assets/SVG/file.svg' class='foldersvg'/>";
		echo "<p class='fileLink'><a href='" . strstr($directory, "uploads") . "/" . $scanned_directory[$i] . "' download>" . $scanned_directory[$i] . "</a></p><p class='fileLink'><a class='preview' href='" . strstr($directory, "uploads") . "/" . $scanned_directory[$i] . "'>Preview</a></p>";
		echo "</div>";
		
		echo "<p class='fileinfo'><b>Type</b>: " . mime_content_type($directory. "/" . $scanned_directory[$i]) . " <b>Size</b>: " . getfilesize($directory . "/" . $scanned_directory[$i], 0) . " <b>Last Modified</b>: " . date("F d Y H:i:s.",filectime($directory. "/" . $scanned_directory[$i]));
		echo " <input type='submit' id='fileRenameID_" . $i . "' class='renameButton' value='Rename' name='directory[" . $i . "]' onclick = 'openModify(\"renameform" . $i . "\", this)'></p>";
		
		echo "<form action='rename.php' method='post' class='forms forms3' enctype='multipart/form-data' id='renameform" . $i . "'>"; // this is the rename form
		echo "<input type='text' name='folderrename_name[" . $i . "]' placeholder='New Name' class='textBox'>";
		echo "<input type='submit' value='Create' name='rename' class='inputButton' style='margin-top: 5px;'>";
		echo "<input type='checkbox' class='modify' name='maintainext' style='display:inline-block;' checked>";
		echo "<p class='fileinfo' style='margin: 0px;' >Maintain Current Extension</p>";
		echo "</form>";
		
		echo "</div>";
		
		if ($selectall == "1") {
			echo "<script>document.getElementById('fileDivID_" . $i . "').style.background = '#abdfff'; document.getElementById('fileRenameID_" . $i . "').style.background = '#abdfff';</script>"; //since JS is not loaded yet when the select all button is pressed, this script is here to highlight everything
		}
	} else if (isset($scanned_directory[$i]) && $directory . "/" . $scanned_directory[$i] != "uploads/" . $_SESSION["username"] . "/Trash") { // Makes this a special dir box if a item is folder. Dont re-scan trash folder
		echo "<div class='folderDiv' id='folderDivID_". $i . "'>";
		echo "<div>";
		if ($selectall == "1") { // if "select all" button is pressed
				echo "<input type='checkbox' id='checkboxID_" . $i . "' class='modify' name='edit[" . $scanned_directory[$i] . "]' form='delete' onclick='checkboxclick(\"folderDivID_". $i . "\", \"folderRenameID_". $i . "\", \"checkboxID_". $i . "\");' checked>";
		} else {
				echo "<input type='checkbox' id='checkboxID_" . $i . "' class='modify' name='edit[" . $scanned_directory[$i] . "]' form='delete' onclick='checkboxclick(\"folderDivID_". $i . "\", \"folderRenameID_". $i . "\", \"checkboxID_". $i . "\");'>";
		}
		echo "<img src='Assets/SVG/folder.svg' class='foldersvg'/>";
		echo "<form action='moveto_dir.php' method='post' class='folderLink' enctype='multipart/form-data'>";
		echo "<input type='submit' class='folderButton' value='" . $scanned_directory[$i] . "' name='directory[" . $i . "]'>";
		echo "</form>";
		echo "</div>";
		
		echo "<p class='folderinfo'><b>Type</b>: Folder <b>Size</b>: " . getfilesize($directory . "/" . $scanned_directory[$i], 1) . " <b>Last Modified</b>: " . date("F d Y H:i:s.",filectime($directory. "/" . $scanned_directory[$i]));
		echo " <input type='submit' id='folderRenameID_" . $i . "' class='renameButton' value='Rename' name='directory[" . $i . "]' onclick = 'openModify(\"renameform" . $i . "\", this)'></p>";
		
		echo "<form action='rename.php' method='post' class='forms forms3' enctype='multipart/form-data' id='renameform" . $i . "'>"; // this is the rename form
		echo "<input type='text' name='folderrename_name[" . $i . "]' placeholder='New Name' class='textBox'>";
		echo "<input type='submit' value='Create' name='rename' class='inputButton' style='margin-top: 5px;'>";
		echo "</form>";
		
		echo "</div>"; 
		
		if ($selectall == "1") {
			echo "<script>document.getElementById('folderDivID_" . $i . "').style.background = '#abdfff'; document.getElementById('folderRenameID_" . $i . "').style.background = '#abdfff';</script>"; //since JS is not loaded yet when the select all button is pressed, this script is here to highlight everything
		}
	}
}

}

if (is_dir($directory)) { // if the current directory exists, do a scan
	directory_scan($directory, $selectall);
} else {
	$_SESSION["directory"] = "uploads/" . $_SESSION["username"];
	$directory = $_SESSION["directory"];

	directory_scan($directory, $selectall);
}

?>
