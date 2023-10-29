<?php 
include("OTP.php"); 
include("CheckOTP.php"); 
// Checks If The OTP Has Benn Sent Before Allowing Access To This Page
if (isset($_SESSION['OTPSent'])){
	$Sent = 1;
} else {
	$Sent = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Campus Compass · Forgot Password</title>
	<link rel="stylesheet" href="ForgotPasswordOTPStyleSheet.css">
	<script src="https://www.google.com/recaptcha/api.js?render=6LeYYnQdAAAAADzsZvpgdKTOsYaARwzbILkUUoIy"></script>
    <script src="reCaptcha.js"></script>
</head>
<body>
	<div class="container" id="container">
		<div class="formcontainer otpcontainer">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" accept-charset="utf-8">
				<h3 class="TheTitle">One-Time Password</h3>
				<h5>By Students For Students</h5>
				<!-- Displays Errors If The Array Containing The Errors Is Not Empty -->
				<?php if(count($OTPError)>0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($OTPError as $Error) { 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php } ?>
	      		<input type="text" name="OTP" placeholder="OTP" maxlength="6">
	      		<button type="submit" name="VerifyOTP">Verify OTP</button>
	      		<p style="margin: 8px 0;">Attempts Remaining: <?php echo $_SESSION['Attempts']?></p>
			</form>
		</div>
		<div class="formcontainer forgotpasswordcontainer">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" accept-charset="utf-8">
				<h3 class="TheTitle">Forgot Password</h3>
				<h5>By Students For Students</h5>
				<!-- Displays Errors If The Array Containing The Errors Is Not Empty -->
				<?php if(count($ForgotPasswordError)>0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($ForgotPasswordError as $Error) { 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php } 
				// Displays Errors If The Array Containing The Errors Is Not Empty
				if((isset($_SESSION['OTPSent'])) and ($_SESSION['OTPSent'] == 0)){ ?>
	        		<div class="ErrorBox">
	          			<div class="ErrorMessage">
	            			Failed To Send OTP. Please Try Again Later
	          			</div>
	        		</div>
	      		<?php unset($_SESSION['OTPSent']); } ?>
				<input type="text" name="UserOrEmail" placeholder="Email Or Username">
				<button type="submit" name="CheckUser">Send OTP</button>
				<a href="Account.php">Return To Log In</a>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-left">
					<h3>Enter Your OTP</h3>
					<p>The OTP Has Been Sent To Your UoM Email</p>
					<img class="Img2" src="Logo1.png" alt="Campus Compass Logo">
				</div>
				<div class="overlay-panel overlay-right">
					<h3>Forgotten Password?</h3>
					<p>Enter Your UoM Email Address Or Your Username To Verify Your Account. Your Account Must Be Activated To Change Your Password.</p>
					<img src="Logo1.png" alt="Campus Compass Logo">
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">


		const Container = document.getElementById('container');
		var Sent = <?php echo json_encode($Sent, JSON_HEX_TAG); ?>;
		// Adds/Removes A Class Depending On If The OTP Has Been Sent
		if(Sent == 1){
			Container.classList.add('right-panel-active');
		} else {
			Container.classList.remove('right-panel-active');
		}


	</script>
</body>
</html>