<?php
	//This script displays server-side system info, like CPU temp and uptime

include "checkpass.php";

//the bottom two if statements simply verifies that this is a linux server being run. if it isnt, then this code will handle it better than it would without it
if ($uptime = shell_exec("uptime")) { // if this varible is set properly, do nothing
} else { //if not, make it unknown
	$uptime = "Unknown";
}

if ($f = fopen("/sys/class/thermal/thermal_zone0/temp","r")) {//There is a file in the raspberry pi that records the CPU temp. Check if it can be found
	$temp = fgets($f); //get the CPU temp number
	fclose($f);
	$temp = round($temp/1000); //convert number to celsius
} else { //if not, make it unknown
	$temp = "Unknown";
}

echo "<div class='serverinfodivcontainer'>";
echo "<div class='serverinfodiv'>";
echo "<p class='serverinfo'>Server Uptime: " . $uptime . "</p>"; //'uptime' is entered in the Debian(raspbian, to be exact) terminal, and it outputs the result here
echo "<p class='serverinfo'>Server CPU Temp: ". $temp . "'C</p>"; //The number is not in celsius. Divide by 1000 to get it in that unit
echo "</div>";
echo "</div>";
?>
