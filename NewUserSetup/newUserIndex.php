<?php
    //This is the new user signup page
    session_start();    
?>

<!DOCTYPE html>
<html id="html" style="background: #c17300;">
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
		    <p id="welcomeText">Sign up here!</p>
		    <form action="../index.php" method="post" enctype="multipart/form-data" style="text-align: center;" id="back"></form>
		    <form action="newUserServerSetup.php" method="post" enctype="multipart/form-data" style="text-align: center;">
			<input placeholder="Desired Username" name="username" id="username" multiple="multiple">
			<input type="password" placeholder="Password" name="password" id="password" multiple="multiple">
			<input type="password" placeholder="Re-Enter Password" name="password2" id="password" multiple="multiple">
			<div>
			    <input type="password" placeholder="Admin Password" name="adminPassword" id="password2" multiple="multiple">
			</div>
			<input type="submit" value="Back" class="inputButton" style="margin-top: 30px;" form="back">
			<input type="submit" value="Register" name="submit" class="inputButton" style="margin-top: 30px;">
		    </form>
		</div>
	    </div>
	</div>
    </body>
    <script type="text/javascript" src="../index.js"></script>
</html>
