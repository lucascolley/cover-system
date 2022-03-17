<?php

// Complex client-server model

$serverName = "localhost";
$dbUsername = "root";
$serverName = "";
$dbName = "dbCover";

$conn = mysqli_connect($serverName, $dbUsername, $serverName, $dbName);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}
