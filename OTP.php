<?php

// Includes The MySQLI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");

// Checks If The USer Is Logged In
if(isset($_SESSION['Username'])){
  header("Location: MyAccount.php");
}

// Fucntion That Generates A OTP Of A Mixture Of Characters And Numbers
function GenerateOTP($Length = 6){
  $Char = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $CharLength = strlen($Char);
  $OTP = "";
  for($X = 0; $X < $Length; $X++){
    // Randomly Selects A Character From The String $Char
    $OTP .= $Char[rand(0, $CharLength - 1)];
  }
  return $OTP;
}

// Inititate String And Array That Are Later Assigned Values Or Pushed Into
$UserOrEmail = $Status = "";
$ForgotPasswordError = array();

// Checks If The User Has Submitted The Reset Password Form 
if(isset($_POST['CheckUser'])){
  // Checks If The Username Or Email Field Is Empty
  if(empty($_POST['UserOrEmail'])){
    echo "<style>input[name='UserOrEmail']{border: 0.175em solid darkred !important}</style>";
    array_push($ForgotPasswordError, "Please Enter Your Email Or Username");
  } else {
    $UserOrEmail = CleanInput($_POST["UserOrEmail"]);
    // Checks If The Input Is An Email
    if(strpos($UserOrEmail, "@")){
      // Checks If The Email Is In A Valid Format
      if(!filter_var($UserOrEmail, FILTER_VALIDATE_EMAIL)){
        echo "<style>input[name='UserOrEmail']{border: 0.175em solid darkred !important}</style>";
        array_push($ForgotPasswordError, "Please Enter A Valid Email");
      } else {
        // Searches For An Account With This Email
        $SearchEmail = mysqli_query($Conn, "SELECT Email, Status FROM Users WHERE Email = '$UserOrEmail' LIMIT 1");
        // Checks If An Account Exists With This Email
        if(mysqli_num_rows($SearchEmail) == 0){
          echo "<style>input[name='UserOrEmail']{border: 0.175em solid darkred !important}</style>";
          array_push($ForgotPasswordError, "An Account With This Email Doesn't Exist");
        } else {
          while($Row = mysqli_fetch_assoc($SearchEmail)){
            // Checks If The Account Hasn't Been Activated Yet
            if($Row["Status"] == "Pending"){
              echo "<style>input[name='UserOrEmail']{border: 0.175em solid darkred !important}</style>";
              array_push($ForgotPasswordError, "Your Account Hasn't Been Activated Yet");
            } else {
              // Generates An OTP And Sets Session Variables
              $SendOTP = GenerateOTP();
              $_SESSION['OTP'] = $SendOTP;
              $_SESSION['Email'] = $UserOrEmail;
              header("Location: EmailOTP.php");
            }
          }
        }
      }
    } else {
      // Searches For An Account With This Username 
      $SearchUsername = mysqli_query($Conn, "SELECT Email, Status FROM Users WHERE Username = '$UserOrEmail' LIMIT 1");
      // Checks If An Account Exists With This Username
      if(mysqli_num_rows($SearchUsername) == 0){
        echo "<style>input[name='UserOrEmail']{border: 0.175em solid darkred !important}</style>";
        array_push($ForgotPasswordError, "An Account With This Username Doesn't Exist");
      } else {
        while($Row = mysqli_fetch_assoc($SearchUsername)){
          // Checks If The Account Hasn't Been Activated Yet
          if($Row["Status"] == "Pending"){
            echo "<style>input[name='UserOrEmail']{border: 0.175em solid darkred !important}</style>";
            array_push($ForgotPasswordError, "Your Account Hasn't Been Activated Yet");
          } else {
            // Generates An OTP And Sets Session Variables
            $SendOTP = GenerateOTP();
            $_SESSION['Email'] = $Row["Email"];
            $_SESSION['OTP'] = $SendOTP;
            header("Location: EmailOTP.php");
          }
        } 
      }
    }
  }
}

?>