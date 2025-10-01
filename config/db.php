<?php
$host = "localhost";
$user = "root";      // default in XAMPP
$pass = "";          // usually empty
$db   = "db";        // your database name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
