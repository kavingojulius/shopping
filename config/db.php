<?php

$host = 'localhost';
$dbname = 'shopping_db';
$user = 'root';
$pass = '';

// create a connection
$conn = new mysqli($host, $user, $pass, $dbname);

// check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>