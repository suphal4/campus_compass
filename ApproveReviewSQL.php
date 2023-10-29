<?php
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}
	$result = mysqli_query($GLOBALS['Conn'], "SELECT ReviewID FROM Reviews WHERE Status = 'Flagged'");//Gets the review that is flagged
	if (mysqli_num_rows($result) > 0) //Checks whether there are any results, will then set the review id based off of this
	{
		$row = mysqli_fetch_assoc($result);
		$reviewID = $row["ReviewID"];//Gets the id of the review we are approving
		$result = mysqli_query($Conn,"UPDATE Reviews SET Status = 'Published' WHERE ReviewID = '$reviewID'");//Will change the value of the column so that the comment is no longer flagged
		//if row successfully deleted from Request table return to ApproveReviews page, else echo an error
		if ($result)
			header('location:ApproveReviews.php');
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
