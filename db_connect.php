<?php
/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_demo_db";
$base_url='http://localhost/myapp/'; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
} 
?>