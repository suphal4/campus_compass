<?php

if(!isset($_SESSION["Username"])){
	header("Location: Account.php");
} else {
	$Username = $_SESSION["Username"];
}

$currentUsername  = $Email = "";
$getUserDetails = mysqli_query($Conn, "SELECT * FROM Users WHERE Username = '$Username' LIMIT 1");
while($Row = mysqli_fetch_assoc($getUserDetails)){
    $currentUsername = $Row["Username"];
    $Email = $Row["Email"];
    $passwordHashed = $Row["Password"];
}

$changeAccountSettings = array();

function updateSettings($newUsername, $currentPassword, $newPassword, $conPassword){

	global $changeAccountSettings, $currentUsername, $Email, $passwordHashed;
	$Conn = new mysqli('dbhost.cs.man.ac.uk','m42552rh','Alihasan_1','2021_comp10120_y18') or die("Connection Failed: " . $Conn -> error);

	if($newUsername != $currentUsername){
		if(empty($_POST["myUsername"])){
		    echo "<style>input[name='myUsername']{border: 0.175em solid darkred !important}</style>";
		    array_push($changeAccountSettings, "Please Enter Your Username");
		} else {
		  $UsernameCount = mysqli_query($Conn, "SELECT * FROM Users WHERE Username = '$newUsername' LIMIT 1");
		  if(mysqli_num_rows($UsernameCount) > 0){
		    echo "<style>input[name='myUsername']{border: 0.175em solid darkred !important}</style>";
		    array_push($changeAccountSettings, "This Username Is Already Taken");
		  } elseif(strlen($newUsername) < 6 || strlen($newUsername) > 15){
		    echo "<style>input[name='myUsername']{border: 0.175em solid darkred !important}</style>";
		    array_push($changeAccountSettings, "Username Must Be 6 To 15 Characters");
		  }
		}
	}

	if(!empty($currentPassword)){
		if(!password_verify($currentPassword, $passwordHashed)){
			echo "<style>input[name='currentPassword']{border: 0.175em solid darkred !important}</style>";
		    array_push($changeAccountSettings, "Your Current Password Is Incorrect");
		}
	} else {
		echo "<style>input[name='currentPassword']{border: 0.175em solid darkred !important}</style>";
		array_push($changeAccountSettings, "Please Enter Your Current Password");
	}

	if(!(empty($newPassword)) && !(empty($conPassword))){
		if($newPassword != $conPassword){
			echo "<style>input[name='newPassword']{border: 0.175em solid darkred !important}</style>";
			echo "<style>input[name='confirmNewPassword']{border: 0.175em solid darkred !important}</style>";
			array_push($changeAccountSettings, "Both Passwords Don't Match");
		} else {
			if(!preg_match('@[A-Z]@', $newPassword) || !preg_match('@[a-z]@', $newPassword) || !preg_match('@[0-9]@', $newPassword)){
      			echo "<style>input[name='newPassword']{border: 0.175em solid darkred !important}</style>";
      			array_push($changeAccountSettings, "Your New Password Is Weak");
    		}
		}
	} elseif((empty($newPassword) && !empty($conPassword)) || (!empty($newPassword) && empty($conPassword))){
		echo "<style>input[name='newPassword']{border: 0.175em solid darkred !important}</style>";
		echo "<style>input[name='confirmNewPassword']{border: 0.175em solid darkred !important}</style>";
      	array_push($changeAccountSettings, "Please Enter/Confirm The New Password");
	}

	if(count($changeAccountSettings) > 0){
		return false;
	} else {
		if(!empty($newPassword)){
			$EncryptedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
			$updateDetails = mysqli_query($Conn, "UPDATE Users SET Username = '$newUsername', Password = '$EncryptedPassword' WHERE Email = '$Email'");
			if($updateDetails){
				$_SESSION['Username'] = $newUsername;
				$_SESSION['Updated'] = true;
			} else {
				$_SESSION['Updated'] = false;
			}
		} else {
			$updateDetails = mysqli_query($Conn, "UPDATE Users SET Username = '$newUsername' WHERE Email = '$Email'");
			if($updateDetails){
				$_SESSION['Username'] = $newUsername;
				$_SESSION['Updated'] = true;
			} else {
				$_SESSION['Updated'] = false;
			}
		}
		return $updateDetails;
	}

}

if(isset($_POST['updateDetails'])){
	unset($_SESSION["Updated"]);
	if(updateSettings(CleanInput($_POST['myUsername']), CleanInput($_POST['currentPassword']), CleanInput($_POST['newPassword']), CleanInput($_POST['confirmNewPassword']))){
		header("Location: Settings.php");
	}
	
}

?>