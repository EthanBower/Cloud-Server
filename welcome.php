<?php
//This script will read the time of the server, and will echo the correct responces. It will also change background color dynamically based on time.
	
	$hour = (int)date("H"); //H means that it will output the hour in millitary time

	function backgroundColorInterval($to, $from, $currentHour, $hourMin, $hourMax) { 
			//This function allows for a background color to be changed on every hour, linearly. As a result, it adds a nice dynamic feeling to the page.
			//Below this function, I have divided the time of day into 4 sections (0-7, 7-12, 12-17, and 17-24). Each of them has a specific color that this function will get to at the BEGINNING of the starting hour.
			// For example, at hour 0, the html background will be rgb(88,88,88). As the hour value approches 7, those rgb values will slowly increase linearly to rgb(242,250,255).
			//As the hour then approaches 12, starting from hour 7, those rgb value will increase/decrease to rgb(26,167,255)
			
			//Parameters: 
			//$to: This is the specific r/g/b value that the user wants to get to when the next landmark hour is reached
			//$from: This is the specific r/g/b value that the user set initially at the start of the landmark hour
			//$currentHour: this is the current time, in millitary time
			//$hourMin: Each if statement has an hour min and hour max parameter. This is for the min hour
			//$hourMax: this is the max hour
			
			$toFromResult = $from - $to; //if value if negative,increment. If value is positive, decrement
			$hourMinMaxResult = $hourMax - $hourMin;
			
			if ($hourMax == 24) { //because 24 is included in the if statement below, 1 has to be added for a completley smooth transistion
				$hourMinMaxResult += 1;
			}
			
			if ($toFromResult < 0) { // if value is negative, increment, since we know that the "to" value is larger
				$toFromResult = abs($toFromResult);
				
				$incrementStep = ($toFromResult / $hourMinMaxResult) * ($currentHour - $hourMin);
				
				return round($incrementStep + $from, 0);
			} else {
				$incrementStep = ($toFromResult / $hourMinMaxResult) * ($currentHour - $hourMin);
				
				return round(abs($from - $incrementStep), 0); // should never be a negetive value, but added abs() just in case
			}
	}
		
	echo "<script>var html = document.getElementById('html');</script>"; // define html so that i can change the background color	
	echo "<p id='welcomeText'>";
	if (isset($_SESSION['timeoutCheck']) && $_SESSION['timeoutCheck'] == true) { //This is displayed when timeout occurs
		echo "You have been logged out due to no activity.";
		echo "<script>html.style.background = 'rgb(119, 42, 0)'</script>";
		session_unset(); // display this page only once. If refreshed it will go back to the normal login page
        session_destroy();
	} else if(isset($_SESSION['loginReject']) && $_SESSION['loginReject'] == true) { //Determine if client failed logging in too many times
		$waitDuration = ($_SESSION['clientReject_duration'] - ($_SERVER['REQUEST_TIME'] - $_SESSION['LAST_ACTIVITY'])); //duration in seconds
		if ($waitDuration < 0) {
			$waitDuration = 0;
		}
		echo "<p>";
		echo "You have entered the password incorrectly too many times.<br>";
		echo "Wait " . ceil($waitDuration/60) . " Minutes.";
		echo "</p>";
		echo "<script>html.style.background = 'rgb(0, 0, 0)'</script>";
	} else if ($hour >= 0 && $hour < 7) { //go from (start at) rgb(88,88,88) to rgb(242,250,255). Start looking at the current time ONLY IF 1) timeout has NOT occured, and 2) server isnt rejecting login
		echo "That snooze button is going to be hit a lot...";
		echo "<script>html.style.background = 'rgb(" . backgroundColorInterval(242, 88, $hour, 0, 7) . "," . backgroundColorInterval(250, 88, $hour, 0, 7) ."," . backgroundColorInterval(255, 78, $hour, 0, 7) . ")';</script>";
	} else if ($hour >= 7 && $hour < 12) { //go from (start at) rgb(242,250,255) to rgb(26,167,255)
		echo "Good morning.";
		echo "<script>html.style.background = 'rgb(" . backgroundColorInterval(26, 242, $hour, 7, 12) . "," . backgroundColorInterval(167, 250, $hour, 7, 12) ."," . backgroundColorInterval(255, 255, $hour, 7, 12) . ")';</script>";
	} else if ($hour >= 12 && $hour < 17) { //go from (start at) rgb(26,167,255) to rgb(0,47,77)
		echo "Good afternoon.";
		echo "<script>html.style.background = 'rgb(" . backgroundColorInterval(0, 26, $hour, 12, 17) . "," . backgroundColorInterval(47, 167, $hour, 12, 17) ."," . backgroundColorInterval(77, 255, $hour, 12, 17) . ")';</script>";
	} else if ($hour >= 17 && $hour <= 24) { //go from (start at) rgb(0,47,77) to rgb(88,88,88)
		echo "It's getting late.";
		echo "<script>html.style.background = 'rgb(" . backgroundColorInterval(88, 0, $hour, 17, 24) . "," . backgroundColorInterval(88, 47, $hour, 17, 24) ."," . backgroundColorInterval(88, 77, $hour, 17, 24) . ")';</script>";
	} else { //Just in case something isnt right
		echo "Welcome.";
		echo "<script>html.style.background = 'rgb(146, 27, 27)';</script>";
	}
	
	echo "</p>";
?>
