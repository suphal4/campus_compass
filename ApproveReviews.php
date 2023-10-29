<?php include("AccountNavBar.php");//Adds the navigation bar to the top of the page
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
	<title>Review Flagged Reviews</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="MyAccountStyleSheet.css">
</head>
<body>
	<div class = "ApproveRequest">
		<div class = "HelpPopUp">
			<img id = "HelpPointer" src = "question mark.png"></img>
			<label id = "HelpInfo">Click to approve reviews that are appropriate and click delete to remove them from Campus Compass if they are inappropriate.</label>
		</div>
		<!-- The php below will load in the oldest flagged review from the database and display it on the screen. If there's nothing, nothing will be displayed -->
		<?php 
			include("DisplayFlaggedReview.php");
		?>
		<button class = "Approve" onclick= "location.href='ApproveReviewSQL.php';">Approve</button>
		<button class = "Deny" onclick= "location.href='DeleteReviewSQL.php';">Delete</button>
	</div>
	
</body>
</html>
<style>
	a[href="ApproveReviews.php"]{
		color: rgba(0, 136, 169, 1);
	}
	
	a[href="ApproveReviews.php"]:hover{
		color: rgba(0, 136, 169, 1) !important;
	}
</style>
