<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('images/student_bg2.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.6);
            z-index: -1;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            padding: 20px;
            text-align: center;
            margin-top: 40px;
        }

        .dashboard-header {
            margin-bottom: 100px;
            font-size: 2rem;
            font-weight: bold;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            color: black;
            border: none;
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, background-color 0.3s ease;
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card:hover {
            transform: translateY(-10px);
            background: rgb(224, 243, 169);
        }

        .card-title {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-link {
            color: black;
            font-weight: bold;
            text-decoration: none;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: red;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: darkred;
        }

        @media (max-width: 768px) {
            .dashboard-header {
                font-size: 1.5rem;
            }

            .card {
                margin-bottom: 20px;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay for contrast -->
    <div class="overlay"></div>

    <!-- Logout Button -->
    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
  

    <!-- Dashboard Content -->
    <div class="container">
        <h1 class="dashboard-header">Teacher Dashboard</h1>

        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <h2 class="card-title">Give Grades</h2>
                    <a href="give_grade.php" class="card-link">Go to Grades</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <h2 class="card-title">Mark Attendance</h2>
                    <a href="mark_attendance.php" class="card-link">Go to Attendance</a>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card">
                    <h2 class="card-title">Upload Materials</h2>
                    <a href="upload_material.php" class="card-link">Go to Materials</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
