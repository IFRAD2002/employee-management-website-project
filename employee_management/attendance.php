<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f3f5;
        }
        h1 {
            text-align: center;
            color: #333;
            padding: 40px 0;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
            color: #333;
        }
        table th {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }
        table td {
            background-color: white;
            border-bottom: 1px solid #f0f0f0;
        }
        table tr:nth-child(even) td {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Attendance Records</h1>

        <table>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php
            // Database connection
            $conn = new mysqli('localhost', 'root', '', 'employee_management');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch attendance records
            $sql = "SELECT a.ID, e.username, a.Date, a.Status
                    FROM attendance a
                    JOIN employee e ON a.EmployeeID = e.EmployeeID
                    ORDER BY a.Date DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['username'] . '</td>';
                    echo '<td>' . $row['Date'] . '</td>';
                    echo '<td>' . $row['Status'] . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3" class="no-data">No attendance records found.</td></tr>';
            }

            $conn->close();
            ?>
        </table>
    </div>
</body>
</html>
