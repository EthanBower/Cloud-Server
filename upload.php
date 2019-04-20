<?php
//This script is responsible for uploading the documents. It is capable of uploading multiple files at a time, and will output sucess for each, then redirect in 3 seconds.
//Planned updates: include folders in upload
//		   Scan for errors first before uploading


session_start();
include "checkpass.php";
include_once "safteycheck.php";
include "filesizes.php";

$i = 0;
$totalSize = 0;
$uploadOk = 1;
$target_dir = $_SESSION["directory"];

for($i = 0; $i < count($_FILES["fileToUpload"]["name"]); $i = $i + 1) { // this does a check of all files before moving them
    $target_file = $target_dir . "/" .  basename($_FILES["fileToUpload"]["name"][$i]);
    $totalSize = $totalSize + $_FILES["fileToUpload"]["size"][$i];

    // Check file size
    if ($totalSize > (100 * pow(10,6))) { // 100M is the max upload size
	echo "<b>Error</b>: Upload is too large. Max upload is: 100MB.<br>";
	$uploadOk = 0;
    }

    if (userentered_stringCheck($_FILES["fileToUpload"]["name"][$i]) == false) { //check for the use of special characters before actually moving the file over
	echo "<b>Error</b>: Illegal characters were used.<br>";
	$uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
	echo "Nothing was uploaded. This file is the current issue:";
	echo "<br> Location: " .  $target_file;
	echo "<br> Basename is: " . basename($_FILES["fileToUpload"]["name"][$i]);
	$i = count($_FILES["fileToUpload"]["name"]); //stop for loop
    }

}

for($i = 0; $i < count($_FILES["fileToUpload"]["name"]) && $uploadOk == 1; $i = $i + 1) { //if upload is good. seperating this in two different for loops allows the script to only upload when all rules are checked for
    $target_file = $target_dir . "/" .  basename($_FILES["fileToUpload"]["name"][$i]);
    
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_file)) { //move the files
	echo "The file <b>". basename($_FILES["fileToUpload"]["name"][$i]) . "</b> has been uploaded.<br>";
	//chmod($target_file, 644); // Makes it so that root can read/write, others can only read
	//echo $totalSize/(1 * pow(10,6)) . "<br>";
    } else {
	echo "<b>Sorry, there was an error uploading this file:</b>" . basename($_FILES["fileToUpload"]["name"][$i]) . "<br>";
    }
    
    if ($i == count($_FILES["fileToUpload"]["name"]) - 1) { //if the for loop is at its last iteration, display total upload size
	echo "<b>Total Upload size</b>: " . convertsize($totalSize) . "<br>";
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
