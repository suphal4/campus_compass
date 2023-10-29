<?php
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}

	// Imports The PHPMailer External Library
	use PHPMailer\PHPMailer\PHPMailer;

	function notifyUser($userID,$notification,$detail)
	{
		if ($userID != -1)
		{
			$row = getUserEmail($userID);//Function will get username and email to send information to the user
			$email = $row['Email'];
			// $username = $row['Username'];

			if ($email != -1)
			{
				// Created A HTML Message With Subject Sent To The Users' University Email. Message & subject will be different based on what we want to send

				if ($notification == 'RequestSent')//Depending on the notification different messages will be sent to the user
				{
					$Message = "Your request to add the location called '". $detail . "' has been sent! This will be reviewed by our admins and may be added to the Campus Compass map.";
					$Subject = "Campus Compass - Request Sent";
				}
				else if ($notification == 'RequestAccepted')
				{
					$Message = "Your recent request to add the location called '" . $detail ."' has been approved! It has now been added to the Campus Compass map!";
					$Subject = "Campus Compass - Request Approved";

				}
				else if ($notification == 'RequestDenied')
				{
					$Message = "Your recent request to add the location called '" . $detail ."' has been denied!";
					$Subject = "Campus Compass - Request Denied";

				}
				else if ($notification == "ReviewDeleted")
				{
					$Message = "Your recent review: '".$detail."' has been deleted from Campus Compass! This is because someone flagged it and our Admins found it was not appropriate. Please be careful when leaving reviews in the future!";
					$Subject = "Campus Compass - Review Removed";
				}
				$Message = "<html>
								<h1 style ='font-size:30px;text-align: center; margin:auto; letter-spacing:1.5px;'>Campus Compass</h1>
								<img style =  margin-left:45%; width:10%; height:10%; text-align: center; display:block;' alt='Logo' title='Logo' src = 'https://web.cs.manchester.ac.uk/h61781jp/FirstYearTeamProject/Symbol3.png'/><br>
								<h3 style='font-size:15px; margin:auto;color: gray; letter-spacing:1.5px;'>$Message</h3>
							</html>";

				// Gets The Required PHP Scripts From The PHPMailer Folder 
				require_once "PHPMailer/PHPMailer.php";
				require_once "PHPMailer/SMTP.php";
				require_once "PHPMailer/Exception.php";

				// Creates A New Email
				$Mail = new PHPMailer();
				$Mail->isSMTP();
				$Mail->Host = "smtp.gmail.com";
				$Mail->SMTPAuth = true;
				// Created A Email For Our Project From Which The Email Is Sent To The Users' University Email
				$Mail->Username = "uom.y18@gmail.com";
				$Mail->Password = "CampusCompass1!";
				$Mail->Port = 465;
				$Mail->SMTPSecure = "ssl";
				$Mail->From = "noreply@campuscompass.com";
				$Mail->FromName = "Campus Compass";
				$Mail->setFrom($email);
				$Mail->addAddress($email);
				$Mail->Subject = $Subject;
				$Mail->Body = ($Message);
				$Mail->isHTML(true);
				if($Mail->send()){
					// Sets Session Variables If The Mail Is Successfully Sent
					$_SESSION['EmailSent'] = 1;
					$_SESSION['Attempts'] = 3;
				} else {
					// Sets Session Variables If The Mail Is Not Sent
					$_SESSION['EmailSent'] = 0;
				}
			}
		}
	}

	function getUserEmail($userID)//Simple sql to get the usrname and email from the database
	{
		$result = mysqli_query($GLOBALS['Conn'],"SELECT Username,Email FROM Users WHERE UserID = '$userID'");
		if (mysqli_num_rows($result) > 0) //Checks whether there are any results, returns these results
		{
			$row = mysqli_fetch_assoc($result);
			return $row;

		}
		else
		{
			return -1;
		}
	}

  ?>
