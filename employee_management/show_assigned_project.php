<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in employee ID
$employee_id = $_SESSION['employeeid']; // Ensure this session variable is properly set during login

// Fetch project details where the employee's department matches the project's manager's employee ID
$sql = "SELECT p.ProjectName, p.StartDate, p.EndDate
        FROM project p
        INNER JOIN employee e ON p.employeeid = e.employeeid
        WHERE e.departmentid = (SELECT departmentid FROM employee WHERE employeeid = $employee_id)";
$result = $conn->query($sql);

$projects = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Projects</title>
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

        .no-project {
            font-size: 1.5rem;
            color: #ff5c5c;
            text-align: center;
            margin-top: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Your Assigned Projects</h1>

    <?php if (count($projects) > 0): ?>
        <table>
            <tr>
                <th>Project Name</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo htmlspecialchars($project['ProjectName']); ?></td>
                    <td><?php echo htmlspecialchars($project['StartDate']); ?></td>
                    <td><?php echo htmlspecialchars($project['EndDate']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p class="no-project">You have not yet been assigned a project!</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="employee.php">Back to Dashboard</a>
    </div>
</body>
</html>
