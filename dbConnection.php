<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'employeedb';

// Create a connection (outside any functions)
$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// echo "Success";
?>
