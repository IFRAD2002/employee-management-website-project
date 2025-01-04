<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management System</title>
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

        .calculate-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            background-color: #1db954;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
        }

        .calculate-btn:hover {
            background-color: #14833b;
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

    <h1>Payroll Management System</h1>

    <form method="POST" action="calculate.php">
        <button type="submit" class="calculate-btn">Calculate Salaries</button>
    </form>

    <?php

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "employee_management";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $employee_query = "SELECT employeeid, username, calculated_salary FROM employee";
    $employee_result = $conn->query($employee_query);

    echo "<table>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Calculated Salary</th>
            </tr>";

    while ($row = $employee_result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['employeeid']}</td>
                <td>{$row['username']}</td>
                <td>{$row['calculated_salary']}</td>
              </tr>";
    }

    echo "</table>";


    $conn->close();
    ?>

    <div class="back-link">
        <a href="admin.php">Back to Dashboard</a>
    </div>
</body>
</html>
