<?php
	//This file is the server-side script for adding a new user to the database. If the database name, specified in 'mysqlParams.php', can't be found, then it will make it. In addition, the entered password will be hashed and then sent to the database.
	
	include "../mysqlParams.php"; //Data about mysql server
	include_once "../safteycheck.php"; //Function for sanitation
	
	$adminPassword = "test"; // To prevent unauthorized people from signing up, this admin password was created. Change it to whatever.
	$error = false;
	
	function createdir($path) {
		mkdir($path);
		chmod($path, 0776);
	}
	
	if (isset($_POST["username"]) && isset($_POST["password"]) && $_POST["adminPassword"] == $adminPassword && $_POST["password"] == $_POST["password2"] && userentered_stringCheck($_POST["username"]) && mysqloptions::$use_database == true) {
		
		$conn = mysqli_connect(mysqloptions::$servername, mysqloptions::$username, mysqloptions::$password, mysqloptions::$dbname);
		
		if (!$conn) {//database does not exist (or incorrect info added), so add new database assuming entered in info is correct
			$conn = mysqli_connect(mysqloptions::$servername, mysqloptions::$username, mysqloptions::$password, "mysql"); //if database doesnt exist, go to mysql
			mysqli_query($conn, "CREATE DATABASE `" . mysqloptions::$dbname . "`"); //Add the database
			$conn = mysqli_connect(mysqloptions::$servername, mysqloptions::$username, mysqloptions::$password, mysqloptions::$dbname); //connect to the originally desired database
			mysqli_query($conn, "CREATE TABLE `user_login` (
								`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
								`username` VARCHAR(255) NOT NULL, 
								`password` VARCHAR(255) NOT NULL, 
								`userdirectory` VARCHAR(255) NOT NULL);"); //add the required tables
		}
					
		$output = mysqli_query($conn, "SELECT `username` FROM `user_login` WHERE `username` = '" . $_POST["username"] ."';"); //username is a unique key, so see if one already exists

		if ($output->num_rows == 0) { //if no instance of the username is on database
			$hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT); //hash the password
			
			$output = mysqli_query($conn, "INSERT INTO `user_login`(`username`, `password`, `userdirectory`) VALUES ('" . $_POST["username"] ."', '" . $hashed_password . "', 'uploads/" . $_POST["username"]. "');"); //Get username/password from database if it exists with entered in credentials

			if ($output) { //if mysql responds back, continue
				$filepath = "../uploads/" . $_POST["username"];
				$trash = "../uploads/" . $_POST["username"] . "/Trash";
				
				createdir($filepath);
				createdir($trash);
				$message = "<p>Sign-up sucsessful!</p>";
			} else {
				$message = "<p>Database error.</p>";
				$error = true;
			}
			
		} else {
			$message = "<p>There is already a username of <b>" . $_POST["username"] . "</b> being used.</p>";
			$error = true;
		}
		
	} else if ($_POST["adminPassword"] != $adminPassword) {
		$message = "<p>Wrong admin password.</p>";
		$error = true;
	} else if (!mysqloptions::$use_database) {
		$message = "<p>You have turned off database privledges. You'll need to enable this cloud service to use databases.</p>";
		$error = true;
	} else {
		$message = "<p>Check to make sure that you entered in the required fields, and no illegal characters are added.</p>";
		$error = true;
	}

?>

<html style="background: #c17300;">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> <!-- Open Sans Font -->
		<link rel="stylesheet" href="../index.css">
    </head>
    <body>
		<div class="container">
			<div class="verticalAdjust">
			<div>
				<img class='cloud' src='../Assets/SVG/cloud.svg'/>
			</div>
				<div id="passform">
					<?php 
						if ($error == true)
							echo $message . "<p id='welcomeText'>If redirect doesn't work, <a href='./newUserIndex.php'>click here</a>.</p>";
						else
							echo $message . "<p id='welcomeText'>If redirect doesn't work, <a href='../index.php'>click here</a>.</p>";
					?>
				</div>
			</div>
		</div>
    </body>
    <script type="text/javascript" src="../index.js"></script>
</html>

<script>
	setTimeout(function redirect() {
		window.location.href = <?php if($error == true) {echo "'./newUserIndex.php'";} else { echo "'../index.php'";} ?>;
	}, 3000);
</script>
