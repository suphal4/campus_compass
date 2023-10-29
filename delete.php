<?php
	include('NotifyUserRequest.php');//Includes function to email user updates abut the request
	if (!isset($Conn))
	{
		//Makes database connecrtion if there isn't one
		include("ConnectionToMySQL.php");
	}
$result = mysqli_query($Conn,"SELECT RequestID,Image,UserID,Name  FROM Request WHERE RequestID = (SELECT MIN(RequestID) FROM Request)");
//fetches the requestID and deletes row that matches that id
$res = mysqli_fetch_assoc($result);
$id = $res["RequestID"];
$imagePath = $res["Image"];
$userid = $res['UserID'];
$name = $res['Name'];
$result = mysqli_query($Conn,"DELETE FROM Request WHERE RequestID = '$id'");
//if row successfully deleted from Request table return to ApproveRequest page, else echo an error
if ($result)
	header('location:ApproveRequest.php');
else
	echo "Error: ".mysqli_error($db);
notifyUser($userid,"RequestDenied",$name);//Will email the user that their request has been denied

?>