<?php 
// This script adds functionality to the "Go Home" button


session_start();
include "checkpass.php";

//change directory status to uploads, which is home folder
$_SESSION["directory"] = "uploads";
$_SESSION["selectall"] = "0";

header("Location: index2.php");	

?>
