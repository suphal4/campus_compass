<?php include_once("AccountNavBar.php"); include("SettingsPHP.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8" http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Campus Compass · Settings</title>
    <link rel="stylesheet" href="MyAccountStyleSheet.css">
</head>
<body>
	<div class="SettingsContainer">
		<form method="post" accept-charset="utf-8">
			<h3>My Account Details</h3>
			<?php if(count($changeAccountSettings)>0){ ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($changeAccountSettings as $Error){ 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
			<?php } ?>
			<?php if(isset($_SESSION['Updated']) && ($_SESSION['Updated'] == 1)){ ?>
					<div class="ErrorBox" style="background: #90EE90; border-color: #00712D;">
						<div class="ErrorMessage" style="color: #00712D;">
							Your Account Details Have Successfully Been Updated
						</div>
					</div>
			<?php } ?>
			<?php if(isset($_SESSION['Updated']) && ($_SESSION['Updated'] == 0)){ ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							Failed To Update Account Details. Please Try Again Later
						</div>
					</div>
			<?php } ?>
			<input type="text" value="<?php echo $Email ?>" disabled="true" style="color: gray;">
			<input value="<?php echo $currentUsername; ?>" type="text" name="myUsername" placeholder="Username">
			<input type="password" name="currentPassword" placeholder="Current Password">
			<input type="password" name="newPassword" placeholder="New Password">
			<input type="password" name="confirmNewPassword" placeholder="Confirm New Password">
			<button type="submit" name="updateDetails">Update Details</button>
		</form>
	</div>
</body>
</html>
<style>
	a[href="Settings.php"]{
		color: rgba(0, 136, 169, 1);
	}
	
	a[href="Settings.php"]:hover{
		color: rgba(0, 136, 169, 1) !important;
	}
</style>