<?php

function Comments($ReviewID){
	$Conn = new mysqli('dbhost.cs.man.ac.uk','m42552rh','Alihasan_1','2021_comp10120_y18');
	$GetComments = mysqli_query($Conn, "SELECT Comments.CommentID, Comments.Comment, Users.Username, Users.Status FROM Comments INNER JOIN Users ON Users.UserID = Comments.UserID WHERE Comments.ReviewID = '$ReviewID'");
	if(mysqli_num_rows($GetComments) > 0){
		echo '<div class="CommentContainer" id="CommentContainer">';
		while($Row = mysqli_fetch_assoc($GetComments)){
			if($Row["Status"] == "Activated"){
					echo '<div class="Comments">
							<p>' . $Row["Username"] . '</p><p class="theComment">' . $Row["Comment"] . '</p>
						  </div>';
			} else {
					echo '<div class="Comments">
							<p style="color:#F44336;">' . $Row["Username"] . ' · This Account Has Been Reported</p>' . $Row["Comment"] . '</p>
						  </div>';
			}
		}
		echo '</div>';
		return true;
	} else {
		echo '<div class="CommentContainer" id="CommentContainer"></div>';
		return false;
	}
}

?>