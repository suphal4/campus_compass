<?php

$feedbackArray = array();
$emailAddress = $fName = $messageField = $messageSubject = "";
include 'ConnectionToMySQL.php';

// Function That Is Called To Search For Abusive Words In User Inputs
function findAbusiveWord($String){

	// API That Checks The Review For Any Abusive Words
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.apilayer.com/bad_words?censor_character=*",
	  CURLOPT_HTTPHEADER => array(
	    "Content-Type: text/plain",
	    "apikey: ev26kIhnZeoFP7KooODJ5hNOjORvYUmd"
	  ),
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{$String}"
	));
	$response = curl_exec($curl);
	curl_close($curl);
	$apiResult = explode(",", $response);
	$badWordResult = explode(":", $apiResult[1]);
	$badWordCount = (int)$badWordResult[1];
	if($badWordCount > 0){
		return true;
	}
	return false;

}

// Imports The PHPMailer External Library
use PHPMailer\PHPMailer\PHPMailer;

if(isset($_POST['Feedback'])){

	unset($_SESSION["MailSent"]);

	// Performs Validation Of The Full Name Inputted
	if(!empty($_POST['fName'])){
		$fName = CleanInput($_POST['fName']);
		if(strlen($fName) <= 2){
			array_push($feedbackArray, "Please Enter A Valid Name");
		} elseif(findAbusiveWord($fName)){
			array_push($feedbackArray, "Abusive Language Has Been Detected In Your Name");
		} 
	} else {
		array_push($feedbackArray, "Please Enter Your Full Name");
	}

	// Performs Validation Of The Email Address Inputted
	if(!empty($_POST['emailAddress'])){
		$emailAddress = CleanInput($_POST['emailAddress']);
		if(!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){
			array_push($feedbackArray, "Please Enter A Valid Email Address");
		}
	} else {
		array_push($feedbackArray, "Please Enter Your Email Address");
	}

	// Performs Validation Of The Message Subject Inputted
	if(!empty($_POST['messageSubject'])){
		$messageSubject = CleanInput($_POST['messageSubject']);
		if(strlen($messageSubject) <= 3){
			array_push($feedbackArray, "Please Enter A Valid Subject");
		} elseif(findAbusiveWord($messageSubject)){
			array_push($feedbackArray, "Abusive Language Has Been Detected In The Subject");
		}
	} else {
		array_push($feedbackArray, "Please Enter Your Feedback Subject");
	}

	// Performs Validation Of The Message Inputted
	if(!empty($_POST['messageField'])){
		$messageField = CleanInput($_POST['messageField']);
		if(strlen($messageField) <= 5){
			array_push($feedbackArray, "Please Enter A Valid Message");
		} elseif(findAbusiveWord($messageField)){
			array_push($feedbackArray, "Abusive Language Has Been Detected In The Message");
		}
	} else {
		array_push($feedbackArray, "Please Enter Your Message");
	}

	if(count($feedbackArray) == 0){

		// Created A HTML Message With Subject Sent To The Company Email
		$Subject = "Feedback - $messageSubject";
		$Message = "<html>
		<h3 style='font-size:1.25em; letter-spacing:1.5px; text-align: center;'>Feedback From - $fName</h3>
		<img style='width: 137.5px; margin: 5px auto; display: flex; flex-direction: column; border-radius: 7.5px;' src='cid:CampusCompassLogo' alt='Company Logo'>
		<br><p style='font-size:1.1em; letter-spacing:1.25px; font-weight:600;'>Email Of Sender: $emailAddress</p>
		<p style='font-size:1em; letter-spacing:1.125px; font-weight:500;'>$messageField</p>
		</html>";

		// Gets The Required PHP Scripts From The PHP Mailer Folder 
		require_once "PHPMailer/PHPMailer.php";
		require_once "PHPMailer/SMTP.php";
		require_once "PHPMailer/Exception.php";

		// Creates A New Email
		$Mail = new PHPMailer();
		$Mail->isSMTP();
		$Mail->Host = "smtp.gmail.com";
		$Mail->SMTPAuth = true;
		$Mail->Username = "uom.y18@gmail.com";
		$Mail->Password = "CampusCompass1!";
		$Mail->Port = 465;
		$Mail->SMTPSecure = "ssl";
		$Mail->From = "noreply@campuscompass.com";
		$Mail->FromName = "Campus Compass";
		$Mail->setFrom($emailAddress, $fName);
		$Mail->addAddress("uom.y18@gmail.com");
		$Mail->Subject = $Subject;
		$Mail->AddEmbeddedImage('Logo.png', 'CampusCompassLogo');
		$Mail->Body = ($Message);
		$Mail->isHTML(true);
		if($Mail->send()){
			$_SESSION['MailSent'] = 1;
		} else {
			$_SESSION['MailSent'] = 0;
		}
		header("Location: ContactUs.php");
	}

}

?>