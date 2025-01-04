<?php
// Start session to track logged-in users
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Query to fetch the user by email
    $sql = "SELECT employeeid, username, password, isAdmin, isManager FROM employee WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];
        $employeeid = $user['employeeid'];

        // Verify the password using password_verify
        if (password_verify($password, $hashed_password)) {
            // Set session variables
            $_SESSION['logged_in'] = true;
            $_SESSION['employeeid'] = $employeeid;

            // Timezone setup and current timestamp
            date_default_timezone_set('Asia/Dhaka'); // Set timezone to Dhaka, Bangladesh
            $current_time = date('Y-m-d H:i:s');
            $date = date('Y-m-d');

            // Query to check attendance for the current date
            $attendance_check = "SELECT id, checkintime, checkouttime FROM attendance
                                 WHERE employeeid = $employeeid AND DATE(checkintime) = '$date'";
            $attendance_result = $conn->query($attendance_check);

            if ($attendance_result->num_rows > 0) {
                // User is logging out, update checkout time if not already set
                $attendance_row = $attendance_result->fetch_assoc();
                if ($attendance_row['checkouttime'] === null) {
                    $update_checkout = "UPDATE attendance SET checkouttime = '$current_time'
                                        WHERE id = {$attendance_row['id']}";
                    $conn->query($update_checkout);
                }
                echo "Logged out successfully.";
            } else {
                // User is logging in, insert check-in time
                $insert_checkin = "INSERT INTO attendance (employeeid, checkintime)
                                   VALUES ($employeeid, '$current_time')";
                $conn->query($insert_checkin);
                echo "Logged in successfully.";
            }

            // Redirect based on user role
            if ($user['isAdmin'] == 1) {
                header("Location: admin.php"); // Redirect to admin dashboard
                exit();
            } elseif ($user['isManager'] == 1) {
                header("Location: manager.php"); // Redirect to manager dashboard
                exit();
            } else {
                header("Location: employee.php"); // Redirect to employee dashboard
                exit();
            }
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Please fill in all fields.";
}

// Close the connection
$conn->close();
?>
