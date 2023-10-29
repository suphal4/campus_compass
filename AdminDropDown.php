<?php
	if (!isset($Conn))
	{
		include("ConnectionToMySQL.php");
	}

	if (session_status() != PHP_SESSION_ACTIVE)
	{
		session_start();
	}

	if(!isset($_SESSION["Username"]))
	{
		header("Location: Account.php");
	}
	else
	{
		$un = $_SESSION["Username"];
		$result = mysqli_query($Conn, "SELECT Admin FROM Users WHERE Username = '$un'");
		if (mysqli_num_rows($result) > 0) 
		{
			$row = mysqli_fetch_assoc($result);
			$admin = $row["Admin"];
			if ($admin == 1)
			{//Ali, add link here to your page, by the Review comments section
				echo("
					<div class =  'NavBarDropDown'>
						<li class = 'DropDownPointer'>Admin</li>
						<div class = 'DropDownContent'>
							<li><a href='ApproveRequest.php'>Review·Requests</a></li><br>
							<li><a href='ApproveReviews.php'>Review·Comments</a></li><br>
						</div>
					</div>
					");
			}
		}
		else
		{
			echo("Error: No results found");
		}
	}
	
?>