<!-- Include The Reviews And Comments PHP Script Into This HTML Document -->
<?php 
include("ReviewsPHP.php"); 
include("Comments.php"); 
include("CommentsPHP.php"); 
unset($_SESSION["Updated"]);
?>

<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8" http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Campus Compass · Reviews</title>
	<link rel="stylesheet" href="MyAccountStyleSheet.css">
	<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
</head>
<body>
	<div class="OverlayBg" id="Overlay"></div>
	<div class="OverlayBg2" id="Overlay2">
		<!-- Form For Submitting A Review -->
		<form method="post" accept-charset="utf-8">
			<h3>Your Review</h3><hr>
			<!-- Displays Errors If The Array Containing The Errors Is Not Empty -->
			<?php if(count($ReviewErrorArray)>0) { ?>
					<div class="ErrorBox">
						<div class="ErrorMessage">
							<?php foreach ($ReviewErrorArray as $Error) { 
									echo "<div style='margin:6.25px 0'>$Error</div>";
								} ?>
						</div>
					</div>
				<?php } ?>
			<textarea id="ReviewText" onkeyup="charNum()" name="UserReview" placeholder="Write Your Review..." 
			cols="10" rows="7" maxlength="500"><?php if(!empty($Review)){ echo $Review; } ?></textarea>
			<p id="charLimit">500 Character Limit</p>
			<!-- Rating Checkbox For The Building -->
			<div id="Ratings">
			<fieldset id="UserRating">
				<input type="radio" name="UserRating" id="5th" value="5"><label for="5th" id="Full"></label>
				<input type="radio" name="UserRating" id="4.5th" value="4.5"><label for="4.5th" id="Half"></label>
				<input type="radio" name="UserRating" id="4th" value="4"><label for="4th" id="Full"></label>
				<input type="radio" name="UserRating" id="3.5th" value="3.5"><label for="3.5th" id="Half"></label>
				<input type="radio" name="UserRating" id="3rd" value="3"><label for="3rd" id="Full"></label>
				<input type="radio" name="UserRating" id="2.5th" value="2.5"><label for="2.5th" id="Half"></label>
				<input type="radio" name="UserRating" id="2nd" value="2"><label for="2nd" id="Full"></label>
				<input type="radio" name="UserRating" id="1.5th" value="1.5"><label for="1.5th" id="Half"></label>
				<input type="radio" name="UserRating" id="1st" value="1"><label for="1st" id="Full"></label>
				<input type="radio" name="UserRating" id="0.5th" value="0.5"><label for="0.5th" id="Half"></label>
				<input type="radio" name="UserRating" id="0th" value="0"><label for="0th" id="None"></label>		
			</fieldset>
			</div>
			<h4 id="RatingValue">(Optional)</h4>
			<div class="formBtn">
				<button type="submit" name="AddReview">Submit Review</button>
				<button type="button" id="Cancel" onclick="DisplayReviewForm()">Cancel</button>
			</div>
		</form>
	</div>
	<div class="OverlayBg3" id="Report">
		<div class="MessageContainer">
			<div class="Title"><h3>Successfully Reported</h3></div><hr>
			<p>Thank You For Reporting The Review. We Will Look Into This Matter And Take Action Accordingly.</p>
			<button onclick="CloseMessage()">Close</button>
		</div>
	</div>
	<h2 id="Title"><?php echo($Building) ?></h2>
	<!-- Displays The Average Rating Of The Building -->
	<div id="RatingContainer">
		<fieldset id="Rating">
			<input type="radio" name="Rating" id="5th" value="5" <?php if($AvgStarRating == 5){ 
				echo 'checked="checked"';} ?> disabled><label for="5th" id="Full" disbaled></label>
			<input type="radio" name="Rating" id="4.5th" value="4.5" <?php if($AvgStarRating == 4.5){ 
				echo 'checked="checked"';} ?> disbaled><label for="4.5th" id="Half" disbaled></label>
			<input type="radio" name="Rating" id="4th" value="4" <?php if($AvgStarRating == 4){ 
				echo 'checked="checked"';} ?> disbaled><label for="4th" id="Full" disbaled></label>
			<input type="radio" name="Rating" id="3.5th" value="3.5" <?php if($AvgStarRating == 3.5){ 
				echo 'checked="checked"';} ?> disbaled><label for="3.5th" id="Half" disbaled></label>
			<input type="radio" name="Rating" id="3rd" value="3" <?php if($AvgStarRating == 3){ 
				echo 'checked="checked"';} ?> disbaled><label for="3rd" id="Full" disbaled></label>
			<input type="radio" name="Rating" id="2.5th" value="2.5" <?php if($AvgStarRating == 2.5){ 
				echo 'checked="checked"';} ?> disbaled><label for="2.5th" id="Half" disbaled></label>
			<input type="radio" name="Rating" id="2nd" value="2" <?php if($AvgStarRating == 2){ 
				echo 'checked="checked"';} ?> disbaled><label for="2nd" id="Full" disbaled></label>
			<input type="radio" name="Rating" id="1.5th" value="1.5" <?php if($AvgStarRating == 1.5){ 
				echo 'checked="checked"';} ?> disbaled><label for="1.5th" id="Half" disbaled></label>
			<input type="radio" name="Rating" id="1st" value="1" <?php if($AvgStarRating == 1){ 
				echo 'checked="checked"';} ?> disbaled><label for="1st" id="Full" disbaled></label>
			<input type="radio" name="Rating" id="0.5th" value="0.5" <?php if($AvgStarRating == 0.5){ 
				echo 'checked="checked"';} ?> disbaled><label for="0.5th" id="Half" disbaled></label>
			<input type="radio" name="Rating" id="0th" value="0" <?php if($AvgStarRating == 0){ 
				echo 'checked="checked"';} ?> disbaled><label for="0th" id="None" disbaled></label>		
		</fieldset>
	</div>
	<div class="ReviewContainer">
		<?php
			// Searches The Database For The User ID
			$GetUser = mysqli_query($Conn, "SELECT UserID FROM Users WHERE Username='$LoggedIn'");
			while($Rows = mysqli_fetch_assoc($GetReviews)){ 
				$ReviewID = $Rows["ReviewID"];
				while($Row = mysqli_fetch_assoc($GetUser)){
					$User = $Row["UserID"];
				}
				// Searches The Database To See If The User Has Liked The Comment
				$PreviouslyLiked = mysqli_query($Conn, "SELECT * FROM Likes WHERE ReviewID = '$ReviewID' AND UserID = '$User'");
				// Searches The Database To See If The User Has Disliked The Comment
				$PreviouslyDisliked = mysqli_query($Conn, "SELECT * FROM Dislikes WHERE ReviewID = '$ReviewID' AND UserID = '$User'");
				// Searches The Database For The Count Of Likes For The Review
				$GetLikes = mysqli_query($Conn, "SELECT COUNT(ReviewID) As Count FROM Likes WHERE ReviewID = '$ReviewID' GROUP BY ReviewID");
				// Searches The Database For The Count Of Dislikes For The Review
				$GetDislikes = mysqli_query($Conn, "SELECT COUNT(ReviewID) As Count FROM Dislikes WHERE ReviewID = '$ReviewID' GROUP BY ReviewID");

				// Sets The Likes To 0 If The Query Returns 0 Rows
				if(mysqli_num_rows($GetLikes) == 0){
					$LikeCounter = 0;
				} else {
					while($Row = mysqli_fetch_assoc($GetLikes)){
						$LikeCounter = $Row["Count"];
					}
				}

				// Sets The Dislikes To 0 If The Query Returns 0 Rows
				if(mysqli_num_rows($GetDislikes) == 0){
					$DislikeCounter = 0;
				} else {
					while($Row = mysqli_fetch_assoc($GetDislikes)){
						$DislikeCounter = $Row["Count"];
					}
				}
				
				// Sets The Timezone To The Current Time In London
				date_default_timezone_set('Europe/London');
				$TimePosted = explode(".", $Rows["DateTime"]);
				// Calculates The Time Difference In Minutes Between The Current Time And The Time The Review Was Published
				$TimeDifferenceMinutes = round(((strtotime($CurrentTime)-strtotime($TimePosted[0]))/60));
		?>
			<div id="Review">
				<div id="UserDetails">
				<?php 
					if($Rows["Status"] == "Activated"){
						echo($Rows["Username"]);
					} else {
						echo("<p style='color:#F44336; display: inline;'>" . $Rows["Username"] . " · This Account Has Been Reported" . "</p>");
					}
					// Checks If The Review Has Been Posted For More Than 1 Week 
					if($TimeDifferenceMinutes > 10080){ ?>
					<div id="Time">
						<?php echo(substr($Rows["DateTime"], 0, 10)); ?>
					</div>
				<?php } 
					// Checks If The Review Has Been Posted For More Than 1 Day
					elseif($TimeDifferenceMinutes > 1440){ 
						$TimeDifferenceDays = substr(($TimeDifferenceMinutes / 1440), 0, 1); ?>
						<div id="Time">
							<?php echo($TimeDifferenceDays . "d"); ?>
						</div>
				<?php } 
					// Checks If The Review Has Been Posted For More Than 1 Hour
					elseif($TimeDifferenceMinutes >= 60){ 
						if(($TimeDifferenceMinutes / 60) >= 10){ ?>
							<div id="Time">
								<?php echo(substr(($TimeDifferenceMinutes / 60), 0, 2) . "h"); ?>
							</div>
						<?php
						} else { ?>
							<div id="Time">
								<?php echo(substr(($TimeDifferenceMinutes / 60), 0, 1) . "h"); ?>
							</div>
				<?php
						}
					} 
					// Outputs How Many Minutes Ago The Review Was Posted
					else { ?>
						<div id="Time">
							<?php echo($TimeDifferenceMinutes . "m"); ?>
						</div>
				<?php } ?>
				</div>
				<div id="ReviewText">
					<?php echo($Rows["Review"]); ?>
				</div>
				
				<div class="Buttons">
					<!-- Form That Passes The Review And User ID To The Reviews PHP Script -->
					<form method="post" accept-charset="utf-8">
						<input value="ReviewID=<?php echo $ReviewID ?>, UserID=<?php echo $User ?>" 
						name="Review[<?php echo $Count ?>]" hidden>
						<input value="<?php echo $Count ?>" name="Key" hidden>
						<button type="submit" class="FlagReview" name="Flag"><i class="fa fa-flag"></i></button>
						<button type="submit" class="Like" name="Like" 
						id="ReviewID=<?php echo $ReviewID ?>, UserID=<?php echo $User ?>"><?php echo $LikeCounter ?>
						<i class="fa fa-thumbs-up"></i></button>
						<button type="submit" class="Dislike" name="Dislike" 
						id="ReviewID=<?php echo $ReviewID ?>, UserID=<?php echo $User ?>"><?php echo $DislikeCounter ?>
						<i class="fa fa-thumbs-down"></i></button>
					</form>
				</div>
				<?php
					echo '<p onclick=displayComments(' . $Count . ') class="CommentsButton"><i class="fa fa-chevron-down"></i> View Comments</p>'; ?>
						<form method="post" accept-charset="utf-8" class="commentForm">
							<?php 
							if(count($commentError)>0 && $reviewID != null && ($reviewID == $ReviewID)){ ?>
								<div class="ErrorBox">
									<div class="ErrorMessage">
										<?php foreach ($commentError as $Error){ 
											echo $Error;
										} ?>
									</div>
								</div>
							<?php } ?> 
							<textarea class="Comment" name="UserComments" placeholder="Write A Comment..." cols="10" rows="7" maxlength="500"></textarea>
							<button name="addComment" class="commentButton" value=<?php echo $ReviewID . "|" . $Count ?>>Reply</button>
						  </form> 
				<?php
						Comments($ReviewID);
					// Changes The Like Colour Button If The Review Was Previously Liked 
					if(mysqli_num_rows($PreviouslyLiked) > 0){
						echo "<style>.Buttons .Like[id='ReviewID=$ReviewID, UserID=$User'] i{ color: #04AA6D !important }</style>";
					}	
					// Changes The Dislike Colour Button If The Review Was Previously Disliked 			
					if(mysqli_num_rows($PreviouslyDisliked) > 0){
						echo "<style>.Buttons .Dislike[id='ReviewID=$ReviewID, UserID=$User'] i{ color: #F44336 !important }</style>";
					}
				?>
			</div>
		<?php $Count += 1; } ?>
	</div>
	<button class="NewReview" id="NewReview" onclick="DisplayReviewForm()">Leave A Review</button>
		<div class="Options" id="Choice">
	      <a href="?SortBy=Top">Top</a>
	      <a href="?SortBy=Controversial">Controversial</a>
	      <a href="?SortBy=Oldest">Oldest</a>
	      <a href="?SortBy=Newest">Newest</a>
		</div>
	  	<div class="SortBy" onclick="DisplayList()" id="SortByBtn">
	    	<button id="SortBy">Sort By<i class="fa fa-caret-up"></i></button>
	  	</div>
