<?php
$host = 'dbhost.cs.man.ac.uk';
$username = 'w25464il';
$password = '36qY3gOAyOnLR6rcOQN0';
$pdo = new pdo('mysql:host=dbhost.cs.man.ac.uk;dbname=2021_comp10120_y18;', $username, $password);  //Connects to the database.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getPlaces()
{
    global $pdo;
    $sql = "SELECT * FROM Location"; // get all locations
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $array = array();
    while ($row = $stmt->fetch()) {
        //In DB, coords stored as a single string, this splits into an array
        // $Coordinates = explode(",", $row['Coordinates']);

        array_push($array, array("lat" => (float)$row['Latitude'], "long" => (float)$row['Longitude'], "name" => $row['Name'],  "desc" => $row['Description'], "image" => base64_encode($row["Image"])));  // add each entry to results
    }
    echo json_encode($array); // send results back to js script
}

// function getLocations()
// {

//     include("ConnectionToMySQL.php");
//     //Creates query
//     $query = 'SELECT * FROM Location';

//     //Gets result from query
//     $result = mysqli_query($Conn, $query);

//     //Fetch Data
//     $locations = mysqli_fetch_all($result, MYSQLI_ASSOC);
//     // var_dump($locations);

//     //Free Result
//     mysqli_free_result($result);

//     //Close Connection
//     mysqli_close($Conn);

//     //var_dump($locations[0]);
//     foreach ($locations as $location) {
//         // echo ($location["Name"] . " ");
//         // echo ($location["Description"] . " ");
//         // $coords = explode(',', $location["Coordinates"]);
//         // echo ($coords[0]);
//         // echo ($coords[1] . "<br><br>");
//         echo json_encode($location);
//     }
// }

function createPlace($lat, $long, $name, $desc)
{
    global $pdo;
    $sql = "SELECT COUNT(*) FROM Locations"; // get length of table
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch();
    $locationid = $row['COUNT(*)']; // new location id, increments from last entry
    $sql = "INSERT INTO Locations (LocationID, Latitude, Longitude, Name, Description) VALUES (:id,:lat,:long,:name,:desc)"; // insert into table
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $locationid, 'lat' => $lat, 'long' => $long, 'name' => $name, 'desc' => $desc]);
}
