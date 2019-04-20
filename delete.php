<?php
//this script deletes everything that is selected by client. Deleting files is straight-forward, but deleting a directory branch with other folders/files in it is not.
//I managed deleting directory branches by recursion. If the user selects a folder to be deleted, the function remove_path() will look into that folder and delete the files it sees in there.
//If it see an other folder, it will look into that as well, and will continue to keep looking into every directory until it's cleared.

session_start();
include "checkpass.php";

function remove_path($folder){ // Since you cannot remove a folder when files are in it with a single command, a function like this needs to be made
    $files = glob( $folder . '/*'); //get array of everything in child directory of $folder
    foreach( $files as $file ){
        if($file == '.' || $file == '..'){continue;} // ignore files with those names since scan_dir will output this
        if(is_dir($file)){ // if a folder, inside a folder, is being scanned, go into that folder and scan it's contents. Recursion!
            remove_path($file);
        }else{ // if the target file is actually a file, delete it
            unlink($file);
        }
    }
    rmdir($folder); //once all done, delete the main folder
}

if (!empty($_POST['edit'])) {
	foreach($_POST['edit'] as $item => $n) { //$item returns app name, $n is checkbox status
		$directory = $_SESSION["directory"] . "/" . $item;
		
		if($_SESSION["directory"] == "uploads/Trash") { //if in trash, actually delete the files
			if (is_dir($directory)) {
				remove_path($directory);
			} else {
				unlink($directory);
			}
		} else { //if not in trash, move to trash
			rename($directory, "uploads/Trash/" . $item);
		}
		
		//echo count($_POST['edit']); //get the amount of files selected
		header("Location: index2.php");	
	}
	
} else {
	echo "Failed to start deleting.<br>";
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
