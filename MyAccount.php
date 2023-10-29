<?php 

// Includes The MySQLI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");
include('AccountNavBar.php');
include('GetStats.php');

// Checks If The User Is Logged In
if(!isset($_SESSION['Username'])){
	header("Location: Account.php");
} else {
	$Username = $_SESSION['Username'];
	// Searches If The Users' Account Is Activated
	$Status = mysqli_query($Conn, "SELECT Status FROM Users WHERE Username = '$Username'");
	// Redirects The User Back To The Log In Page If There Accounts Aren't Activated
	if(mysqli_num_rows($Status) > 0){
		while($Row = mysqli_fetch_assoc($Status)){
			if(($Row["Status"]) == "Pending"){	
				header("Location: LogOut.php");	
			} 
		} 
	}
}

// Checks If The User Has Inputted A Building Name
if(isset($_POST["FindReviews"])){
	if(!empty($_POST["Search"]) or ($_POST["Search"] != "Building Not Found")){
		$Building = $_POST['Search'];
		// Searches For The Building In The Database By The Building Name 
		if(mysqli_num_rows(mysqli_query($Conn, "SELECT * FROM Location WHERE Name = '$Building'")) > 0){
			$_SESSION["Building"] = $Building;
			header("Location: Reviews.php");
		}
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8" http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Campus Compass · My Account</title>
	<link rel="stylesheet" href="MyAccountStyleSheet.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
	<div class="OverlayBg" id="Overlay" onclick="restoreBackground()"></div>
	<form method="post" id="SearchBuilding">
			<input onclick="changeBackground()" onfocus="changeScale()" type="text" name="Search" id="Input" placeholder="Search For A Building">
			<button name="FindReviews" type="submit"><i class="fa fa-search"></i></button>
			<div id="Buildings"></div>
	</form>
	<div class="MostLikedReviews">
		<h3>Most Liked Reviews</h3>
		<?php MostLikedReviews(); ?>
	</div>
	<div class="MyReviews">
		<h3>My Reviews</h3>
		<?php MyReviews(); ?>
	</div>
	<div class="MyStatistics">
		<span>My Stats</span>
		<div class="MyLikes">
			<span class="ExtraInfo">Total Number Of Likes On Your Reviews</span>
			<i class="fa fa-thumbs-up" onmouseover="viewToolTip1()" onmouseout="viewToolTip1()"></i>
			<?php Likes(); ?>
		</div>
		<div class="MyDislikes">
			<span class="ExtraInfo">Total Number Of Dislikes On Your Reviews</span>
			<i class="fa fa-thumbs-down" onmouseover="viewToolTip2()" onmouseout="viewToolTip2()"></i>
			<?php Dislikes(); ?>
		</div>
		<div class="MyComments">
			<span class="ExtraInfo">Total Number Of Reviews By You</span>
			<i class="fa fa-comments" onmouseover="viewToolTip3()" onmouseout="viewToolTip3()"></i>
			<?php Reviews(); ?>
		</div>
	</div>
</body>
</html>
<style>
	a[href="MyAccount.php"]{
		color: rgba(0, 136, 169, 1);
	}
	
	a[href="MyAccount.php"]:hover{
		color: rgba(0, 136, 169, 1) !important;
	}
</style>
<script>

	Overlay = document.getElementById("Overlay");
	SearchBuilding = document.getElementById("SearchBuilding");
	Search = document.getElementById("Input");
	Buildings = document.getElementById("Buildings");

// jQuery Which Submits The Form To The PHP Script (SearchBuilding.php) As The User Types The Building Name
$(document).ready(function() {
	$("#Input").keyup(function(){
		var UoMBuilding = $(this).val();
		if(UoMBuilding != ""){
			$.ajax({
				url:"SearchBuilding.php",
				method:"POST",
				data:{Building:UoMBuilding},
				success:function(data){
					$("#Buildings").fadeIn();
					$("#Buildings").html(data);
				}
			});
		} else {
			$("#Buildings").fadeOut();
			$("#Buildings").html("");
		}
	});
	// Displays The Building Names As The User Types
	$(document).on('click', '#University', function(){
		$("#Input").val($(this).text());
		$("#Buildings").fadeOut();
	});
});

// Adds The Dark Focused Background On The Search Bar When The Search Bar Is Clicked
function changeBackground(){
		if(!Overlay.style.display || Overlay.style.display == "none"){
			Overlay.style.display = "block";
			Buildings.style.display = "block";	
			SearchBuilding.style.zIndex = "15";
		} 
	}

// Removes The Dark Focused Background On The Search Bar When The Background Is Clicked
function restoreBackground(){
	if(Overlay.style.display || Overlay.style.display == "block"){
		Overlay.style.display = "none";
		Buildings.style.display = "none";
	}
}

// Changes The Size Of The Search Bar For A Set Time When They Click It And Different Sizes Depending On Screen Width
function changeScale(){
	if(screen.width > 730){
		document.getElementById("SearchBuilding").style.transform = "scale(1.075)";
	} else {
		document.getElementById("SearchBuilding").style.transform = "scale(1.025)";
	}
	setTimeout(function(){
		document.getElementById("SearchBuilding").style.transform = "scale(1)";
	}, 200); 
}

// Displays The Details About Like Tooltip
function viewToolTip1(){
	if(!document.getElementsByClassName("ExtraInfo")[0].style.display || document.getElementsByClassName("ExtraInfo")[0].style.display == "none"){
		document.getElementsByClassName("ExtraInfo")[0].style.display = "block";
	} else {
		document.getElementsByClassName("ExtraInfo")[0].style.display = "none";
	}
}

// Displays The Details About Dislike Tooltip
function viewToolTip2(){
	if(!document.getElementsByClassName("ExtraInfo")[1].style.display || document.getElementsByClassName("ExtraInfo")[1].style.display == "none"){
		document.getElementsByClassName("ExtraInfo")[1].style.display = "block";
	} else {
		document.getElementsByClassName("ExtraInfo")[1].style.display = "none";
	}
}

// Displays The Details About Reviews Tooltip
function viewToolTip3(){
	if(!document.getElementsByClassName("ExtraInfo")[2].style.display || document.getElementsByClassName("ExtraInfo")[2].style.display == "none"){
		document.getElementsByClassName("ExtraInfo")[2].style.display = "block";
	} else {
		document.getElementsByClassName("ExtraInfo")[2].style.display = "none";
	}
}

</script>