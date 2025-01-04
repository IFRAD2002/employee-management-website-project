<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch department data
$sql = "SELECT d.departmentid, d.Dname, e.username AS manager_name
        FROM department d
        LEFT JOIN employee e ON d.managerid = e.employeeid";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Departments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #121212, #1e1e1e);
            color: #e0e0e0;
        }

        table {
            width: 80%;
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

        h1 {
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

        .back-link {
            display: block;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Department Details</h1>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Department ID</th>
                <th>Department Name</th>
                <th>Manager Name</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['departmentid']; ?></td>
                    <td><?php echo $row['Dname']; ?></td>
                    <td><?php echo $row['manager_name'] ? $row['manager_name'] : 'No Manager'; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No department data available.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="admin.php">Back to Dashboard</a>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
