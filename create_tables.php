<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Set up Database</title>
</head>
<body>
<?php
$database_host = "dbhost.cs.man.ac.uk";
$database_user = "h61781jp";
$database_pass = "Nevthebear12+";
$database_name = "2021_comp10120_y18";



$pdo = new pdo('mysql:host=' . $database_host . ';dbname=' . $database_name,$database_user,$database_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

$sql = "CREATE TABLE IF NOT EXISTS User
(
    UserID VARCHAR(20) not null,
    Email VARCHAR(20) not null UNIQUE,
    Username VARCHAR(20) not null UNIQUE,
    Password VARCHAR(20) not null,
    Biography VARCHAR(100) not null,
    Priveleges VARCHAR(20) not null,
    PRIMARY KEY(UserID)
)";
$pdo->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS Review
(
    ReviewID VARCHAR(20) not null,
    UserID VARCHAR(20) not null,
    LocationID VARCHAR(20) not null,
    Review VARCHAR(20),
    ReviewDate DATETIME(6) not null,
    StarRating INT(1) not null,
    PRIMARY KEY(ReviewID)
)";
$pdo->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS Location
(
    LocationID VARCHAR(20) not null,
    Location VARCHAR(20) not null,
    Name VARCHAR(20) not null,
    Description VARCHAR(20),
    AvgStarRating INT(1),
    Capacity INT(4),
    Website VARCHAR(30),
    State VARCHAR(10),
    PRIMARY KEY(LocationID)
)";
$pdo->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS Request
(
    RequestID VARCHAR(20) not null,
    name VARCHAR(20) not null,
    Location VARCHAR(20) not null,
    Description VARCHAR(20),
    Image VARBINARY(200),
    UserID VARCHAR(20) not null,

    PRIMARY KEY(RequestID)
)";
$pdo->exec($sql);



?>
</body>
</html>