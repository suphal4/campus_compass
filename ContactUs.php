<?php 
include_once("NavBar.php"); 
include_once("ContactUsPHP.php");
?>
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://kit.fontawesome.com/aab591f857.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="ContactUsStyleSheet.css">
</head>
<body>
    <div class="ContactPage">
    	<div class="ContactForm">
    		<form method="post" accept-charset="utf-8">
    			<h3>Leave Your Feedback</h3>
    			<?php if(count($feedbackArray) > 0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($feedbackArray as $Error) { 
									echo "<div style='margin: 3.5px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php } ?>
				<?php if(isset($_SESSION["MailSent"]) && $_SESSION["MailSent"] == 1) { ?>
					<div class="ErrorBox" style="background: #90EE90; border-color: #00712D;">
						<div class="ErrorMessage" style="color: #00712D;">
							Your Feedback Has Successfully Been Sent. We Will Respond To You In Due Course If Needed
						</div>
					</div>
				<?php }
				if(isset($_SESSION["MailSent"]) && $_SESSION["MailSent"] == 0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							Failed To Send Your Feedback. Please Try Again Later
						</div>
					</div>
				<?php } ?>
    			<input type="text" name="fName" placeholder="Full Name" value="<?php echo $fName ?>">
    			<input type="text" name="emailAddress" placeholder="Email Address" value="<?php echo $emailAddress ?>">
    			<input type="text" name="messageSubject" placeholder="Subject" value="<?php echo $messageSubject ?>">
    			<textarea placeholder="Write Your Message..." cols="10" rows="7" name="messageField"><?php if($messageField != ""){ echo htmlentities ($messageField); } ?></textarea>
    			<button type="submit" name="Feedback">Submit Feedback</button>
    		</form>
    	</div>
    	<div class="ContactDetails">
    		<h3>How Can We Help?</h3>
    		<div class="Phone">
    			<i class='fa fa-mobile-alt'></i><br>
    			<a href="sms:074305758674"><button>Text Us</button></a>  
    		</div>
    		<div class="Email">
    			<i class="material-icons" style="margin-top: 6px;">mail</i>
    			<a href="mailto:uom.y18@gmail.com"><button>Drop A Mail</button></a> 
    		</div>
    		<div class="Maps">
    			<i class='fas fa-map-marked-alt'></i>
    			<a href="https://www.google.com/maps/place/Engineering+Building+A/@53.4691725,-2.236098,17z/data=!3m1!4b1!4m5!3m4!1s0x487bb1ce9b2ee58f:0x6932db0c80475615!8m2!3d53.4691693!4d-2.2339093">
    			<button type="">Find Us</button></a>  
    		</div>
    		<div class="socialMedia">
    			<i class="fa fa-instagram"></i>
    			<button type="">Instagram</button>  
    		</div>
    	</div>
    </div>
</body>
</html>
<style>
	a[href="ContactUs.php"]{
		color: #D43F3A;
	}
	a[href="ContactUs.php"]:hover{
		color: #D43F3A !important;
	}
</style>