<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8" http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="MyAccountStyleSheet.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="https://web.cs.manchester.ac.uk/h61781jp/FirstYearTeamProject/Symbol1.png">
</head>

<body>
	<header>
		<img class="Logo" src="Logo1.png" onclick="ReturnToHome()">
		<label for="CheckBox" class="CheckBtn"><i class="fa fa-bars"></i></label>
		<nav>
			<input type="checkbox" id="CheckBox">
			<ul class="Links">
				<li><a href="Home.php">Home</a></li>
				<li><a href="MyAccount.php">Reviews</a></li>
				<li><a href="MapIndex.php">Interactive Map</a></li>
				<li><a href="AddLocation.php">Request Location</a></li>
				<!-- <li><a href="ApproveRequest.php">Admin</a></li> -->
				<?php include("AdminDropDown.php");  ?>
				<div class ='NavBarDropDown'>
					<li class ='DropDownPointer'>Other</li>
					<div class ='DropDownContent'>
						<li><a href='ContactUs.php'>Contact Us</a></li><br>
						<li><a href= 'Settings.php'>Settings</a></li><br>
					</div>
				</div>
				<?php
				// Changes The Button Text Displayed To The User Based On If They Are Logged In
				echo "<li><a class='LogOut' href='LogOut.php' id='LogOut'>Log Out</a></li>";
				?>
			</ul>
		</nav>
		<a href="LogOut.php"><button class="LogOut">Log Out</button></a>
	</header>
</body>

</html>
<script type="text/javascript">
	// Function To Return To The Homepage When The Company Logo Is Clicked
	function ReturnToHome() {
		window.location.href = "Home.php"
	}
</script>