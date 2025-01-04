<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Projects</title>
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
        table tr:nth-child(even) {
            background: #1e1e1e;
        }
        table tr:hover {
            background: #333;
        }
        .no-data {
            text-align: center;
            color: #e0e0e0;
            font-size: 18px;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #1db954;
            color: #e0e0e0;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin: 20px auto;
            display: block;
            text-align: center;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #14863b;
        }
        .back-link {
            display: block;
            text-align: center;
            margin: 20px;
        }
        .back-link a {
            color: #1db954;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Project List</h1>

    <a href="add_project.php" class="btn">Add New Project</a>

    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = ""; // Password (assuming it’s empty for local development)
    $dbname = "employee_management";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch project data
    $sql = "
        SELECT
            p.ProjectID,
            p.ProjectName,
            p.StartDate,
            p.EndDate,
            e.username AS ManagerName,
            (SELECT COUNT(*) FROM employee WHERE ProjectID = p.ProjectID) AS EmployeeCount
        FROM project p
        LEFT JOIN employee e ON p.employeeid = e.employeeid
    ";
    $result = $conn->query($sql);

    // Check for query errors
    if (!$result) {
        die("Error executing query: " . $conn->error);
    }

    echo '<table>';
    echo '<tr>
            <th>Project ID</th>
            <th>Project Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Manager Name</th>
            <th>Number of Employees</th>
          </tr>';

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['ProjectID'] . '</td>';
            echo '<td>' . $row['ProjectName'] . '</td>';
            echo '<td>' . date('Y-m-d', strtotime($row['StartDate'])) . '</td>';
            echo '<td>' . ($row['EndDate'] ? date('Y-m-d', strtotime($row['EndDate'])) : 'Ongoing') . '</td>';
            echo '<td>' . ($row['ManagerName'] ? $row['ManagerName'] : 'Unassigned') . '</td>';
            echo '<td>' . $row['EmployeeCount'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6" class="no-data">No projects available!</td></tr>';
    }

    echo '</table>';

    $conn->close();
    ?>

</body>
</html>
