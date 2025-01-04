<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Default for XAMPP is an empty string
$dbname = "employee_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone_no = $_POST["phone_no"];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $gender = $_POST["gender"];
    $joining_date = $_POST["joining_date"];
    $salary = $_POST["salary"];
    $isManager=1;

    // Insert data into the database
    $sql = "INSERT INTO employee (username, email, phone_no, password, gender, joining_date, salary, isManager)
            VALUES ('$username', '$email', '$phone_no', '$hashed_password', '$gender', '$joining_date', '$salary', '$isManager')";

    if ($conn->query($sql) === TRUE) {
        echo "Manager added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>