<?php
	//This script contains saftey guards for when the user possibly enters in an illegal character. This prevents serverside issues, as well as clientside if a file with any of these contents are downloaded
	
	function userentered_stringCheck($userinput) {
		if(strpos($userinput, "../") !== false) {
			return false;
		} else if(strpos($userinput, "\"") !== false) {
			return false;
		} else if(strpos($userinput, "'") !== false) {
			return false;
		} else if(strpos($userinput, "/") !== false) {
			return false;
		} else if(strpos($userinput, "?") !== false) {
			return false;
		} else if(strpos($userinput, "<") !== false) {
			return false;
		} else if(strpos($userinput, ">") !== false) {
			return false;
		} else if(strpos($userinput, ":") !== false) {
			return false;
		} else if(strpos($userinput, "|") !== false) {
			return false;
		} else { //If input doesnt have any illegal characters, then return true
			return true;
		}
	}
?>
