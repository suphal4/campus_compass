<?php
if (!isset($Conn)) {
	//Makes database connecrtion if there isn't one
	include("ConnectionToMySQL.php");
}
if (session_status() != PHP_SESSION_ACTIVE) {
	session_start();
}


$GLOBALS['Errors'] = array(); //Array in whcih we will keep track of all errors that can occur on this page
$GLOBALS['Messages'] = array(); //Records all mesages to output to the user

function requestNewLocation()
{
	$name = $_POST['name'];
	$longitude = $_POST['longitude'];
	$latitude = $_POST['latitude'];
	$description = $_POST['description'];
	$image = getImage();

	if (isset($_POST['contactUser'])) //if the user ticks the checkbox to be notified, only then is their id saved with the request, to email them with updates
	{
		$userid = getUserID(); //Sets user id using username from account page
	} else {
		$userid = -1;
	}

	//Validation for all the different fields
	//Name and coordinate presence checks
	if (empty($name)) {
		array_push($GLOBALS['Errors'], "Please enter the name of the location");
	}
	if (empty($longitude) && empty($latitude)) {
		array_push($GLOBALS['Errors'], "Please click on the map to select a location for your request");
	}
	else
	{
		//Checks to ensure that the coordinates are purely numerical
		if ((!is_numeric($longitude)) | (!is_numeric($latitude))) {
			array_push($GLOBALS['Errors'], "Please ensure that the coordinates entered are numeric values");
		}
		else
		{
			//Bounds checks for the coordinates for the map
			if ((-2.2525 > $longitude) || ($longitude > -2.2100) || (53.4450 > $latitude) || ($latitude > 53.4850)) {
				array_push($GLOBALS['Errors'], "Coordinates are not on the University of Manchester Campus! Please review these.");
			}	
		}
	}
	//Non uniqye location names and coordinates are filtered out
	if (!UniqueName($name)) {
		array_push($GLOBALS['Errors'], "There already exists a request or location with this name");
	}

	if (!UniqueLocation($longitude, $latitude)) {
		array_push($GLOBALS['Errors'], "There already exists a request or location at these coordinates");
	}
	//Length checks for each different field
	if (strlen($name) > 50) {
		array_push($GLOBALS['Errors'], "Location name is too long!");
	}

	if (strlen($longitude) > 100) {
		array_push($GLOBALS['Errors'], "Location longitude is too long!");
	}

	if (strlen($latitude) > 100) {
		array_push($GLOBALS['Errors'], "Location latitude is too long!");
	}

	if (strlen($description) > 100) {
		array_push($GLOBALS['Errors'], "Location description is too long!");
	}



	if (checkForProfanity($name) && checkForProfanity($longitude) && checkForProfanity($latitude) && checkForProfanity($description)) //Checks for swear words in each input field
	{
		if (count($GLOBALS['Errors']) == 0) //Only if there have been no errors so far is the request actually sent to the database
		{
			$result = $GLOBALS['Conn']->prepare("INSERT INTO Request (Name,Longitude,Latitude,Description,UserID,Image) VALUES (?,?,?,?,?,?)");
			if ($result) {
				$result->bind_param('sddsds', $name, $longitude, $latitude, $description, $userid, $image);
				$result->execute();
			} else {
				array_push($GLOBALS['Errors'], "Connection to database error");
			}


			if ($result) {
				notifyUser($userid, "RequestSent", $name); //Will email the user that their request has been sent off
				array_push($GLOBALS['Messages'], "Your Request has been sent!");
			} else {
				array_push($GLOBALS['Errors'], "Database error! Please try again!");
			}
		}
	} else {
		array_push($GLOBALS['Errors'], "Please refrain from using profrane language!");
	}
}

function getUserID() //Get uid from username
{
	$username = $_SESSION["Username"]; //Gets the username from the login session
	if (isset($username)) {
		$result = mysqli_query($GLOBALS['Conn'], "SELECT UserID FROM Users WHERE Username = '$username'"); //Queries database, gets user id from the username in the previous page

		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$userid = $row['UserID']; //Fetches the userid from the results
		}
		return $userid;
	} else {
		return null;
	}
}
function getImage()
{
	$img_name = $_FILES['imageUpload']['name'];
	// $img_type = $_FILES['imageUpload']['type'];
	$tmp_name = $_FILES['imageUpload']['tmp_name'];
	$error = $_FILES['imageUpload']['error'];
	$img_size = $_FILES['imageUpload']['size'];
	if (!is_uploaded_file($tmp_name)) {
		array_push($GLOBALS['Errors'], "No image has been uploaded");
	} else if ($error !== 0) {
		array_push($GLOBALS['Errors'], "Unknown error! Please try again!");
	} else {
		if ($img_size > 12500000) {
			array_push($GLOBALS['Errors'], "Image file is too large! Please try again with a smaller image");
		} else {
			$img_ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));
			$allowed_ext = array('jpg', 'png', 'jpeg');
			if (!in_array($img_ext, $allowed_ext)) {
				array_push($GLOBALS['Errors'], "Allowed extensions are png, jpg and jpeg!");
			} else {
				return file_get_contents($tmp_name);
			}
		}
	}
}

