<?php

// Includes The MySQlI Connection Page To This PHP Page To Access The Database 
include("ConnectionToMySQL.php");

// Checks If The Building Form Has Been Submitted Through jQuery As The User Types (Check MyAccount.php)
if(isset($_POST["Building"])){
	// Searches For The Building In The Database
	$GetBuildings = mysqli_query($Conn, "SELECT * FROM Location WHERE Name LIKE '%".$_POST["Building"]."%'");
	$Results = '<ul class="DropDown" id="Results">';
	// Checks If The Database Search Produced A Non-Empty Result
	if(mysqli_num_rows($GetBuildings) > 0){
		// Loops Through The Result Of The Query And Concatenates It To The Variable $Results
		while($Rows = mysqli_fetch_assoc($GetBuildings)){
		    $Results .= '<li id="University">'.$Rows["Name"].'</li>';
		}
	} else {
		// Concatenates An Alternative Message If The Building Inputted Doesn't Exist
		$Results .= '<li>Building Not Found</li>';
	}
	$Results .= '</ul>';
	echo $Results;
}

?>