<?php

// Starts The Session Which Allows Variables To Be Passed Between Pages
session_start();

// Checks If The User Is Logged In
if(isset($_SESSION["Username"])){
	// Unsets All Session Variables And Destroys The Session 
	session_unset();
	session_destroy();
	header("Location: Home.php");
} else {
	header("Location: Account.php");
}

?>