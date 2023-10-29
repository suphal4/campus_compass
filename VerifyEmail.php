<?php

// Includes The MySQLI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");

// Checks If The Email And Hash Has Been Passed In The URL Otherwise They Are Redirected Back To The Login Page
if(!isset($_GET['Email']) or empty($_GET['Email']) or !isset($_GET['Hash']) or empty($_GET['Hash'])){
	header("Location: Account.php");
} else {
	$Email = $_GET['Email'];
	$Hash = $_GET['Hash'];
	// Searches For The Account In The Database
	$SearchUser = mysqli_query($Conn, "SELECT Email, Status, VerificationHash FROM Users WHERE Email = '$Email' AND VerificationHash = '$Hash' AND Status = 'Pending'");
	// Checks If The Account Exists
	if(mysqli_num_rows($SearchUser) > 0){
		// Updates The Account Status To Activated As The User Have Successfully Verified Their Account 
		$Verify = mysqli_query($Conn, "UPDATE Users SET Status = 'Activated' WHERE Email = '$Email' AND VerificationHash = '$Hash'");
		// Checks If The Row In The Database Has Been Updated And Sets The Session Variable Accordingly 
		if(mysqli_affected_rows($Conn) > 0){
			$_SESSION['Verified'] = 1;
		} else {
			$_SESSION['Verified'] = 0;
		}
		header("Location: Account.php");
	} else {
		header("Location: Account.php");
	}
}

?>