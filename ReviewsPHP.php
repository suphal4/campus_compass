<?php

// Includes The MySQLI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");

// Includes The Navigation Bar On The Reviews Page
include("AccountNavBar.php");

$AvgStarRating = 0;
$ReviewCount = 0;
$ReviewError = 0; 
$Rating = 0;
$Count = 0;
$Review = "";
$CurrentTime = date('Y-m-d H:i:s');
$ReviewErrorArray = array();

// Checks If User Is Logged In
if(!isset($_SESSION["Username"])) {
	header("Location: Account.php");
// Checks If The Buidling Being Searched About Is Set In The Session Variables
// } elseif(!isset($_SESSION["Building"])) {
// 	header("Location: MyAccount.php");
} else {
	$LoggedIn = $_SESSION["Username"];
	$Building = $_SESSION["Building"];

	// Checks If A Users' Review Is Reported And Displays A Message Accordingly
	if(isset($_SESSION["Reported"])){
		echo "<style>.OverlayBg3{display: block !important;}</style>";
		unset($_SESSION["Reported"]);
	}

	// Searches For The ID Of The Building In The Database
	$GetBuildingID = mysqli_query($Conn, "SELECT LocationID FROM Location WHERE Name = '$Building' LIMIT 1");
	while($Rows = mysqli_fetch_assoc($GetBuildingID)){
		$BuildingID = $Rows["LocationID"];
	}

	// Searches And Tallies Up The Total Star Rating For That Buidling From The Database 
	$GetStarRating = mysqli_query($Conn, "SELECT StarRating FROM Reviews WHERE LocationID = '$BuildingID'");
	while($Rows = mysqli_fetch_assoc($GetStarRating)){
		if($Rows["StarRating"] == 0){
			continue;
		}
		$AvgStarRating += $Rows["StarRating"];
		$ReviewCount += 1;
	}

	// Calculates The Average Star Rating For The Building
	if($ReviewCount != 0){
		$AvgStarRating /= $ReviewCount;
	} else {
		$AvgStarRating = 0;
	}

	$AvgStarRating = round($AvgStarRating * 2) / 2;

	// Checks If The User Has Chosen To Sort The Reviews by A Custom Type And Performs A Different SQL Query For Each Option
	if(isset($_GET['SortBy'])){
		if($_GET["SortBy"] == "Oldest"){
			$GetReviews = mysqli_query($Conn, "SELECT Reviews.Review, Reviews.ReviewID, Reviews.UserID, Users.Username, Users.Status, Reviews.DateTime, Reviews.StarRating FROM Reviews INNER JOIN Users ON Users.UserID = Reviews.UserID WHERE Reviews.LocationID = '$BuildingID' AND Reviews.Status = 'Published' ORDER BY Reviews.DateTime ASC");
		} elseif($_GET["SortBy"] == "Controversial"){
			$GetReviews = mysqli_query($Conn, "SELECT Reviews.Review, COUNT(Dislikes.ReviewID) AS Count, Reviews.ReviewID, Reviews.UserID, Users.Username, Users.Status, Reviews.DateTime, Reviews.StarRating FROM Reviews LEFT JOIN Users ON Users.UserID = Reviews.UserID LEFT JOIN Dislikes ON Dislikes.ReviewID = Reviews.ReviewID WHERE Reviews.LocationID = '$BuildingID' AND Reviews.Status = 'Published' GROUP BY ReviewID ORDER BY Count DESC");
		} elseif($_GET["SortBy"] == "Top"){
			$GetReviews = mysqli_query($Conn, "SELECT Reviews.Review, COUNT(Likes.ReviewID) AS Count, Reviews.ReviewID, Reviews.UserID, Users.Username, Users.Status, Reviews.DateTime, Reviews.StarRating FROM Reviews LEFT JOIN Users ON Users.UserID = Reviews.UserID LEFT JOIN Likes ON Likes.ReviewID = Reviews.ReviewID WHERE Reviews.LocationID = '$BuildingID' AND Reviews.Status = 'Published' GROUP BY ReviewID ORDER BY Count DESC;");
		} else {
			$GetReviews = mysqli_query($Conn, "SELECT Reviews.Review, Reviews.ReviewID, Reviews.UserID, Users.Username, Users.Status, Reviews.DateTime, Reviews.StarRating FROM Reviews INNER JOIN Users ON Users.UserID = Reviews.UserID WHERE Reviews.LocationID = '$BuildingID' AND Reviews.Status = 'Published' ORDER BY Reviews.DateTime DESC");
		}
	} else {
		// Default SQL Query When The User Hasn't Sorted Reviews By A Custom Type
		$GetReviews = mysqli_query($Conn, "SELECT Reviews.Review, Reviews.ReviewID, Reviews.UserID, Users.Username, Users.Status, Reviews.DateTime, Reviews.StarRating FROM Reviews INNER JOIN Users ON Users.UserID = Reviews.UserID WHERE Reviews.LocationID = '$BuildingID' AND Reviews.Status = 'Published' ORDER BY Reviews.DateTime DESC");
	}	
}

