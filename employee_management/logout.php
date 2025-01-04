<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['employeeid'])) {
    $employeeid = $_SESSION['employeeid'];
    date_default_timezone_set('Asia/Dhaka'); // Set timezone to Dhaka, Bangladesh
    $current_time = date('Y-m-d H:i:s');
    $date = date('Y-m-d');

    // Update attendance table
    $sql = "UPDATE attendance
            SET CheckOutTime = '$current_time'
            WHERE employeeid = $employeeid AND DATE(CheckInTime) = '$date' AND CheckOutTime = '0000-00-00 00:00:00'";

    if ($conn->query($sql) === true) {
        if ($conn->affected_rows > 0) {
            echo "Check-out time recorded successfully.";
        } else {
            echo "No matching record found to update.";
        }
    } else {
        echo "Error: " . $conn->error;
    }

    // Destroy session
    session_unset();
    session_destroy();

    // Redirect to login page
    header("Location: index.html");
    exit();
} else {
    echo "You are not logged in.";
    header("Location: index.html");
    exit();
}

// Close the connection
$conn->close();
?>
