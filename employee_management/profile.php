<?php

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['employeeid'])) {
    header("Location: index.html");
    exit();
}

$employeeid = $_SESSION['employeeid'];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_query = "SELECT e.employeeid, e.username, e.email, e.joining_date, e.calculated_salary, d.Dname AS department
                   FROM employee e
                   LEFT JOIN department d ON e.departmentid = d.departmentid
                   WHERE e.employeeid = ?";
$stmt = $conn->prepare($employee_query);
$stmt->bind_param("i", $employeeid);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #121212, #1e1e1e);
            color: #e0e0e0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #1db954;
        }

        .profile-container {
            width: 50%;
            margin: 50px auto;
            background: #292929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        .profile-container p {
            font-size: 18px;
            margin: 10px 0;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #1db954;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Employee Profile</h1>

    <div class="profile-container">
        <?php if ($employee): ?>
            <p><strong>Employee ID:</strong> <?php echo $employee['employeeid']; ?></p>
            <p><strong>Name:</strong> <?php echo $employee['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $employee['email']; ?></p>
            <p><strong>Joining Date:</strong> <?php echo $employee['joining_date']; ?></p>
            <p><strong>Current Salary:</strong> <?php echo $employee['calculated_salary']; ?></p>
            <p><strong>Department:</strong> <?php echo $employee['department'] ? $employee['department'] : 'N/A'; ?></p>
        <?php else: ?>
            <p>No profile data available.</p>
        <?php endif; ?>
    </div>

    <div class="back-link">
        <a href="employee.php">Back to Dashboard</a>
    </div>

</body>
</html>
