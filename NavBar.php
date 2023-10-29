<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8" http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="stylesheet" href="MainStyleSheet.css">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
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
				<li><a href="ContactUs.php">Contact Us</a></li>
				<?php 
				// Changes The Button Text Displayed To The User Based On If They Are Logged In
				if(isset($_SESSION["Username"])){
					echo "<li><a class='Account' href='MyAccount.php'>Account</a></li>";
				} else {
					echo "<li><a class='Account' href='Account.php'>Log In</a></li>";
				}
				?>
			</ul>
		</nav>
		<?php 
			// Changes The Button Text Displayed To The User Based On If They Are Logged In 
			if(isset($_SESSION["Username"])){
				echo '<a href="MyAccount.php" class="LogInBtn"><button>Account</button></a>';
			} else {
				echo '<a href="Account.php" class="LogInBtn"><button>Log In</button></a>';
			}
		?>
	</header>
</body>
</html>
<script type="text/javascript">

	// Function To Return To The Homepage When The Company Logo Is Clicked
	function ReturnToHome(){
		window.location.href = "Home.php"
	}

</script>