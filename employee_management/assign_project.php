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

// Fetch employees who are not managers or admins
$employees_query = "SELECT employeeid, username FROM employee WHERE isManager = 0 AND isAdmin = 0";
$employees_result = $conn->query($employees_query);

// Fetch all projects
$projects_query = "SELECT ProjectID, ProjectName FROM project";
$projects_result = $conn->query($projects_query);

// Handle project assignment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $project_id = $conn->real_escape_string($_POST['project_id']);

    $assign_query = "UPDATE employee SET ProjectID = $project_id WHERE employeeid = $employee_id";
    if ($conn->query($assign_query) === TRUE) {
        $success_message = "Project assigned successfully.";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Project</title>
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

        form {
            width: 50%;
            margin: 20px auto;
            background: #292929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background: #1f1f1f;
            color: #e0e0e0;
        }

        button {
            background-color: #1db954;
            cursor: pointer;
        }

        button:hover {
            background-color: #14833b;
        }

        .message {
            text-align: center;
            margin: 10px;
        }

        .back-link {
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
    <h1>Assign Project to Employee</h1>

    <?php if (isset($success_message)): ?>
        <p class="message" style="color: #1db954;"> <?php echo $success_message; ?> </p>
    <?php elseif (isset($error_message)): ?>
        <p class="message" style="color: #e74c3c;"> <?php echo $error_message; ?> </p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="employee_id">Select Employee:</label>
        <select name="employee_id" id="employee_id" required>
            <option value="">-- Select Employee --</option>
            <?php while ($employee = $employees_result->fetch_assoc()): ?>
                <option value="<?php echo $employee['employeeid']; ?>">
                    <?php echo $employee['username']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="project_id">Select Project:</label>
        <select name="project_id" id="project_id" required>
            <option value="">-- Select Project --</option>
            <?php while ($project = $projects_result->fetch_assoc()): ?>
                <option value="<?php echo $project['ProjectID']; ?>">
                    <?php echo $project['ProjectName']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <button type="submit">Assign Project</button>
    </form>

    <div class="back-link">
        <a href="admin.php">Back to Dashboard</a>
    </div>
</body>
</html>
