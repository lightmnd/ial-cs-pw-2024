<?php
$hostname_DBConn = "localhost";
$database_DBConn = "test";
$username_DBConn = "root";
$password_DBConn = "Margot2024!!";

$DBConn = mysqli_connect($hostname_DBConn, $username_DBConn, $password_DBConn, $database_DBConn);
mysqli_set_charset($DBConn, "utf8");

if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}   

$query = "SELECT VERSION()";
if ($result = mysqli_query($DBConn, $query)) {
    $row = mysqli_fetch_assoc($result);
    // echo "Connected to database. MySQL version: " . $row['VERSION()'];
    mysqli_free_result($result);
} else {
    echo "Error executing query: " . mysqli_error($DBConn);
}

$GLOBALS['DBConn'] = $DBConn;