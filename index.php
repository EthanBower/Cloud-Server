<?php
    //The 3 settings below are now set in php.ini. No need for these commands anymore
    //ini_set( 'session.cookie_httponly', 1 ); //This prevents session hijacking. Its the act of getting a legitamite user's SESSION ID via cookie 
    //ini_set('session.use_only_cookies', 1); // Session ID cannot be passed through URLs
    //ini_set('session.cookie_secure', 0);// Uses a secure connection (HTTPS) if possible
    session_start();    
?>

<!DOCTYPE html>
<html id="html">
    <head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> <!-- Open Sans Font -->
	<link rel="stylesheet" href="index.css">
    </head>
    <body>
	<div class="container">
	    <div class="verticalAdjust">
		<div>
		    <?php
			//This will display a red or white cloud logo. Red prepresents that the user previous has not logged out, white represents OK.
			if (isset($_SESSION['timeoutCheck']) && $_SESSION['timeoutCheck'] == true) {
			    echo "<img src='Assets/SVG/cloud_red.svg' width='140px;'/>";
			    session_destroy();
			} else if (isset($_SESSION['loginReject']) && $_SESSION['loginReject'] == true) {
			    echo "<img src='Assets/SVG/cloud_red.svg' width='140px;'/>";
			} else {
			    echo "<img class='cloud' src='Assets/SVG/cloud.svg'/>";
			}
		    ?>
		</div>
		<form action="cloudenterpassword.php" method="post" enctype="multipart/form-data" style="text-align: center;" id="passform">
		    <?php include "welcome.php";?>
		    <input type="password" placeholder="Enter Password" name="password" id="password" multiple="multiple">
		    <input type="submit" value="Log In" name="submit" class="inputButton"><br>
		</form>
	    </div>
	</div>
    </body>
    <script type="text/javascript" src="index.js"></script>
</html>
