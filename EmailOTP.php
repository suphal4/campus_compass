<?php

// Includes The MySQLI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");

// Imports The PHPMailer External Library
use PHPMailer\PHPMailer\PHPMailer;

// Checks If The Email Variable Is Set In The Current Session
if(!isset($_SESSION['Email'])){
	header("Location: Account.php");
}

$Recipient = $_SESSION['Email'];
$OTP = $_SESSION['OTP'];

// Created A HTML Message With Subject Sent To The Users' University Email When They Forget Their Password
$Subject = "Campus Compass - One-Time Password";
$Message = "<html><h3 style='font-size:18px; letter-spacing:1.5px;'>Your One-Time Password Is Below</h3><p style='font-size:14px; letter-spacing:1.5px; font-weight:400;'><b style='color: red;'>DO NOT SHARE YOUR OTP WITH ANYONE!</b><br><hr><br><b style='font-size:25px; letter-spacing:3.5px; font-weight:700;'>OTP: $OTP</b><br></p></html>";

// Gets The Required PHP Scripts From The PHPMailer Folder 
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";
require_once "PHPMailer/Exception.php";

// Creates A New Email
$Mail = new PHPMailer();
$Mail->isSMTP();
$Mail->Host = "smtp.gmail.com";
$Mail->SMTPAuth = true;
// Created A Email For Our Project From Which The Email Is Sent To The Users' University Email
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
if($Mail->send()){
	// Sets Session Variables If The Mail Is Successfully Sent
	$_SESSION['OTPSent'] = 1;
	$_SESSION['Attempts'] = 3;
	header("Location: ForgotPassword.php");
} else {
	// Sets Session Variables If The Mail Is Not Sent
	$_SESSION['OTPSent'] = 0;
	header("Location: ForgotPassword.php");
}

?>