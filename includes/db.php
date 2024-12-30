<?php
$servername = "localhost";
$username = "root";
$password = "root"; // Default for MAMP
$dbname = "spending_tracker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
