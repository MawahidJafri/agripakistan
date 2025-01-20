<?php
// Database configuration
$servername = "localhost"; // Default server
$username = "root";        // Default username for XAMPP
$password = "";            // Default password for XAMPP
$database = "agri_pakistan"; // Correct database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    // Optional debugging message
    // echo "Database connected successfully!";
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) { // 30 minutes
    session_unset();
    session_destroy();
}
$_SESSION['last_activity'] = time();

?>
