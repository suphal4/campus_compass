<?php include("AccountNavBar.php");//Adds the navigation bar to the top of the page
include("CheckForProfanity.php");//Allow user to call function
include('NotifyUserRequest.php');//Includes function to email user updates abut the request
if (session_status() != PHP_SESSION_ACTIVE)
{
	session_start();
}
if(!isset($_SESSION["Username"])){//Only opens the page if the user is logged in, otherwise they are directed to the login page. Works as it checks if their username is set within the session. This session is from the location of Account.php the website in whcih they login
	header("Location: Account.php");}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Request New Location</title>
	<!-- Imports and uses the style sheet specified below -->
	<link rel="stylesheet" href="MyAccountStyleSheet.css"> 
</head>
<body>
	<div class = "AddLocation">
		<form method = "POST" enctype="multipart/form-data">
			<div class = "AddLocationHeader">
				<h3 id = "AddLocationHeading">Request New Location</h3>
				<hr>
				<?php include("AddLocationAuthentication.php");//Includes the code to request new location & to validate each of the fields and display errors. Included here so that the errors appear here on the screen ?>
			</div>
			<div class = "AddLocationLeft col">
				<div class = "HelpPopUp">
					<img id = "HelpPointer" src = "question mark.png"></img>
					<label id = "HelpInfo">Your uploaded image will be displayed here. Please note that it must be a .jpg, .png or .jpeg file and it cannot be greater than 12.5MB.</label>
				</div>

				<img id = "imageDisplay" src = "Symbol2.png"></img><br>
<!-- 		        Below there are two buttons. The first is the button that's displayed, when clicked this calls to click the other button. This is to allow customisation of the button

 -->				<input type="button" id="btnImageUpload" value="Upload Location Image" onclick="document.getElementById('imageUpload').click();" />
		        <input type="file" style="display:none;" id = "imageUpload" name="imageUpload" accept = "image/*" text = "Upload Location Image"><br>

			</div>
			<div class = "AddLocationMid col">
				<div class = "HelpPopUp">
					<img id = "HelpPointer" src = "question mark.png"></img>
					<label id = "HelpInfo">Click anywhere on the map to use that location for your request or use the button to use your current location. </label>
				</div>
				
				<div id="map"></div>
	    		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGvOaSMLj8ZDKf3q6bA7MXZf4wqHT8juI&callback=initMap&libraries=&v=weekly" async></script>
				<button onclick="getUsersCurrentLocation()" id = "btnGetLocation"type = "button">Get Current Location</button><br>
			</div>
			<div class = "AddLocationRight col">
				<div class = "HelpPopUp">
					<img id = "HelpPointer" src = "question mark.png"></img>
					<label id = "HelpInfo">Enter the name of your location here. To enter the coordinates click the map opposite.</label>
				</div>

		        <input type="text" placeholder="Name" id = "name" name="name"><br>
		        <input type="text" placeholder="Latitude" id = "latitude" name="latitude" readonly = true><br>
		        <input type="text" placeholder="Longitude" id = "longitude" name="longitude" readonly = true><br>
		        <input type = "text" placeholder="Description (Optional)" id = "description" name = "description"><br>
		        <input type="submit" value = "Request"><br>
		        <input type="checkbox"  id ="contactUser" name="contactUser" checked ><label id = "lbContactUser">I agree to be emailed about this request</label><br>
			</div>

		    </div>

	        <!-- Below is the javascript for the uploading of the image of the location, that way it is displayed on screen correctly -->
	        <script type="text/javascript">
	        	const imageDisplay = document.getElementById("imageDisplay");//Gets the component from the web page that actually displays the image
	        	const imageUpload = document.getElementById("imageUpload");//Gets the component from the web page that uplaods the image, so the file input field

	        	imageUpload.addEventListener("change",function(){//This adds an event listener to the image upload component, that way it will execute a function whenever it is changed, for example whenever a new image is uploaded
	        		getImgData();
	        	});

	        	function getImgData() {//This is the function that unpacks the image data from the uploaded file. This will display the iamge on the screen
				  const files = imageUpload.files[0];//Gets files from the component that has saved it 
				  if (files) {
				    const fileReader = new FileReader();//This creates a new filereader object
				    fileReader.readAsDataURL(files);//The file reader will read through the dataq within the file and will store it as a URL of the data. It stores this within the result attribute in the filereader.
				    fileReader.addEventListener("load", function () {//Adds an event listener so that the file reader will carry out the function below when data is loaded
				      imageDisplay.setAttribute("src",this.result);//This will edit the image display and instead display the selected image file
				    });    
				  }
				}

				function currentLocationError(err) {
					alert("There has been an error! Please review your GPS and try again.");
				}

				function getUsersCurrentLocation()
				{
					if (navigator.geolocation) 
					{
						// navigator.geolocation.getCurrentPosition(displayCoordinates);//Displays the users current lcoation, will request permission to do so too
						navigator.geolocation.getCurrentPosition(displayCoordinates,currentLocationError,{enableHighAccuracy:true});//Displays the users current lcoation, will request permission to do so too
					}
					
				}
				//Will display the coordinates passed in into the text boxes on the screen & will move the marker on the map to that position too
				function displayCoordinates(position) 
				{
					const longitude = position.coords.longitude;
					const latitude = position.coords.latitude;

					document.getElementById("longitude").value = longitude;
					document.getElementById("latitude").value = latitude;

			        const coords = {//Formats the coordinates to pass in to set the position of the marker
			            lat: latitude,
			            lng: longitude
			        };
		    		marker.setPosition(coords);
				}

	        </script>
		</form>
	</div>


</body>
</html>
<style>
	a[href="AddLocation.php"]{
		color: rgba(0, 136, 169, 1);
	}
	
	a[href="AddLocation.php"]:hover{
		color: rgba(0, 136, 169, 1) !important;
	}
</style>