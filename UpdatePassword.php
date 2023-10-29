<?php
	
// Includes The MySQlI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");
// Initiates An Empty Array And Strings Which Are Later Used  
$ChangePasswordErrors = array();
$UpdatedPassword = $ConfirmUpdatedPassword = "";

// Checks If The OTP That Was Sent To Their Email Was Valid And If Their Email Has Been Saved In The Session
if(!isset($_SESSION['OTPValid']) and (!isset($_SESSION['Email']))){
	header("Location: Account.php");
} else {
	$Email = $_SESSION['Email'];
	// Checks If The Form Has Been Posted 
	if(isset($_POST["UpdatePassword"])){
		// Checks If The Password Is Empty
		if(empty($_POST['Password'])){
			echo "<style>input[name='Password']{border: 0.175em solid darkred !important}</style>";
			array_push($ChangePasswordErrors, "Please Enter A New Password");
		} else {
			$UpdatedPassword = CleanInput($_POST["Password"]);
			// Checks If The Password Is >= 6 Characters
			if(strlen($UpdatedPassword) < 6){
    			echo "<style>input[name='Password']{border: 0.175em solid darkred !important}</style>";
    			array_push($ChangePasswordErrors, "Your Password Must Be Longer Than 6 Characters");
    		// Checks If The Password Contains Upper/Lower Case Letters And Numbers Through Regular Expressions
  			} elseif(!preg_match('@[A-Z]@', $UpdatedPassword) || !preg_match('@[a-z]@', $UpdatedPassword) || !preg_match('@[0-9]@', $UpdatedPassword)){
    			echo "<style>input[name='Password']{border: 0.175em solid darkred !important}</style>";
    			array_push($ChangePasswordErrors, "Your Password Is Weak");
  			}
		}
		// Checks If the Password Confirmation Is Empty
		if(empty($_POST["ConfirmPassword"])){
  			echo "<style>input[name='ConfirmPassword']{border: 0.175em solid darkred !important}</style>";
    			array_push($ChangePasswordErrors, "Please Confirm Your New Password");
		} else {
  			$ConfirmUpdatedPassword = CleanInput($_POST["ConfirmPassword"]);
  			// Checks If The Password And The Password Confirmation Are Equal
  			if($ConfirmUpdatedPassword != $UpdatedPassword){
  				echo "<style>input[name='ConfirmPassword']{border: 0.175em solid darkred !important}</style>";
  				echo "<style>input[name='Password']{border: 0.175em solid darkred !important}</style>";
    			array_push($ChangePasswordErrors, "Both Passwords Don't Match");
  			}
  		}
  		// Checks The Count Of The Array To See If There Are Any Errors In The User Inputs
		if(count($ChangePasswordErrors) == 0){
			// Hashes The Users' New Password Using PHP Inbuilt Function
			$UpdatedPassword = password_hash($UpdatedPassword, PASSWORD_DEFAULT);
			// Updates The Password For That User In The Database
			$AddAccount = mysqli_query($Conn, "UPDATE Users SET Password = '$UpdatedPassword' WHERE Email = '$Email'");
			// Checks If Password Was Succesfully Updated And Sets Session Variables Accordingly 
			if(mysqli_affected_rows($Conn) > 0){
				$_SESSION['Updated'] = 1;
			} else {
				$_SESSION['Updated'] = 0;
			}
			// Unsets Session Variables And Redirects To The Login Page
			unset($_SESSION['OTPValid']);
			unset($_SESSION['Email']);
			header("Location: Account.php");
		}
	}
}

?>