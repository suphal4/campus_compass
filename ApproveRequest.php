<?php include("AccountNavBar.php");//Adds the navigation bar to the top of the page
include('NotifyUserRequest.php');//Includes function to email user updates abut the request
if (session_status() != PHP_SESSION_ACTIVE)
{
	session_start();
}
if(!isset($_SESSION["Username"])){//Only opens the page if the user is logged in, otherwise they are directed to the login page. Works as it checks if their username is set within the session. This session is from the location of Account.php the website in whcih they login
	header("Location: Account.php");}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Approve Location Request</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="MyAccountStyleSheet.css">
</head>
<body>
	<div class = "ApproveRequest">
		<div class = "HelpPopUp">
			<img id = "HelpPointer" src = "question mark.png"></img>
			<label id = "HelpInfo">Click the button approve to add the location request to the map. Click the button deny to delete the request and not add it to the map.</label>
		</div>
		<!-- The php below will load in the request from the database and display it on the screen. If there's nothing, nothing will be displayed -->
		<?php 
			include("DisplayRequest.php");
		?>
		<button class = "Approve" onclick= "location.href='approve.php';">Approve</button>
		<button class = "Deny" onclick= "location.href='delete.php';">Deny</button>
	</div>
</body>
</html>
<style>
	a[href="ApproveRequest.php"]{
		color: rgba(0, 136, 169, 1);
	}
	
	a[href="ApproveRequest.php"]:hover{
		color: rgba(0, 136, 169, 1) !important;
	}
</style>
