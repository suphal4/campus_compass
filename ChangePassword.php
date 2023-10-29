<?php include("UpdatePassword.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Campus Compass · Change Password</title>
	<link rel="stylesheet" href="ForgotPasswordOTPStyleSheet.css">
	<script src="https://www.google.com/recaptcha/api.js?render=6LeYYnQdAAAAADzsZvpgdKTOsYaARwzbILkUUoIy"></script>
    <script src="reCaptcha.js"></script>
</head>
<body>
	<div class="container">
		<div class="formcontainer changepasswordcontainer">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" accept-charset="utf-8">
				<h3>Change Password</h3>
				<h5>By Students For Students</h5>
				<!-- Displays Errors If The Array Containing The Errors Is Not Empty -->
				<?php if(count($ChangePasswordErrors)>0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($ChangePasswordErrors as $Error) { 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php } ?>
				<input type="password" id="Password" name="Password" placeholder="New Password">
				<input type="password" id="ConfirmPassword" name="ConfirmPassword" placeholder="Confirm Password">
				<button type="submit" name="UpdatePassword">Change Password</button><br>
			</form>
		</div>
		<div class="overlay-container">
			<div class="overlay">
				<div class="overlay-panel overlay-right">
					<h3 class="ChangePassword">Change Your Password</h3>
					<p>Choose A Strong Password To Keep Your Account Secure And Protected</p>
					<img src="Logo1.png" alt="Campus Compass Logo">
				</div>				
			</div>
		</div>
	</div>
</body>
</html>