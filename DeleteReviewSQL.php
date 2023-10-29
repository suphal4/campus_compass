<?php
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}
	include("NotifyUserRequest.php");//Includes the function to email the user
	$result = mysqli_query($GLOBALS['Conn'], "SELECT ReviewID,UserID,Review FROM Reviews WHERE Status = 'Flagged'");//Gets the review that is flagged
	if (mysqli_num_rows($result) > 0) //Checks whether there are any results, will then set the review id based off of this
	{
		$row = mysqli_fetch_assoc($result);
		$review = $row["Review"];
		$reviewID = $row["ReviewID"];//Gets the id of the review we are approving
		$userID = $row["UserID"];//Gets the user id to email them about their review being deleted.
		$result = mysqli_query($Conn,"DELETE FROM Reviews WHERE ReviewID = '$reviewID'");
		notifyUser($userID,"ReviewDeleted",$review);
		//if row successfully deleted from review table return to ApproveReviews page, else echo an error
		if ($result)
		{
			header('location:ApproveReviews.php');
		}
		else
		{
			echo "Error: ".mysqli_error($Conn);
		}	
	}
	else
	{
		header('location:ApproveReviews.php');//Does nothing on button click with no flagged reviews
	}
?>