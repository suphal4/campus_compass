<?php

// Inititate Int And Array For Error Detection Error Messages 
$Error = 0;
$OTPError = array();

// Checks If The User Has Clicked The Submit OTP Form Button
if(isset($_POST['OTP'])){

	// Returns The User Back To The Login Page And Unsets All Session Variables If They Have Failed 3 Attempts For The OTP  
	if($_SESSION['Attempts'] < 1){
		unset($_SESSION['Attempts']);
		unset($_SESSION['OTPSent']);
		unset($_SESSION['OTPValid']);
		header("Location: Account.php");
	}

	// Checks If The OTP Field Is Not Empty
	if(empty($_POST['OTP'])){
		echo "<style>input[name='OTP']{border: 0.175em solid darkred !important}</style>";
		array_push($OTPError, "Please Enter The OTP Sent To Your Email");
		$_SESSION['Attempts'] = ($_SESSION['Attempts'] - 1);
	} else {
		// Checks If The Inputted OTP And The Emailed OTP Are The Same
		if(strtoupper($_POST['OTP']) != $_SESSION['OTP']){
			echo "<style>input[name='OTP']{border: 0.175em solid darkred !important}</style>";
			array_push($OTPError, "The OTP Is Incorrect");
			$_SESSION['Attempts'] = ($_SESSION['Attempts'] - 1);
		} else {
			// Redirects The User To The Change Password Page If The OTP Is Correct 
			$_SESSION['OTPValid'] = 1;
			header("Location: ChangePassword.php");
		}
	}
}

?>