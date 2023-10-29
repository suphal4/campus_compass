<?php

// Checks If User Is Already Logged In 
if(isset($_SESSION['Username'])){
  header("Location: MyAccount.php"); 
}

// Initiates Empty Strings, Integer And Array That Are Later Used In The File
$UsernameNew = $PasswordNew = $Email = $ConfirmPassword = $Failure = "";
$SignUpError = 0;
$SignUpErrors = array();

// Checks If The Signup Form Has Been Submitted 
if(isset($_POST["SignUp"])){
  // Checks If The Email Field Is Empty
  if(empty($_POST["Email"])){
    echo "<style>input[name='Email']{border: 0.175em solid darkred !important}</style>";
    array_push($SignUpErrors, "Please Enter Your UoM Email");
  } else {
    $Email = CleanInput($_POST["Email"]);
    // Checks Database To See If An Account With This Email Already Exists
    $EmailCount = mysqli_query($Conn, "SELECT * FROM Users WHERE Email = '$Email' LIMIT 1");
    // Checks If The Email Format Is Valid 
    if(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
      echo "<style>input[name='Email']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "Please Enter A Valid Email");
    // Checks the Number Of Rows Returned By The SQL Query Above
    } elseif(mysqli_num_rows($EmailCount) > 0){
      echo "<style>input[name='Email']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "An Account With This Email Exists");
    } else {
      list($Start, $End) = explode("@", $Email);
      // Checks That The Email Inputted By The User Is A Manchester University Email 
      if($End != "student.manchester.ac.uk"){
        echo "<style>input[name='Email']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "Please Enter Your UoM Email");
      } 
    }
  }

  // Checks If The Username Field Is Empty 
  if(empty($_POST["UsernameNew"])){
    echo "<style>input[name='UsernameNew']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "Please Enter Your Username");
  } else {
    $UsernameNew = CleanInput($_POST["UsernameNew"]);
    // Checks Database To See If An Account With This Email Already Exists
    $UsernameCount = mysqli_query($Conn, "SELECT * FROM Users WHERE Username = '$UsernameNew' LIMIT 1");
    // Checks the Number Of Rows Returned By The SQL Query Above
    if(mysqli_num_rows($UsernameCount) > 0){
      echo "<style>input[name='UsernameNew']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "This Username Is Already Taken");
    // Checks The Length Of The Username To See If It Is Between 6 And 15 Characters In length
    } elseif(strlen($UsernameNew) < 6 || strlen($UsernameNew) > 15){
      echo "<style>input[name='UsernameNew']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "Username Must Be 6 To 15 Characters");
    }
  }

  // Checks If Password Field Is Empty
  if(empty($_POST["PasswordNew"])){
    echo "<style>input[name='PasswordNew']{border: 0.175em solid darkred !important}</style>";
    array_push($SignUpErrors, "Please Enter Your Password");
  } else {
    $PasswordNew = CleanInput($_POST["PasswordNew"]);
    // Checks The Length Of The Password To See More Than 5 Characters In length
    if(strlen($PasswordNew) < 6){
      echo "<style>input[name='PasswordNew']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "PasswordNew Must Be Longer Than 6 Characters");
    // Checks If The Password Contains Upper/Lower Case Letters And Numbers Through Regular Expressions
    } elseif(!preg_match('@[A-Z]@', $PasswordNew) || !preg_match('@[a-z]@', $PasswordNew) || !preg_match('@[0-9]@', $PasswordNew)){
      echo "<style>input[name='PasswordNew']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "Your Password Is Weak");
    }
  }

  //  Checks If Password Confirmation Field Is Empty
  if(empty($_POST["ConfirmPassword"])){
    echo "<style>input[name='ConfirmPassword']{border: 0.175em solid darkred !important}</style>";
    array_push($SignUpErrors, "Please Confirm Your Password");
  } else {
    $ConfirmPassword = CleanInput($_POST["ConfirmPassword"]);
    // Checks If The Password And The Password Confirmation Are Equal
    if($ConfirmPassword != $PasswordNew){
      echo "<style>input[name='ConfirmPassword']{border: 0.175em solid darkred !important}</style>";
      array_push($SignUpErrors, "Both Passwords Don't Match");
    }
  }

  // Checks The Count Of The Array To See If There Are Any Errors In The User Inputs
  if(count($SignUpErrors) == 0){
    // Hashes The Users' New Password Using PHP Inbuilt Function
    $EncryptedPassword = password_hash($PasswordNew, PASSWORD_DEFAULT);
    // Generates A Verification Hash Using PHP Inbuild PHP Function MD5
    $VerificationHash = md5(rand(0,1000));
    // Inserts The Users' Details Into The Database Table
    $AddAccount = mysqli_query($Conn, "INSERT INTO `Users`(`Email`, `Username`, `Password`, `Status`, `VerificationHash`, `Admin`) VALUES ('$Email', '$UsernameNew', '$EncryptedPassword', 'Pending', '$VerificationHash',0)");
    // Sets The Session Variables If The Account Was Sucessfully Created
    if($AddAccount){
      $_SESSION['Email'] = $Email;
      $_SESSION['Hash'] = $VerificationHash;
      $_SESSION['Username'] = $UsernameNew;
      $_SESSION['Password'] = $PasswordNew;
      header("Location: EmailVerification.php");
    } else {
      $Failure = "Failure To Create An Account. Please Try Again Later";
    }
  } else {
    $SignUpError = 1;
  }

}

?>