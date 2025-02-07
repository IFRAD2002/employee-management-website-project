<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee Details</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('edit_employee.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            margin: 0;
        }


        .container {
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
        }


        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }


        form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid rgba(0, 0, 0, 0.5);
            border-radius: 4px;
            font-size: 16px;
            background-color: transparent;
            color: #000;
        }

        form input::placeholder, form select option {
            color: #000;
        }


        form button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
            padding: 10px;
            border-radius: 4px;
        }

        form button:hover {
            background-color: #45a049;
        }


        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>

</head>
<body>
    <div class="container">
        <h1>Edit Employee Details</h1>
        <form id="editEmployeeForm" action="update_employee.php" method="POST">
            <label for="employeeId">Employee ID:</label>
            <input type="text" id="employeeId" name="employeeId" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone_no">Phone Number:</label>
            <input type="text" id="phone_no" name="phone_no" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" required>

            <label for="joining_date">Joining Date:</label>
            <input type="date" id="joining_date" name="joining_date" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="department_id">Department:</label>
            <select id="department_id" name="department_id" required>

                <?php

                $conn = new mysqli("localhost", "root", "", "employee_management");


                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }


                $sql = "SELECT departmentid, Dname FROM department";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['departmentid']}'>{$row['Dname']}</option>";
                    }
                } else {
                    echo "<option value='' disabled>No Departments Available</option>";
                }


                $conn->close();
                ?>
            </select>

            <button type="submit">Update Employee</button>
        </form>
    </div>
</body>
</html>