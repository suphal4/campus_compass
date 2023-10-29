<?php
error_reporting(0);
include("NavBar.php"); 
unset($_SESSION["MailSent"]);
include_once("ConnectionToMySQL.php");
$getCountOfUsers = mysqli_query($Conn, "SELECT COUNT(*) AS CNT FROM Users");
while($Row = mysqli_fetch_assoc($getCountOfUsers)){
	$countOfUsers = $Row["CNT"];
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://kit.fontawesome.com/aab591f857.js" crossorigin="anonymous"></script>
	<title>Campus Compass · Home</title>
	<link rel="stylesheet" href="MainStyleSheet.css">
</head>
<body>
	<div class="HomeContainer">
		<div class="Slideshow">
			<div class="fadeSlide mySlide">
				<div class="numberPosition">1 / 5</div>
    			<img src="UOMMainBuilding.jpg" style="width:100%">
			</div>	
			 <div class="fadeSlide mySlide">
		    	<div class="numberPosition">2 / 5</div>
			    <img src="EngineeringBuilding.jpg" style="width:100%">
		  	</div>
		  	<div class="fadeSlide mySlide">
		    	<div class="numberPosition">3 / 5</div>
		    	<img src="UOMSU.jpg" style="width:100%">
		  	</div>
		  	<div class="fadeSlide mySlide">
		    	<div class="numberPosition">4 / 5</div>
		    	<img src="AlanGilbert.jpg" style="width:100%">
		  	</div>
		  	<div class="fadeSlide mySlide">
		    	<div class="numberPosition">5 / 5</div>
		    	<img src="KilburnBuilding.jpg" style="width:100%">
		  	</div>
		  	<div class="Indicators">
			  <span class="Indicator"></span> 
			  <span class="Indicator"></span> 
			  <span class="Indicator"></span> 
			  <span class="Indicator"></span> 
			  <span class="Indicator"></span> 
			</div>
		</div>
		<div class="Stats">
			<a href="Account.php"><div class="StatsText">Join Our Community Of <b style="font-weight: 900;font-size: 1.375em;"><?php echo $countOfUsers ?></b> Students</div></a>
			
		</div>
		<div class="AboutUs">
			<h3>About Us</h3><hr id="Break" data-symbol="∞">
			<p>We are a group of first year computer science students who created this website to assist new and existing university students to help them settle in, communicate, socialise and easily get familiarised with the campus. This website aims to solve common problems faced by students both studying for an undergraduate or postgraduate degree. Also, only students attending the University of Manchester can access our system so you can be rest assured that the reviews are written by people who have first-hand experience. We strongly believe in keeping our platform safe and free of all types of abuse hence we have incorporated multiple features such as review moderation to ensure that our website doesn't promote hate and discrimination.</p>
			<img src="Logo1.png" alt="Campus Compass Logo">
		</div>
	</div>
</body>
</html>
<script>

var CurrentSlide = 0;

displaySlide();

function displaySlide(){
  var Slides = document.getElementsByClassName("fadeSlide");
  var Indicator = document.getElementsByClassName("Indicator");

  for (X = 0; X < Slides.length; X++){
    Slides[X].style.display = "none";  
  }

  CurrentSlide++;

  if (CurrentSlide > Slides.length){
  	CurrentSlide = 1;
  }

  for (X = 0; X < Indicator.length; X++){
    Indicator[X].style.backgroundColor = "#BBB";
  }

  Slides[CurrentSlide - 1].style.display = "block";
  Indicator[CurrentSlide - 1].style.backgroundColor = "#717171";

  setTimeout(displaySlide, 4000); 

}
</script>
<style>
	a[href="Home.php"]{
		color: #D43F3A;
	}	
	a[href="Home.php"]:hover{
		color: #D43F3A !important;
	}
</style>