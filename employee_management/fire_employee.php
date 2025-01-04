<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "employee_management";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employeeid'])) {
    $employeeid = intval($_POST['employeeid']);


    $conn->begin_transaction();

    try {

        $conn->query("DELETE FROM leave_tracker WHERE employeeid = $employeeid");


        $conn->query("DELETE FROM attendance WHERE employeeid = $employeeid");


        $conn->query("UPDATE project SET employeeid = NULL WHERE employeeid = $employeeid");


        $conn->query("DELETE FROM employee WHERE employeeid = $employeeid");


        $conn->commit();
        $message = "Employee removed successfully.";
    } catch (Exception $e) {

        $conn->rollback();
        $message = "Error removing employee: " . $e->getMessage();
    }
}


$result = $conn->query("SELECT employeeid, username FROM employee");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #121212, #1e1e1e);
            color: #e0e0e0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: #292929;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            color: #e0e0e0;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        select, button {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }
        select {
            background: #1f1f1f;
            color: #e0e0e0;
            border: 1px solid #444;
        }
        button {
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #c9302c;
        }
        .message {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
        }
        .message.success {
            color: #1db954;
        }
        .message.error {
            color: #d9534f;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
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
    <div class="container">
        <h1>Fire Employee</h1>
        <?php if (isset($message)): ?>
            <p class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="employeeid">Select Employee:</label>
            <select name="employeeid" id="employeeid" required>
                <option value="">-- Choose an Employee --</option>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?= $row['employeeid'] ?>">
                        <?= htmlspecialchars($row['username']) ?> (ID: <?= $row['employeeid'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Fire Employee</button>
        </form>
        <div class="back-link">
            <a href="manager.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
