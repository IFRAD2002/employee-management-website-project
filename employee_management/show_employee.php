<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch employee data
$employee_query = "SELECT e.employeeid, e.username, e.email, e.joining_date, e.gender, d.Dname AS department 
                   FROM employee e
                   LEFT JOIN department d ON e.departmentid = d.departmentid";
$employee_result = $conn->query($employee_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Employees</title>
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
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background: #292929;
            color: #e0e0e0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        table th, table td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }

        table th {
            background: #1f1f1f;
            color: #1db954;
        }

        a {
            color: #1db954;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Employee Details</h1>

    <?php if ($employee_result && $employee_result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Joining Date</th>
                <th>Gender</th>
                <th>Department</th>
            </tr>
            <?php while ($row = $employee_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['employeeid']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['joining_date']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['department'] ? $row['department'] : 'N/A'; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No employee data available.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="admin.php">Back to Dashboard</a>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
