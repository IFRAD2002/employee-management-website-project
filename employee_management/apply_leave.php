<?php
// Start session and check if employee is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['employeeid'])) {
    header("Location: index.html");
    exit();
}

$employeeid = $_SESSION['employeeid'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$dept_query = "SELECT departmentid, managerid FROM department
               WHERE departmentid = (SELECT departmentid FROM employee WHERE employeeid = $employeeid)";
$dept_result = $conn->query($dept_query);
$department = $dept_result->fetch_assoc();
$departmentid = $department['departmentid'];
$managerid = $department['managerid'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_date = $conn->real_escape_string($_POST['leave_date']);
    $reason = $conn->real_escape_string($_POST['reason']);

    $insert_query = "INSERT INTO leave_tracker (employeeid, departmentid, leave_date, reason, managerid)
                     VALUES ($employeeid, $departmentid, '$leave_date', '$reason', $managerid)";
    if ($conn->query($insert_query) === TRUE) {
        $message = "Leave application submitted successfully.";
    } else {
        $message = "Error: " . $conn->error;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #1f4037, #99f2c8);
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #1f4037;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background: #1f4037;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #145a32;
        }
        .message {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            color: #1f4037;
            margin-top: 10px;
        }
        .back-link {
            text-align: center;
            margin-top: 15px;
        }
        .back-link a {
            text-decoration: none;
            color: #1f4037;
            font-weight: bold;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Apply for Leave</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="leave_date">Leave Date:</label>
            <input type="date" name="leave_date" id="leave_date" required>

            <label for="reason">Reason:</label>
            <textarea name="reason" id="reason" rows="4" required></textarea>

            <button type="submit">Submit</button>
        </form>
        <div class="back-link">
            <a href="employee.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