// Checks If The User Has Submitted A New Review 
if(isset($_POST["AddReview"])){

	// Checks If The Review Is Not Empty
	if(empty($_POST["UserReview"])){
		array_push($ReviewErrorArray, "Please Enter A Review");
		$ReviewError = 1;
	} else {
		$Review = CleanInput($_POST["UserReview"]);
		// API That Checks The Review For Any Abusive Words
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.apilayer.com/bad_words?censor_character=*",
		  CURLOPT_HTTPHEADER => array(
		    "Content-Type: text/plain",
		    "apikey: ev26kIhnZeoFP7KooODJ5hNOjORvYUmd"
		  ),
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>"{$Review}"
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$apiResult = explode(",", $response);
		$badWordResult = explode(":", $apiResult[1]);
		$badWordCount = (int)$badWordResult[1];
		if($badWordCount > 0){
			array_push($ReviewErrorArray, "Please Avoid Using Abusive Language");
			$ReviewError = 1;
		} 
	}
	
	// Removes Any Duplicate Entries From The Array
	$ReviewErrorArray = array_unique($ReviewErrorArray);

	// Checks If The Rating Is Not Empty
	if(!empty($_POST["UserRating"])){
		$Rating = $_POST["UserRating"];
	}

	// Checks If There Are Any Errors In The Reviews
	if(count($ReviewErrorArray) == 0){
		$Username = $_SESSION['Username'];
		// Searches For The ID Of The Logged In User From The Database
		$GetUserDetails = mysqli_query($Conn, "SELECT UserID FROM Users WHERE Username = '$Username' LIMIT 1");
		while($Rows = mysqli_fetch_assoc($GetUserDetails)){
			$UserID = $Rows["UserID"];
		}
		// Searches The Database For Any Duplicate Reviews From The Logged In User
		$DuplicateReviews = mysqli_query($Conn, "SELECT * FROM Reviews INNER JOIN Users ON Users.UserID = Reviews.UserID WHERE Reviews.Review = '$Review'");
		// Checks If The Review Is A Duplciate From The User
		if(mysqli_num_rows($DuplicateReviews) > 0){
			while($Rows = mysqli_fetch_assoc($DuplicateReviews)){
				if($Rows["Username"] == $_SESSION["Username"]){
					$DBReview = strtolower($Rows["Review"]);
					if($DBReview == strtolower($Review)){
						array_push($ReviewErrorArray, "You Have Already Submitted This Review");
						$ReviewError = 1;
					} 
				} 
			}
		} 
	}

	// Inserts The Review Into The Database Table If There Aren't Any Errors In Thre Review
	if($ReviewError == 0){
		mysqli_query($Conn, "INSERT INTO `Reviews`(`LocationID`, `UserID`, `Review`, `StarRating`, `DateTime`, `Status`) VALUES ('$BuildingID','$UserID','$Review','$Rating','$CurrentTime','Published')");
		header("Location: Reviews.php");
	}
	
}

// Checks If The User Has Liked A Review
if(isset($_POST['Like'])){
	$Key = $_POST['Key'];
	$Like = $_POST['Review'][$Key];
	$likeDetails  = explode(",", $_POST['Review'][$Key]);
	$ReviewDetails  = str_split($likeDetails[0], 9);
	$ReviewDetails = $ReviewDetails[1];
	$UserDetails  = str_split($likeDetails[1], 8);
	$UserDetails = $UserDetails[1];
	// Searches If The User Has Previously Liked This Review
	$SearchForExisitingLike = mysqli_query($Conn, "SELECT * FROM Likes WHERE ReviewID = '$ReviewDetails' AND UserID = '$UserDetails' LIMIT 1");
	// Searches If The User Has Previously Disliked This Review
	$SearchForExisitingDislike = mysqli_query($Conn, "SELECT * FROM Dislikes WHERE ReviewID = '$ReviewDetails' AND UserID = '$UserDetails' LIMIT 1");
	// Checks If The Review Is Already Liked By This User
	if(mysqli_num_rows($SearchForExisitingLike) > 0){ 
		echo "<style>.Buttons .Like[id='$Like'] i{ color: #ADB8C2 !important }</style>";
		while($Like = mysqli_fetch_assoc($SearchForExisitingLike)){
			$LikeID = $Like["LikeID"];
			// Deletes The Like If They Have Clicked Like On A Review They Have Already Liked 
			mysqli_query($Conn, "DELETE FROM Likes WHERE LikeID = '$LikeID'");
		}
	} else {
		// Checks If The Review Is Disliked By This User
		if(mysqli_num_rows($SearchForExisitingDislike) > 0){
			while($Dislike = mysqli_fetch_assoc($SearchForExisitingDislike)){
				$DislikeID = $Dislike["DislikeID"];
				// Deletes The Dislike If They Have Clicked Like On A Review They Have Disliked 
				mysqli_query($Conn, "DELETE FROM Dislikes WHERE DislikeID = '$DislikeID'");
				echo "<style>.Buttons .Dislike[id='$Like'] i{ color: #ADB8C2 !important }</style>";
				echo "<style>.Buttons .Like[id='$Like'] i{ color: #04AA6D !important }</style>";
				// Inserts A Like For That Review From That User
				mysqli_query($Conn, "INSERT INTO `Likes`(`ReviewID`, `UserID`) VALUES ('$ReviewDetails','$UserDetails')");
			}
		} else {
			echo "<style>.Buttons .Like[id='$Like'] i{ color: #04AA6D !important }</style>";
			// Inserts A Like For That Review From That User
			mysqli_query($Conn, "INSERT INTO `Likes`(`ReviewID`, `UserID`) VALUES ('$ReviewDetails','$UserDetails')");
		}	
	}
	header("Location: Reviews.php");
}

