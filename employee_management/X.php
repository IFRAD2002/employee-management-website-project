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


if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];


    $sql = "SELECT employeeid, username, password, isAdmin, isManager FROM employee WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];
        $employeeid = $user['employeeid'];


        if (password_verify($password, $hashed_password)) {
            /
            $_SESSION['logged_in'] = true;
            $_SESSION['employeeid'] = $employeeid;


            date_default_timezone_set('Asia/Dhaka'); // Set timezone to Dhaka, Bangladesh
            $current_time = date('Y-m-d H:i:s');
            $date = date('Y-m-d');


            $attendance_check = "SELECT id, checkintime, checkouttime FROM attendance
                                 WHERE employeeid = $employeeid AND DATE(checkintime) = '$date'";
            $attendance_result = $conn->query($attendance_check);

            if ($attendance_result->num_rows > 0) {

                $attendance_row = $attendance_result->fetch_assoc();
                if ($attendance_row['checkouttime'] === null) {
                    $update_checkout = "UPDATE attendance SET checkouttime = '$current_time'
                                        WHERE id = {$attendance_row['id']}";
                    $conn->query($update_checkout);
                }
                echo "Logged out successfully.";
            } else {

                $insert_checkin = "INSERT INTO attendance (employeeid, checkintime)
                                   VALUES ($employeeid, '$current_time')";
                $conn->query($insert_checkin);
                echo "Logged in successfully.";
            }

            // Redirect based on user role
            if ($user['isAdmin'] == 1) {
                header("Location: admin.php");
                exit();
            } elseif ($user['isManager'] == 1) {
                header("Location: manager.php");
                exit();
            } else {
                header("Location: employee.php");
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


$conn->close();
?>
