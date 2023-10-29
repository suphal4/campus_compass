<?php

// Includes The MySQLI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");

// Unsets Session Variables
unset($_SESSION['OTPSent']);
unset($_SESSION['OTPValid']);
unset($_SESSION['Email']);

// Checks If The User Is Logged In  
if(isset($_SESSION['Username'])){
  $User = $_SESSION['Username'];
  // Searches The Database to See If The Users' Account Is Activated
  $Status = mysqli_query($Conn, "SELECT * FROM Users WHERE Username = '$User' AND Status = 'Pending'");
  if(mysqli_num_rows($Status) == 0){
    header("Location: MyAccount.php");
  } else {
    unset($_SESSION['Username']);
  }
}

$Username = $Password = "";
$LogInErrors = array();

// Checks If The User Has Clicked The Log In Button
if(isset($_POST["LogIn"])){

  // Checks If The Username Field Is Empty 
  if(empty($_POST["UsernameLogIn"])){
    echo "<style>input[name='UsernameLogIn']{border: 0.175em solid darkred !important}</style>";
    array_push($LogInErrors, "Please Enter Your Username");
  } else {
    $Username = CleanInput($_POST["UsernameLogIn"]);
  }

  // Checks If The Password Field Is Empty
  if(empty($_POST["PasswordLogIn"])){
    echo "<style>input[name='PasswordLogIn']{border: 0.175em solid darkred !important}</style>";
    array_push($LogInErrors, "Please Enter Your Password");
  } else {
    $Password = CleanInput($_POST["PasswordLogIn"]);
  }

  // Checks If There Are Any Errors In The Log In Inputs
  if(count($LogInErrors) == 0){
    // Searches The database For The Users' Details By The Username
    $CheckUsername = mysqli_query($Conn, "SELECT Username, Password, Status FROM Users WHERE Username = '$Username' LIMIT 1");
    // Checks If An Account With This Username Exists
    if(mysqli_num_rows($CheckUsername) > 0){
      while($Row = mysqli_fetch_assoc($CheckUsername)){
        if($Row["Status"] == "Pending"){
          array_push($LogInErrors, "Please Verify Your UoM Email Before Logging In");
        }
        // PHP Function Which Verifies The Inputted Password With The Encryped Password Stored In The Database 
        elseif(password_verify($Password, $Row["Password"])){
          $_SESSION["Username"] = $Username;
          header("Location: MyAccount.php");
        } else {
          echo "<style>input[name='UsernameLogIn']{border: 0.175em solid darkred !important}</style>";
          echo "<style>input[name='PasswordLogIn']{border: 0.175em solid darkred !important}</style>";
          array_push($LogInErrors, "Invalid Username Or Password");
        }
      }
    } else {
      echo "<style>input[name='UsernameLogIn']{border: 0.175em solid darkred !important}</style>";
      echo "<style>input[name='PasswordLogIn']{border: 0.175em solid darkred !important}</style>";
      array_push($LogInErrors, "Invalid Username Or Password");
    }
  }
}

?>