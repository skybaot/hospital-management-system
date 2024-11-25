<?php
// Create database if it doesn't exist
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS hospital_management";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);

// Include database setup
require_once 'setup_database.php';

echo "Database initialization complete.<br>";
echo "<a href='../index.html'>Go to Dashboard</a>";
?>
