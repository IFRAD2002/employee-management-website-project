<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$employee_query = "SELECT employeeid, salary FROM employee";
$employee_result = $conn->query($employee_query);

while ($employee = $employee_result->fetch_assoc()) {
    $employeeid = $employee['employeeid'];
    $base_salary = $employee['salary'];
    $total_salary = 0;

    $current_month = date('Y-m');
    $attendance_query = "SELECT CheckInTime, CheckOutTime
                         FROM attendance
                         WHERE employeeid = $employeeid AND DATE_FORMAT(CheckInTime, '%Y-%m') = '$current_month'";
    $attendance_result = $conn->query($attendance_query);

    while ($attendance = $attendance_result->fetch_assoc()) {
        $check_in_time = strtotime($attendance['CheckInTime']);
        $check_out_time = strtotime($attendance['CheckOutTime']);


        $work_seconds = $check_out_time - $check_in_time;

        if ($work_seconds < 5 * 3600) {
            $total_salary += ($base_salary - 1000);
        } elseif ($work_seconds > 8 * 3600) {
            $extra_hours = floor(($work_seconds - 8 * 3600) / 3600);
            $total_salary += $base_salary + ($extra_hours * 150);
        } else {
            $total_salary += $base_salary;
        }
    }


    $update_query = "UPDATE employee SET calculated_salary = $total_salary WHERE employeeid = $employeeid";
    $conn->query($update_query);
}

echo "Payroll calculated and updated in the employee table for the month $current_month.";
header("Location: calculate_payroll.php");
exit();

$conn->close();
?>