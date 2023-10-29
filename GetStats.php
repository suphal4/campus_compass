<?php
$Conn = new mysqli('dbhost.cs.man.ac.uk','m42552rh','Alihasan_1','2021_comp10120_y18');
function MostLikedReviews(){
	$Num = 1;
	global $Conn;
	$getMostLikedReviews = mysqli_query($Conn, "SELECT COUNT(*) As CNT, Reviews.Review, Reviews.LocationID, Reviews.ReviewID FROM Likes INNER JOIN Reviews ON Reviews.ReviewID = Likes.ReviewID GROUP BY Likes.ReviewID ORDER BY CNT DESC LIMIT 5");
	if(mysqli_num_rows($getMostLikedReviews) > 0){
		while($Result = mysqli_fetch_assoc($getMostLikedReviews)){
			echo '<div class=MLReviews><p class=Num>'.$Num.'</p>';
			$Review = $Result["ReviewID"];
			$Location = $Result["LocationID"];
			$getLocation = mysqli_query($Conn, "SELECT Name FROM Location WHERE LocationID = '$Location' LIMIT 1");
			$getUsername = mysqli_query($Conn, "SELECT Users.Username FROM Users INNER JOIN Reviews ON Reviews.UserID = Users.UserID WHERE ReviewID = '$Review'");
			while($Result2 = mysqli_fetch_assoc($getUsername)){
				echo '<p class=Name>'. $Result2["Username"] .'</p><br>';
				
			}
			while($Result3 = mysqli_fetch_assoc($getLocation)){
				echo '<p class=Review>'.$Result["Review"].'<em> ~ '.$Result3["Name"].'</em></p></div>';
			}
			$Num += 1;
		}
	}
}

function MyReviews(){
	global $Conn;
	$Username = $_SESSION['Username'];
	$MyReviews = mysqli_query($Conn, "SELECT Reviews.Review, Reviews.LocationID FROM Reviews INNER JOIN Users ON Users.UserID = Reviews.UserID WHERE Users.Username = '$Username'");
	if(mysqli_num_rows($MyReviews) > 0){
		while($Result = mysqli_fetch_assoc($MyReviews)){
			echo '<div class=myReview>';
			$Location = $Result["LocationID"];
			$getLocation = mysqli_query($Conn, "SELECT Name FROM Location WHERE LocationID = '$Location' LIMIT 1");
			while($Result2 = mysqli_fetch_assoc($getLocation)){
				echo '<p class=Review>'.$Result["Review"].'<em> ~ '.$Result2["Name"].'</em></p></div>';
			}
		}
	}
}

function Likes(){
	global $Conn;
	$Username = $_SESSION['Username'];
	$getUserID = mysqli_query($Conn, "SELECT UserID FROM Users WHERE Username = '$Username' LIMIT 1");
	while($Result = mysqli_fetch_assoc($getUserID)){
		$userID = $Result["UserID"];
	}
	$getLikeCount = mysqli_query($Conn, "SELECT COUNT(Likes.ReviewID) AS CNT FROM Likes INNER JOIN Reviews ON Reviews.ReviewID = Likes.ReviewID WHERE Reviews.UserID = '$userID'");
	while($Result = mysqli_fetch_assoc($getLikeCount)){
		$likeCount = $Result["CNT"];
	}
	echo '<p class=likeCount>'.$likeCount.'</p>';
}

function Dislikes(){
	global $Conn;
	$Username = $_SESSION['Username'];
	$getUserID = mysqli_query($Conn, "SELECT UserID FROM Users WHERE Username = '$Username' LIMIT 1");
	while($Result = mysqli_fetch_assoc($getUserID)){
		$userID = $Result["UserID"];
	}
	$getDislikeCount = mysqli_query($Conn, "SELECT COUNT(Dislikes.ReviewID) AS CNT FROM Dislikes INNER JOIN Reviews ON Reviews.ReviewID = Dislikes.ReviewID WHERE Reviews.UserID = '$userID'");
	while($Result = mysqli_fetch_assoc($getDislikeCount)){
		$dislikeCount = $Result["CNT"];
	}
	echo '<p class=dislikeCount>'.$dislikeCount.'</p>';
}

function Reviews(){
	global $Conn;
	$Username = $_SESSION['Username'];
	$getUserID = mysqli_query($Conn, "SELECT UserID FROM Users WHERE Username = '$Username' LIMIT 1");
	while($Result = mysqli_fetch_assoc($getUserID)){
		$userID = $Result["UserID"];
	}
	$getReviewCount = mysqli_query($Conn, "SELECT COUNT(Reviews.ReviewID) AS CNT FROM Reviews WHERE Reviews.UserID = '$userID'");
	while($Result = mysqli_fetch_assoc($getReviewCount)){
		$reviewCount = $Result["CNT"];
	}
	echo '<p class=reviewCount>'.$reviewCount.'</p>';
}

?>