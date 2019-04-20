<?php
    //When included on the top of a .php file, it will check to see if the password has been entered in. If not, forcefully go back to the password page
        
    if($_SESSION["loginStatus"] !== "logged") { // if nothing is in txt file, return to password menu
        header("Location: index.php"); 
    } else { //this will see if current session is about to expire. if it is, make a new one so that the user wont get logged out
        //NOTE: Make sure that php.ini files are set correctly, too. 
        //Start by changing session.gc maxlifetime to the amount of seconds you want, and session.cookie lifetime to the SAME AMOUNT. This will delete session files upon user leaving.
        //Then, change $timeout_duration to (ideally) the same amount of seconds, below. This will timeout the user if page is stagnite for a while.

        //set timeout in sec
        $timeout_duration = 600; //timeout after 10min

        $time = $_SERVER['REQUEST_TIME'];
        
        /**
        * Here we look for the user's LAST_ACTIVITY timestamp. If
        * it's set and indicates our $timeout_duration has passed,
        * blow away any previous $_SESSION data and start a new one.
        */
        if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
            session_unset();
            session_destroy();
            
            session_start();
            $_SESSION['timeoutCheck'] = true; //if timeout occurs, make this set to true. This will pass to welcome.php and will reset it 
            header("Location: index.php");
        }

        /**
        * Finally, update LAST_ACTIVITY so that our timeout
        * is based on it and not the user's login time.
        */
        $_SESSION['LAST_ACTIVITY'] = $time;
    }
?>
