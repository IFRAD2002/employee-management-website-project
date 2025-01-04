<?php
if (isset($_GET['employeeId'])) {
    $employeeId = $_GET['employeeId'];


    $conn = new mysqli('localhost', 'root', '', 'employee_management');


    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT * FROM employee WHERE employeeid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $employeeId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        echo json_encode($employee);
    } else {
        echo json_encode(['error' => 'Employee not found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'No employee ID provided']);
}
?>