// Checks If The User Has Flagged A Review
if(isset($_POST['Flag'])){
	$Key = $_POST['Key'];
	$Like = $_POST['Review'][$Key];
	$likeDetails  = explode(",", $_POST['Review'][$Key]);
	$ReviewDetails  = str_split($likeDetails[0], 9);
	$ReviewDetails = $ReviewDetails[1];
	// Updates The Reviews Status To Flagged In The Database
	mysqli_query($Conn, "UPDATE Reviews SET Status = 'Flagged' WHERE ReviewID = '$ReviewDetails'");
	$_SESSION["Reported"] = True;
	header("Location: Reviews.php");
}

// Checks If The User Has Disliked A Review
if(isset($_POST['Dislike'])){
	$Key = $_POST['Key'];
	$Dislike = $_POST['Review'][$Key];
	$likeDetails  = explode(",", $_POST['Review'][$Key]);
	$ReviewDetails  = str_split($likeDetails[0], 9);
	$ReviewDetails = $ReviewDetails[1];
	$UserDetails  = str_split($likeDetails[1], 8);
	$UserDetails = $UserDetails[1];
	// Searches If The User Has Previously Liked This Review
	$SearchForExisitingLike = mysqli_query($Conn, "SELECT * FROM Likes WHERE ReviewID = '$ReviewDetails' AND UserID = '$UserDetails' LIMIT 1");
	// Searches If The User Has Previously Disliked This Review
	$SearchForExisitingDislike = mysqli_query($Conn, "SELECT * FROM Dislikes WHERE ReviewID = '$ReviewDetails' AND UserID = '$UserDetails' LIMIT 1");
	// Checks If The Review Is Already Disliked By This User
	if(mysqli_num_rows($SearchForExisitingDislike) > 0){ 
		echo "<style>.Buttons .Dislike[id='$Dislike'] i{ color: #ADB8C2 !important }</style>";
		while($Dislike = mysqli_fetch_assoc($SearchForExisitingDislike)){
			$DislikeID = $Dislike["DislikeID"];
			// Deletes The Dislike If They Have Clicked Dislike On A Review They Have Already Disliked 
			mysqli_query($Conn, "DELETE FROM Dislikes WHERE DislikeID = '$DislikeID'");
		}
	} else {
		if(mysqli_num_rows($SearchForExisitingLike) > 0){
			echo "<style>.Buttons .Like[id='$Dislike'] i{ color: #ADB8C2 !important }</style>";
			while($Like = mysqli_fetch_assoc($SearchForExisitingLike)){
				$LikeID = $Like["LikeID"];
				// Deletes The Like If They Have Clicked Dislike On A Review They Have Liked 
				mysqli_query($Conn, "DELETE FROM Likes WHERE LikeID = '$LikeID'");
				echo "<style>.Buttons .Dislike[id='$Dislike'] i{ color: #F44336 !important }</style>";
				// Inserts A Dislike For That Review From That User
				mysqli_query($Conn, "INSERT INTO `Dislikes`(`ReviewID`, `UserID`) VALUES ('$ReviewDetails','$UserDetails')");
			}
		} else {
			echo "<style>.Buttons .Dislike[id='$Dislike'] i{ color: #F44336 !important }</style>";
			// Inserts A Dislike For That Review From That User
			mysqli_query($Conn, "INSERT INTO `Dislikes`(`ReviewID`, `UserID`) VALUES ('$ReviewDetails','$UserDetails')");
		}
	}
	header("Location: Reviews.php");
}

?>