<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Put your own password
$database = 'MYCAMPD';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

