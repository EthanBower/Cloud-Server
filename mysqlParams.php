<?php
    //This is where you enter in info about the specific database to place info in.
    
	class mysqloptions {
		public static $use_database = false; //If you dont want to use database, and rather use a single local user, replace 'true' with 'fasle'
		public static $servername = "localhost";
		public static $username = "admin";
		public static $password = "password";
		public static $dbname = "cloud_server"; //set the name of the database that you want
	}
?>