</body>
</html>
<script>

	// Passes The Value From PHP To JavaScript Through JSON_ENCODE
	var AddedReview = <?php echo json_encode($ReviewError, JSON_HEX_TAG); ?>;
	var ErrorPresent = <?php echo json_encode($errorPresent, JSON_HEX_TAG); ?>;
	var CancelBtn = document.getElementById("Cancel");
	let Stars = document.querySelectorAll("input[type=radio]");
	let StarsValue = document.querySelector("#RatingValue");
	var DropDown = document.getElementById("Choice");
	var Overlay2 = document.getElementById("Overlay2");
	var CharLimit = document.getElementById("charLimit");

	if(ErrorPresent != null){
		document.getElementsByClassName('CommentContainer')[ErrorPresent].style.display = 'block';
		document.getElementsByClassName('commentForm')[ErrorPresent].style.display = "block";
		document.getElementsByClassName('CommentsButton')[ErrorPresent].innerHTML = "<i class='fa fa-chevron-up'></i> Hide Comments"
	}

	// Displays The Star Rating When The User Clicks A Rating Level
	for(let x = 0; x < Stars.length; x++){
		Stars[x].addEventListener("click", function() {
			x = this.value;
			StarsValue.innerHTML = x + " Out Of 5";
		})
	}

	// Displays The Review Form When The User Clicks The Form Button
	function DisplayReviewForm(){
		if(!Overlay2.style.display || Overlay2.style.display  == "none"){
			Overlay2.style.display = "block";	
		} else {
			Overlay2.style.display = "none";
		}
	}

	// Displays The Sort By Options To Change The Reviews Order When The User Clicks The Buttons 
	function DisplayList(){
		if(!DropDown.style.display || DropDown.style.display == "none"){
			document.getElementById("SortByBtn").style.zIndex = "15";
			DropDown.style.display = "block";
			document.getElementById("Overlay").style.display = "block";	
		} else {
			document.getElementById("SortByBtn").style.zIndex = "1";
			DropDown.style.display = "none";
			document.getElementById("Overlay").style.display = "none";
		}
	}

	// Tells The User The Number Of Characters Remaining For The Review 
	function charNum(){
		var Review = document.getElementById('ReviewText').value.length;
		if(Review == 0){
			CharLimit.innerHTML = "500 Character Limit";
		} else {
			CharLimit.innerHTML = (500-Review) + " Characters Remaining";
		}	
	}

	// Hides And Displays The Comments For A Review
	function displayComments(Num){
		if(!document.getElementsByClassName('CommentContainer')[Num].style.display || document.getElementsByClassName('CommentContainer')[Num].style.display == "none"){
			document.getElementsByClassName('CommentContainer')[Num].style.display = 'block';
			document.getElementsByClassName('commentForm')[Num].style.display = "block";
			document.getElementsByClassName('CommentsButton')[Num].innerHTML = "<i class='fa fa-chevron-up'></i> Hide Comments"
		} else {
			document.getElementsByClassName('CommentContainer')[Num].style.display = "none";
			document.getElementsByClassName('commentForm')[Num].style.display = "none";
			document.getElementsByClassName('CommentsButton')[Num].innerHTML = "<i class='fa fa-chevron-down'></i> View Comments"
		}
	}

	// Hides The Review Form If The Review Has Been Submitted
	if(AddedReview == 1){
		Overlay2.style.display = "block";
		CancelBtn.addEventListener('click', () => 
			Overlay2.style.display = "none"
		);
	}

	// Closes The Reported Review Message When The User Click The Close Button 
	function CloseMessage(){
		document.getElementById("Report").style.setProperty("display", "none", "important");
	}

</script>
<style>
	a[href="MyAccount.php"]{
		color: rgba(0, 136, 169, 1);
	}
	a[href="MyAccount.php"]:hover{
		color: rgba(0, 136, 169, 1) !important;
	}
</style>