<?php
	//This script checks for password validation. Used in the password login page.
	include "mysqlParams.php";
	
	session_start();
	
	$customusername = "localadmin"; //USE ONLY WHEN SQL SERVER IS DISABLED IN "mysqlParams.php"
	$custompassword="password"; //USE ONLY WHEN SQL SERVER IS DISABLED IN "mysqlParams.php"
	$_SESSION['maxLoginAttempts'] = 3;
	$_SESSION['clientReject_duration'] = 600; //timeout after 10 min
	if (!isset($_SESSION['loginReject'])) { //if not set, make it false. This is here so that loginReject will be defined false only once
		$_SESSION['loginReject'] = false;
	}

	function initializeLogin($directory, $username) { //This function sets up the user's session
		$_SESSION['LAST_ACTIVITY'] = $_SERVER['REQUEST_TIME']; // update last activity time stamp. This way, the user will NOT be logged out when logging in for the first time
		$_SESSION["passAttemptCount"] = 0; //reset failed password attempts
		$_SESSION["loginStatus"] = "logged"; //determines if user logged in
		$_SESSION["directory"] = $directory; //sets initial directory
		$_SESSION["selectall"] = "0"; //sets all files to not be initially selected 
		$_SESSION["timeoutCheck"] = false; //set timeout parameter. this is used for determining if client times out
		$_SESSION["username"] = $username;
	}
	
	if (mysqloptions::$use_database) {
		$conn = mysqli_connect(mysqloptions::$servername, mysqloptions::$username, mysqloptions::$password, mysqloptions::$dbname);
		$username = mysqli_real_escape_string($conn, $_POST["username"]); //for security, make sure there is an escape string
		$output = mysqli_query($conn, "SELECT `username`, `password`, `userdirectory` FROM `user_login` WHERE `username` = '" . $username . "'"); //Get username/password from database if it exists with entered in credentials
		$output = mysqli_fetch_assoc($output);
	} else {
		$output["username"] = $customusername;
		$output["password"] = $custompassword;
		$output["userdirectory"] = "uploads/" . $customusername;
	}
	
	//The below statement says "if there is something in $output, AND ((the database is enabled AND the hashed password is correct) OR (the database is disabled AND the non-hashed passwords are correct AND the username matches)) AND the user hasnt entered the incorrect password a few times in a row"  
	if(count($output) != 0 && ((mysqloptions::$use_database && password_verify($_POST["password"], $output["password"])) || (!mysqloptions::$use_database && $_POST["password"] == $output["password"] && $_POST["username"] == $output["username"])) && $_SESSION['loginReject'] == false) {
		if (!is_dir("./uploads")) { //if the "uploads" directory doesnt exist for whatever reason, make one
			mkdir("./uploads");
			chmod("./uploads", 0775);
		}
		
		if (!is_dir($output["userdirectory"])) { //if user is in database, but directory is not on file, create a location
			mkdir($output["userdirectory"]);
			chmod($output["userdirectory"], 0777);
		}
		
		initializeLogin($output["userdirectory"], $output["username"]);
		header("Location: index2.php");
	} else if (count($output) == 0) { //If connection to database fails
		echo "Service couldn't be reached.";
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

<html>
	<p>If redirect doesn't work, <a href="/index.php">click here</a>.</p>
</html>

<script>
	setTimeout(function redirect() {
		window.location.href = '/index.php';
	}, 30000);
</script>