if (empty($_POST) == False) {
	echo (requestNewLocation());

	//Then checks all possible errors & displays them on the screen
	if (count($GLOBALS['Errors']) > 0) {
		echo ('

					<div class="ErrorBox">
						<div class="ErrorMessage">');
		foreach ($GLOBALS['Errors'] as $Error) {
			echo ("<div style='margin:6.25px 0'>$Error</div>");
		}
		echo ('		</div>
					</div>
					');
	}

	//Displays messages on the screen such as if the request was successfully sumbitted
	if (count($GLOBALS['Messages']) > 0) {
		echo ('

					<div class="LocationMessageBox">
						<div class="LocationMessage">');
		foreach ($GLOBALS['Messages'] as $Message) {
			echo ("<div style='margin:6.25px 0'>$Message</div>");
		}
		echo ('		</div>
					</div>
					');
	}
}

function UniqueName($value) //Will query the location & request tables and return whether or not there are any records with the same name
{
	$unique = true;
	$result = mysqli_query($GLOBALS['Conn'], "SELECT LocationID FROM Location WHERE Name = '$value'");

	if ($result) {
		if (mysqli_num_rows($result) > 0) {
			$unique = false;
		}

		if ($unique) {
			$result = mysqli_query($GLOBALS['Conn'], "SELECT RequestID FROM Request WHERE Name = '$value'");
			echo (mysqli_error($GLOBALS['Conn']));
			if (mysqli_num_rows($result) > 0) {
				$unique = false;
			}
		}
	} else {
		array_push($GLOBALS['Errors'], "An error has occured! Please ensure that all fields contain just letters & numbers.");
	}
	return $unique;
}

function UniqueLocation($longitude, $latitude) //Will query the location & request tables and return whether or not there are any records with the same coordinates
{
	$unique = true;
	$result = mysqli_query($GLOBALS['Conn'], "SELECT LocationID FROM Location WHERE Longitude = '$longitude' AND  Latitude = '$latitude'"); //Checks both latitude & longitude

	if ($result) //Error handling incase the sql statement doesn't work
	{
		if (mysqli_num_rows($result) > 0) {
			$unique = false;
		}

		if ($unique) {
			$result = mysqli_query($GLOBALS['Conn'], "SELECT RequestID FROM Request WHERE Longitude = '$longitude' AND  Latitude = '$latitude'");
			if (mysqli_num_rows($result) > 0) {
				$unique = false;
			}
		}
	} else {
		array_push($GLOBALS['Errors'], "An error has occured! Please ensure that all fields contain just letters & numbers.");
	}
	return $unique;
}

?>
<!-- Map stuff for this page is below -->
<script>
	var marker; //MAkes a global variable for the marker so that its position can be changed elsewhere
	// Initialize and add the map
	function initMap() {
		const uom = {
			lat: 53.4668,
			lng: -2.2339
		};

		var mapTypeStylesArray = [{
				featureType: 'water',
				elementType: 'labels.text', //Looks at element of the feature given
				stylers: [{
					color: '#FF0000' //Styles text of label of water to red
				}]
			},
			{
				featureType: "poi.school",
				elementType: "geometry.fill",
				stylers: [{
					color: "#202124"
				}]
			},
			{
				featureType: "road",
				elementType: "labels",
				stylers: [{
					visibility: "off"
				}]
			},
			{
				featureType: "administrative",
				elementType: "all",
				stylers: [{
					visibility: "off"
				}]
			},
			{
				featureType: 'poi',
				elementType: "labels",
				stylers: [{
					saturation: "-40"
				}]
			}, {
				stylers: [{
					hue: "#242526"
				}, {
					saturation: "-80"
				}]
			},
		];

		const Map_Boundaries = {
			north: 53.4850,
			south: 53.4450,
			west: -2.2525,
			east: -2.2100,
		}

		let mapOptions = {
			center: uom,
			zoom: 17,
			minZoom: 10,
			maxZoom: 20,
			draggableCursor: 'pointer',
			mapTypeControlOptions: {
				mapTypeIds: ['roadmap', 'hybrid', ]
			},
			styles: mapTypeStylesArray,
			restriction: {
				latLngBounds: Map_Boundaries,
				strictBounds: true,
			},
		}

		let map = new google.maps.Map(document.getElementById("map"), mapOptions);

		marker = new google.maps.Marker({
			position: uom
		});
		marker.setMap(map);

		// Configure the click listener to change the text coordinates on the screen to the position clicked
		map.addListener("click", (mapsMouseEvent) => {
			var jsonCoords = mapsMouseEvent.latLng.toJSON();

			document.getElementById("longitude").value = jsonCoords.lng; //Sets the coordinates clicked to fill in the two fields
			document.getElementById("latitude").value = jsonCoords.lat;

			marker.setPosition(jsonCoords);
		});

	}
</script>