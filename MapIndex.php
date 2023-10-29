<?php
session_start();
// if link clicked
if(isset($_POST["ReviewLink"])){
	unset($_SESSION["Building"]);
	$_SESSION["Building"] = $_POST["ReviewLink"];
	header("Location: Reviews.php");
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Interactive Map</title>
	
    <link rel="stylesheet" type="text/css" href="MapStyleSheet.css">
    <link rel="shortcut icon" type="image/x-icon" href="https://web.cs.manchester.ac.uk/h61781jp/FirstYearTeamProject/Symbol1.png">
</head>

<body>

    <?php include("AccountNavBar.php"); ?>
    <?php include("MapFunctions.php"); ?>

    <!--Div that contains the map -->
    <div id="map"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGvOaSMLj8ZDKf3q6bA7MXZf4wqHT8juI&callback=initMap&libraries=&v=weekly" async></script>
</body>

</html>
<style>
    a[href="MapIndex.php"]{
        color: rgba(0, 136, 169, 1);
    }
    
    a[href="MapIndex.php"]:hover{
        color: rgba(0, 136, 169, 1) !important;
    }
</style>