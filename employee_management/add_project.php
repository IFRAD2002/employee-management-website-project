<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('project.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #fff;
        }

        .container {
            width: 50%;
            margin: 100px auto;
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
        }

        input, select, button {
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        input, select {
            background: #fff;
            color: #000;
        }

        button {
            background: #28a745;
            color: #fff;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .error, .success {
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
        }

        .success {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Project</h1>
        <?php
        // Database connection
        $conn = new mysqli("localhost", "root", "", "employee_management");

        // Check connection
        if ($conn->connect_error) {
            die("<p class='error'>Database connection failed: " . $conn->connect_error . "</p>");
        }

        // Fetch eligible managers
        $sql = "SELECT employeeid, username FROM employee WHERE isManager = 1 AND (ProjectID IS NULL OR ProjectID = '')";
        $result = $conn->query($sql);
        ?>
        <form method="POST" action="">
            <label for="ProjectName">Project Name:</label>
            <input type="text" id="ProjectName" name="ProjectName" required>

            <label for="StartDate">Start Date:</label>
            <input type="date" id="StartDate" name="StartDate" required>

            <label for="EndDate">End Date:</label>
            <input type="date" id="EndDate" name="EndDate">

            <label for="manager">Assign Manager:</label>
            <select id="manager" name="manager" required>
                <option value="">Select a Manager</option>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['employeeid'] . '">' . $row['username'] . '</option>';
                    }
                } else {
                    echo '<option value="">No eligible managers available</option>';
                }
                ?>
            </select>

            <button type="submit">Create Project</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $projectName = $_POST['ProjectName'];
            $startDate = $_POST['StartDate'];
            $endDate = $_POST['EndDate'];
            $managerId = $_POST['manager'];

            // Insert project
            $sql = "INSERT INTO project (ProjectName, StartDate, EndDate, employeeid) VALUES ('$projectName', '$startDate', '$endDate', $managerId)";

            if ($conn->query($sql) === TRUE) {
                // Get the last inserted ProjectID
                $projectId = $conn->insert_id;

                // Update the manager's ProjectID
                $updateSql = "UPDATE employee SET ProjectID = $projectId WHERE employeeid = $managerId";
                if ($conn->query($updateSql) === TRUE) {
                    echo "<p class='success'>Project created successfully and manager assigned.</p>";
                } else {
                    echo "<p class='error'>Error updating manager: " . $conn->error . "</p>";
                }
            } else {
                echo "<p class='error'>Error creating project: " . $conn->error . "</p>";
            }
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
