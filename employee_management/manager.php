<?php

session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['employeeid'])) {
    e
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Employee Management System</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #121212, #1e1e1e);
            color: #e0e0e0;
        }


        header {
            background-color: #1f1f1f;
            color: white;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        nav ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: #b0b0b0;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #1db954;
            text-decoration: underline;
        }


        main {
            padding: 20px;
        }

        section {
            margin-bottom: 20px;
            background: #1e1e1e;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.7);
        }

        /* Quick Links */
        .quick-links ul {
            list-style: none;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        .quick-links ul li {
            margin-bottom: 10px;
            padding: 15px;
            text-align: center;
            background: #292929;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .quick-links ul li:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.7);
        }

        .quick-links ul li a {
            text-decoration: none;
            color: #1db954;
            font-weight: bold;
        }


        .key-metrics {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .welcome {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 200px;
            background: #292929;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        .key-metrics .metric {
            flex: 1;
            margin: 10px;
            padding: 20px;
            background: #292929;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .key-metrics .metric:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.7);
        }

        .key-metrics .metric h3 {
            color: #1db954;
            margin-bottom: 10px;
        }


        footer {
            text-align: center;
            padding: 10px;
            background: #1f1f1f;
            color: #b0b0b0;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="departments.php">Departments</a></li>
                <li><a href="employee_project.php">Projects</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>

        <section class="welcome">
            <h2>Welcome to Employee Management System!</h2>
            <p>Your one-stop solution for managing employees, projects, and more.</p>
        </section>


        <section class="quick-links">
            <h2>Quick Links</h2>
            <ul>
                <li><a href="add_employee.php">Add New Employee</a></li>
                <li><a href="edit_employee.php">Edit Employee Details</a></li>
                <li><a href="manage_leave.php">Pending Leave Applications</a></li>
                <li><a href="fire_employee.php">FIRE EMPLOYEE</a></li>
            </ul>
        </section>


    </main>

    <footer>
        &copy; 2023 Employee Management System. All Rights Reserved.
    </footer>
</body>
</html>
