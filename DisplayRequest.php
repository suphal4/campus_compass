<?php
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}

	$result = mysqli_query($GLOBALS['Conn'], "SELECT Name,Longitude,Latitude,Description,RequestID,Image FROM Request WHERE RequestID = (SELECT MIN(RequestID) FROM Request)");//Selects the request with the minimum id, this will be oldest request

	if (mysqli_num_rows($result) > 0) //Checks whether there are any results, returns these results
	{
		$row = mysqli_fetch_assoc($result);
		displayRequest($row["Name"],($row["Longitude"] . ",". $row["Latitude"]),$row["Description"], $row["Image"]);

	}
	else
	{
		displayRequest();
	}


	function displayRequest($name = "No New Requests",$location = " ", $description = " ", $img = "")//This sets up all the input details. It will try and fill them in from the request from the table
	{
		if($img == "")
			$img = file_get_contents("Symbol2.png");
		echo('
								<div>
									<h1>'.$name.'</h1><hr>
								</div>
								<div>
									<img id = "imageDisplay" src = "data:image/png;base64,'.base64_encode($img).'"></img>
								</div>
								
								<div>
									<h5 id = "name">Location:' . $location . '</h5>
								</div>	
								<div>
									<h3>Description:' . $description . '</h3>
								</div>


			');
	}
 ?>