<?php
//this script contains the necessary functions for obtaining the file size for specified files

include "checkpass.php";

function getfilesize($dir, $fileorfolder) { //since filesize returns the file in bytes, there needs to be a conversion. This function will check the filesize of a specific file, and then round it to the correct units by 2 decimal places
	//So there is a huge standardiztion flaw in electronic storage sizes. Some companies use the binary IEC units, whereas others use decimal units. Im using the latter because my computers happen to read these uploaded files as them, in which i feel would be a more accurate representation of file size for many people as a result.
	// $fileorfolder = 0 means file, $fileorfolder = 1 means folder. This parameter used to matter, but after a few revious of this php file, it no longer does
	
	if ($fileorfolder == 0 && file_exists('/usr/bin/du')) { // if the current dir is a file, and if server is linux-based. Otherwise, it cant get size
		//$dirfilesize = filesize($dir);
		$io = popen('/usr/bin/du -sk ' . $dir, 'r'); //Debian has a folder in which it can caluculate the directory size, located in /usr/bin, with filename du. This line of code directs the file du to output the filesize in the specified directory.
		$size = fgets($io, 4096); //The size is in KB
		$size = substr($size, 0, strpos($size, "\t"));
		return convertsize($size * 1000);
	} else if ($fileorfolder == 1 && file_exists('/usr/bin/du')) { //file_exists checks to see if file exists. This is here so that there is no error if this cloud server is running on a non-linux host
		$io = popen('/usr/bin/du -sk ' . $dir, 'r'); //Debian has a folder in which it can caluculate the directory size, located in /usr/bin, with filename du. This line of code directs the file du to output the filesize in the specified directory.
		$size = fgets($io, 4096); //The size is in KB
		$size = substr($size, 0, strpos($size, "\t"));
		pclose($io);
		return convertsize($size * 1000);
	} else {
		return "Unknown";
	}
}

function convertsize($dirfilesize) {// This takes the results from the function above, and converts it to something more readable other than bytes
	if ($dirfilesize < 1000) {
		return round($dirfilesize, 2) . "B";
	} else if ($dirfilesize >= 1000 && $dirfilesize < pow(1000,2)) {
		return round($dirfilesize/1000, 2) . "KB";
	} else if ($dirfilesize >= pow(1000,2) && $dirfilesize < pow(1000,3)) {
		return round($dirfilesize/pow(1000,2), 2) . "MB";
	} else if ($dirfilesize >= pow(1000,3) && $dirfilesize < pow(1000,4)) {
		return round($dirfilesize/pow(1000,3), 2) . "GB";
	} else if ($dirfilesize >= pow(1000,4) && $dirfilesize < pow(1000,5)) {
		return round($dirfilesize/pow(1000,4), 2) . "TB";
	} else {
		return "Unknown";
	}
}
?>
