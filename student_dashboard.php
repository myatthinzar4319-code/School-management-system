<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('images/student_bg2.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .container {
            background: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }

        h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            background: rgb(87, 176, 226);
            color: white;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, transform 0.2s;
            text-decoration: none;
        }

        .nav-link:hover {
            background: rgb(224, 243, 169);
            color: black;
            transform: translateY(-5px);
        }

        .nav-link-icon {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: red;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: darkred;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            .nav-link {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <!-- Logout Button -->
    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>

    <div class="container">
        <h2>Student Dashboard</h2>
        <nav class="nav">
            <a class="nav-link" href="view_grades.php">
                <span class="nav-link-icon">&#x1F4DA;</span> View Grades
            </a>
            <a class="nav-link" href="view_attendance.php">
                <span class="nav-link-icon">&#x1F4CB;</span> View Attendance
            </a>
            <a class="nav-link" href="view_materials.php">
                <span class="nav-link-icon">&#x1F4C4;</span> View Materials
            </a>
        </nav>
    </div>

</body>
</html>
