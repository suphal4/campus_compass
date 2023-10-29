<?php 
// Include The Sign Up And Log In PHP Scripts
include("LogInAuthentication.php"); 
include("SignUpAuthentication.php"); 
error_reporting(0); 
unset($_SESSION["MailSent"]);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Campus Compass · Account</title>
	<link rel="stylesheet" href="LogInSignUpStyleSheet.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="https://web.cs.manchester.ac.uk/h61781jp/FirstYearTeamProject/Symbol1.png">
	<script src="https://www.google.com/recaptcha/api.js?render=6LeYYnQdAAAAADzsZvpgdKTOsYaARwzbILkUUoIy"></script>
    <script src="reCaptcha.js"></script>
</head>
<body>
	<div class="container" id="container">
		<div class="formcontainer signupcontainer">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off" accept-charset="utf-8">
				<h3>Create Account</h3>
				<h5>By Students For Students</h5>
				<!-- Displays Errors If The Array Containing The Errors Is Not Empty -->
				<?php if(count($SignUpErrors)>0){ ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($SignUpErrors as $Error){ 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php } 
					if($Failure != ""){ ?>
        			<div class="ErrorBox">
          				<div class="ErrorMessage">
            				<?php echo $Failure ?>
          				</div>
        			</div> 
      			<?php } ?>
				<input type="text" name="Email" placeholder="University Email" value="<?php echo $Email ?>">
				<input type="text" name="UsernameNew" placeholder="Username" value="<?php echo $UsernameNew ?>">
				<input type="password" name="PasswordNew" id="Password" placeholder="Password">
				<input type="password" name="ConfirmPassword" id="ConfirmPassword" placeholder="Confirm Password">
				<button type="submit" name="SignUp">Sign Up</button>
			</form>
		</div>
		<div class="formcontainer logincontainer">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" autocomplete="off" accept-charset="utf-8">
				<h3>Enter Account</h3>
				<h5>By Students For Students</h5>
				<!-- Displays Errors If The Array Containing The Errors Is Not Empty -->
				<?php if(count($LogInErrors)>0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($LogInErrors as $Error) { 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php }
				// Displays Error Message If The Account Wasn't Created
				if((isset($_SESSION['AccountCreated'])) and ($_SESSION['AccountCreated'] == 0)){ ?>
        			<div class="ErrorBox">
          				<div class="ErrorMessage">
            				Failed To Create Your Account. Please Try Again Later
          				</div>
        			</div>
      			<?php unset($_SESSION['AccountCreated']); }
      			// Displays Success Message If The Account Was Created
      			elseif((isset($_SESSION['AccountCreated'])) and ($_SESSION['AccountCreated'] == 1)){ ?>
        			<div class="ErrorBox" style="background: #90EE90; border-color: #00712D;">
          				<div class="ErrorMessage" style="color: #00712D;">
            				Please Activate Your New Account From Your UoM Email
          				</div>
        			</div>
      			<?php unset($_SESSION['AccountCreated']); }
      			// Displays Error Message If There Was An Error In Activating The Account 
      			if((isset($_SESSION['Verified'])) and ($_SESSION['Verified'] == 0)){ ?>
        			<div class="ErrorBox">
          				<div class="ErrorMessage">
            				Failed To Activate Your Account. Please Try Again Later
          				</div>
        			</div>
      			<?php unset($_SESSION['Verified']); }
				// Displays Success Message If The Account Was Activated Successfully
      			elseif((isset($_SESSION['Verified'])) and ($_SESSION['Verified'] == 1)){ ?>
        			<div class="ErrorBox" style="background: #90EE90; border-color: #00712D;">
          				<div class="ErrorMessage" style="color: #00712D;">
            				Your Account Has Successfully Been Activated
          				</div>
        			</div>
      			<?php unset($_SESSION['Verified']); }
      			// Displays Error Message If There Was An Error In Updating The Password 
      			if((isset($_SESSION['Updated'])) and ($_SESSION['Updated'] == 0)){ ?>
        			<div class="ErrorBox">
          				<div class="ErrorMessage">
            				Failed To Update Your Password. Please Try Again Later
          				</div>
        			</div>
      			<?php unset($_SESSION['Updated']); }
      			// Displays Success Message If The Password Was Updated Successfully
      			elseif((isset($_SESSION['Updated'])) and ($_SESSION['Updated'] == 1)){ ?>
        			<div class="ErrorBox" style="background: #90EE90; border-color: #00712D;">
          				<div class="ErrorMessage" style="color: #00712D;">
            				Your Password Has Successfully Been Updated
          				</div>
        			</div>
      			<?php unset($_SESSION['Updated']); } ?>
				<input type="text" name="UsernameLogIn" value="<?php echo $Username ?>" placeholder="Username">
				<input type="password" name="PasswordLogIn" id="PasswordLogIn" placeholder="Password">
				<button type="submit" name="LogIn">Log In</button>
      			<a href="ForgotPassword.php" class="Forgot">Forgotten Your Password?</a>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h3 class="Title" id="AccessAccount">Access Your Account</h3>
					<p class="Message">Log In To Your Account To Read And Publish Reviews</p>
					<button class="ghost" id="logIn">Log In</button>
					<label><i class="fa fa-home" aria-hidden="true" onclick="ReturnToHome()"></i></label>
					<img class="Image" src="Logo1.png" alt="Campus Compass Logo">
				</div>
				<div class="overlay-panel overlay-right">
					<h3 class="Title" id="NewAccount">Create New Account</h3>
					<p class="Message">Sign Up Using Your UoM Email To Interact With Other Students</p>
					<button class="ghost" id="signUp">Sign Up</button>
					<label><i class="fa fa-home" aria-hidden="true" onclick="ReturnToHome()"></i></label>
					<img src="Logo1.png" alt="Campus Compass Logo">
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">

	// Passes The Value From PHP To JavaScript Through JSON_ENCODE
	var ErrorExists = <?php echo json_encode($SignUpError, JSON_HEX_TAG); ?>;
	const SignUpBtn = document.getElementById('signUp');
	const LogInBtn = document.getElementById('logIn');
	const HomeBtn = document.getElementById('Home');
	const Container = document.getElementById('container');
	
	// Shows The Sign Up Section Of The Form On Page Refresh If There Was An Error In That Form
	if(ErrorExists == 1){
		Container.classList.add('right-panel-active');
		SignUpBtn.addEventListener('click', () => 
			Container.classList.add('right-panel-active')
		);
		LogInBtn.addEventListener('click', () => 
			Container.classList.remove('right-panel-active')
		);
	} else {
		SignUpBtn.addEventListener('click', () => 
			Container.classList.add('right-panel-active')
		);
		LogInBtn.addEventListener('click', () => 
			Container.classList.remove('right-panel-active')
		);
	}

	// Function To Return To The Homepage When The Company Logo Is Clicked
	function ReturnToHome(){
		window.location.href = "Home.php"
	}

</script>