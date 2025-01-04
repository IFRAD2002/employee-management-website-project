<?php

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['employeeid'])) {
    $employeeid = $_SESSION['employeeid'];
    date_default_timezone_set('Asia/Dhaka');
    $current_time = date('Y-m-d H:i:s');
    $date = date('Y-m-d');


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


    session_unset();
    session_destroy();


    header("Location: index.html");
    exit();
} else {
    echo "You are not logged in.";
    header("Location: index.html");
    exit();
}


$conn->close();
?>
