<?php
	include('NotifyUserRequest.php');//Includes function to email user updates abut the request
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}
//Refer to delete.php for more meaningful comments
$result = mysqli_query($Conn,"SELECT Name,Longitude,Latitude,Description,RequestID,Image,UserID FROM Request WHERE RequestID = (SELECT MIN(RequestID) FROM Request)");
$res = mysqli_fetch_assoc($result);
$id = $res["RequestID"];
$name = $res["Name"];
$longitude = $res["Longitude"];
$latitude = $res["Latitude"];
$description = $res["Description"];
$image = $res['Image'];
$userid = $res['UserID'];
notifyUser($userid,"RequestAccepted",$name);//Will email the user that their request has been aproved
$result = $Conn->prepare("INSERT INTO Location (Name,Longitude,Latitude,Description,Image) VALUES (?,?,?,?,?)");
if($result){
		$result->bind_param('sddss',$name,$longitude,$latitude,$description,$image);
		$result->execute();
}

$result = mysqli_query($Conn,"DELETE FROM Request WHERE RequestID = '$id'");

if ($result)
	header('location:ApproveRequest.php');
else{
	echo "Error: ".mysqli_error($db);
}
?>
