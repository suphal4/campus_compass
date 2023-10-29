<?php 

$reviewID = null;
$errorPresent = null;
$commentError = array();
include_once("ConnectionToMySQL.php");

if(isset($_POST['addComment'])){

	$ReviewAndPositionDetails = explode("|", $_POST['addComment']);

	$reviewID = (int)$ReviewAndPositionDetails[0];

	if(!empty($_POST['UserComments'])){
		$userComment = CleanInput($_POST['UserComments']);
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
		  CURLOPT_POSTFIELDS =>"{$userComment}"
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$apiResult = explode(",", $response);
		$badWordResult = explode(":", $apiResult[1]);
		$badWordCount = (int)$badWordResult[1];
		if($badWordCount > 0){
			array_push($commentError, "Please Avoid Using Abusive Language");
			$errorPresent = $ReviewAndPositionDetails[1];
		}
	} else {
		array_push($commentError, "Please Enter A Comment");
		$errorPresent = $ReviewAndPositionDetails[1];
	}

	if(count($commentError) == 0){
		$userName = $_SESSION['Username'];
		$getUserID = mysqli_query($Conn, "SELECT UserID FROM Users WHERE Username = '$userName' LIMIT 1");
		while($Row = mysqli_fetch_assoc($getUserID)){
			$userID = $Row["UserID"];
		}
		if(mysqli_query($Conn, "INSERT INTO `Comments`(`Comment`, `ReviewID`, `UserID`) VALUES ('$userComment','$reviewID','$userID')")){
			header("Location: Reviews.php");
		} else {
			array_push($commentError, "Failed To Add Comment. Please Try Again Later");
			$errorPresent = $ReviewAndPositionDetails[1];
		}
	}

}

?>