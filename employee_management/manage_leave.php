<?php
// Start session and check if manager is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['employeeid'])) {
    header("Location: index.html");
    exit();
}

$managerid = $_SESSION['employeeid'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "employee_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch leave applications for the manager's department
$leaves_query = "SELECT lt.leave_id, lt.employeeid, e.username, lt.leave_date, lt.reason, lt.status
                 FROM leave_tracker lt
                 JOIN employee e ON lt.employeeid = e.employeeid
                 WHERE lt.managerid = $managerid AND lt.status = 'Pending'";
$leaves_result = $conn->query($leaves_query);

// Handle leave approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_id = $conn->real_escape_string($_POST['leave_id']);
    $action = $conn->real_escape_string($_POST['action']); // Accept or Reject

    $status = ($action === 'accept') ? 'Accepted' : 'Rejected';

    $update_query = "UPDATE leave_tracker SET status = '$status' WHERE leave_id = $leave_id";
    $conn->query($update_query);

    if ($status === 'Accepted') {
        // Update attendance status to 'Leave'
        $leave_date_query = "SELECT employeeid, leave_date FROM leave_tracker WHERE leave_id = $leave_id";
        $leave_data = $conn->query($leave_date_query)->fetch_assoc();
        $employeeid = $leave_data['employeeid'];
        $leave_date = $leave_data['leave_date'];

        // Check if an attendance record exists for the leave date
        $check_attendance_query = "SELECT id FROM attendance
                                   WHERE employeeid = $employeeid AND DATE(CheckInTime) = '$leave_date'";
        $attendance_result = $conn->query($check_attendance_query);

        if ($attendance_result->num_rows > 0) {
            // Update the existing attendance record
            $update_attendance_query = "UPDATE attendance SET status = 'Leave'
                                        WHERE employeeid = $employeeid AND DATE(CheckInTime) = '$leave_date'";
            $conn->query($update_attendance_query);
        } else {
            // Insert a new attendance record with status 'Leave'
            $insert_attendance_query = "INSERT INTO attendance (employeeid, status, CheckInTime)
                                        VALUES ($employeeid, 'Leave', '$leave_date 00:00:00')";
            $conn->query($insert_attendance_query);
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Leaves</title>
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

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        button {
            padding: 5px 10px;
            margin: 0 5px;
            background: #1db954;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background: #14833b;
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
    <h2>Manage Leave Applications</h2>
    <table>
        <tr>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Leave Date</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($leave = $leaves_result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $leave['employeeid']; ?></td>
            <td><?php echo $leave['username']; ?></td>
            <td><?php echo $leave['leave_date']; ?></td>
            <td><?php echo $leave['reason']; ?></td>
            <td><?php echo $leave['status']; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="leave_id" value="<?php echo $leave['leave_id']; ?>">
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>

    <div class="back-link">
        <a href="manager.php">Back to Dashboard</a>
    </div>
</body>
</html>
