<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database config
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "hms_db";

// Connect to database
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Include helper functions
require_once __DIR__ . '/../includes/helpers.php';
?>
