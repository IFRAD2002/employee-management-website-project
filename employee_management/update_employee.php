<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = $_POST['employeeId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_no = $_POST['phone_no'];
    $gender = $_POST['gender'];
    $salary = $_POST['salary'];
    $joining_date = $_POST['joining_date'];
    $password = $_POST["password"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $department_id = $_POST["department_id"];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'employee_management');

    // Check connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Update the employee details
    $sql = "UPDATE employee
            SET username = '$username',
                email = '$email',
                phone_no = '$phone_no',
                gender = '$gender',
                salary = '$salary',
                joining_date = '$joining_date',
                departmentid = '$department_id',
                password = '$hashed_password'
            WHERE employeeid = '$employeeId'";

    if ($conn->query($sql) === TRUE) {
        echo "Employee details updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method.";
}
