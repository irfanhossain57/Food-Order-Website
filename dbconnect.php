<?php
// Database connection parameters
$servername = "localhost"; 
$username = "root";
$password = "";
$database = "food_order";

// Create connection
$connection = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>