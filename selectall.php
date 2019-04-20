<?php
// This script sets the value "1" to the selectAll.txt file so that show_dir.php can read it and determine if the user clicked the "select all" button when it refreshes


session_start();
include "checkpass.php";

if (isset($_POST["selectallbtn"])) {
	$_SESSION["selectall"] = "1";
}

header("Location: index2.php");
?>
