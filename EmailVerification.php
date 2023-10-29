<?php

session_start();
// Imports The PHPMailer External Library
use PHPMailer\PHPMailer\PHPMailer;
$Recipient = $_SESSION['Email'];
$VerificationHash = $_SESSION['Hash'];
$User = $_SESSION['Username'];
$Password = $_SESSION['Password'];

// Created A HTML Message With Subject Sent To The Users' University Email When They Create A New Account
$Subject = "Campus Compass - Email Verification";
$Message = "<html><h3 style='font-size:18px; letter-spacing:1.5px;'>Thanks For Signing Up With Campus Compass</h3><p style='font-size:14px; letter-spacing:1.25px; font-weight:500;'>Your Account Has Successfully Been Created. Your Login Credentials Are Below:<br><b style='color:red;'>DO NOT SHARE THESE DETAILS WITH ANYONE!</b><br><br>-------------------------<br>Username: $User<br>Password: $Password<br>-------------------------<br><br>Please Click <a style='color:#124AD6; font-weight:600;' href='https://web.cs.manchester.ac.uk/m42552rh/FirstYearTeamProject/VerifyEmail.php?Email=$Recipient&Hash=$VerificationHash'>Here</a> To Activate Your Account</p></html>";

// Gets The Required PHP Scripts From The PHP Mailer Folder 
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";
require_once "PHPMailer/Exception.php";

// Creates A New Email
$Mail = new PHPMailer();
$Mail->isSMTP();
$Mail->Host = "smtp.gmail.com";
$Mail->SMTPAuth = true;
// Created A Email For Our Project From Which The Email Is Sent To The New Users' University Email
$Mail->Username = "uom.y18@gmail.com";
$Mail->Password = "CampusCompass1!";
$Mail->Port = 465;
$Mail->SMTPSecure = "ssl";
$Mail->From = "noreply@campuscompass.com";
$Mail->FromName = "Campus Compass";
$Mail->setFrom($Recipient,$Username);
$Mail->addAddress($Recipient);
$Mail->Subject = $Subject;
$Mail->Body = ($Message);
$Mail->isHTML(true);
// Sets Session Variables Depending On If The Mail Is Sent Or Not
if($Mail->send()){
	$_SESSION['AccountCreated'] = 1;
} else {
	$_SESSION['AccountCreated'] = 0;
}
header("Location: Account.php");

?>