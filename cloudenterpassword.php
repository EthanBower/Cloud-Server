<?php
//This script checks for password validation. Used in the password login page. Here is where you can change the password 
session_start();

$custompassword="swag";
$_SESSION['maxLoginAttempts'] = 3;
$_SESSION['clientReject_duration'] = 600; //timeout after 10 min
if (!isset($_SESSION['loginReject'])) { //if not set, make it false. This is here so that loginReject will be defined false only once
	$_SESSION['loginReject'] = false;
}

function initializeLogin() {
	$_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME']; // update last activity time stamp. This way, the user will NOT be logged out when logging in for the first time
	$_SESSION["passAttemptCount"] = 0; //reset failed password attempts
	$_SESSION["loginStatus"] = "logged"; //determines if user logged in
	$_SESSION["directory"] = "uploads"; //sets initial directory
	$_SESSION["selectall"] = "0"; //sets all files to not be initially selected 
	$_SESSION["timeoutCheck"] = false; //set timeout parameter. this is used for determining if client times out
}

/*
if("pass" == $_POST["password"]) { //this is to reset the timer upon too many failed password attempts for development
	$_SESSION["passAttemptCount"] = 0;
	$_SESSION['LAST_ACTIVITY'] = 0;
	$_SESSION['loginReject'] = false;
}
*/

if($custompassword == $_POST["password"] && $_SESSION['loginReject'] == false) {
	initializeLogin();
	header("Location: index2.php");
} else { //this will keep track of how many times the client enters in the password incorrectly
	if(!isset($_SESSION["passAttemptCount"])) {
		$_SESSION["passAttemptCount"] = 1;
	} else {
		$_SESSION["passAttemptCount"] += 1;
	}
	
	if($_SESSION["passAttemptCount"] >= $_SESSION['maxLoginAttempts']) { //if client tried to log in too many times

        $time = $_SERVER['REQUEST_TIME'];
        $_SESSION['loginReject'] = true;
        
        if ($_SESSION["passAttemptCount"] == ($_SESSION['maxLoginAttempts']) || !isset($_SESSION['LAST_ACTIVITY'])) { //this sets LAST_ACTIVITY to the current time, but only once
			$_SESSION['LAST_ACTIVITY'] = $time;
		}
		
        if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $_SESSION['clientReject_duration']) { //if timeout is expired, continue
            $_SESSION['loginReject'] = false; //if timeout occurs, make this set to true. This will pass to welcome.php and will reset it 
            if ($custompassword == $_POST["password"]) { 
				initializeLogin();
				header("Location: index2.php");
			} else {
				$_SESSION["passAttemptCount"] = 1;
				header("Location: index.php");
			}
        } else { //if client logged in too many times, but timeout has not expired
			header("Location: index.php");
		}
			 
	} else { //if client has failed logging in, but not failed too much
		header("Location: index.php");
	}	
}

?>
