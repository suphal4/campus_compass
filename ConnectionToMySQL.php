<?php
	
	if (session_status() != PHP_SESSION_ACTIVE){
		session_start();
	}

	function CleanInput($Data){
    	$Data = trim($Data);
    	$Data = stripslashes($Data);
    	$Data = htmlspecialchars($Data);
    	return $Data;
  	}
	
	$Conn = new mysqli('dbhost.cs.man.ac.uk','m42552rh','Alihasan_1','2021_comp10120_y18') or die("Connection Failed: " . $Conn -> error);

?>