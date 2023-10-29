<?php
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}

	$result = mysqli_query($GLOBALS['Conn'], "SELECT Review, ReviewID FROM Reviews WHERE Status = 'Flagged'");//Selects a flagged review
	$numRows = mysqli_num_rows($result);
	if ($numRows > 0) //Checks whether there are any results, returns these results
	{
		$row = mysqli_fetch_assoc($result);//Uses the top most result, so the oldest flagged review
		displayCommment($row["Review"],$numRows);

	}
	else
	{
		displayCommment();
	}


	function displayCommment($comment = " ",$numRows = 0)//This sets up all the input details. It will try and fill them in from the comment from the table
	{	
		$wordNum = "are ";
		$sLetter = "s";
		if ($numRows == 1)
		{
			$wordNum = "is ";
			$sLetter = "";
		}
		$title = "There " . $wordNum . $numRows ." new Flagged Review" . $sLetter;
		echo('
								<div>
									<h1>'.$title.'</h1><hr>
								</div>
								<div>
									<h2>Flagged Comment: "'.$comment.'"</h2>
								</div>


			');
	}
 ?>