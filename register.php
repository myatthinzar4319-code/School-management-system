<?php
include 'db.php';

$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $course = isset($_POST['course']) ? $_POST['course'] : null;

    // Insert the new user into the users table
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        // If the role is 'student', insert the course into student_courses table
        if ($role === 'student' && $course) {
            $user_id = $conn->insert_id;  // Get the ID of the new student
            $course_stmt = $conn->prepare("INSERT INTO student_courses (student_id, course) VALUES (?, ?)");
            $course_stmt->bind_param("is", $user_id, $course);
            $course_stmt->execute();
        }

        // Display success message
        $successMessage = "Registration successful! <a href='login.php' style='color: blue; text-decoration: underline;'>Click here to login</a>";
    } else {
        $successMessage = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
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
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            color: black;
            backdrop-filter: blur(10px);
        }

        input, select, button {
            margin: 10px 0;
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #007BFF;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .course-row {
            margin-top: 10px;
            display: none; /* Hidden by default */
        }

        .success-message {
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const roleSelect = document.getElementById('role');
            const courseRow = document.getElementById('courseRow');

            roleSelect.addEventListener('change', function () {
                if (roleSelect.value === 'student') {
                    courseRow.style.display = 'block';
                } else {
                    courseRow.style.display = 'none';
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Role:</label>
            <select name="role" id="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <!-- Course Selection Row (hidden by default) -->
            <div class="course-row" id="courseRow">
                <label>Course:</label>
                <select name="course">
                    <option value="">-- Select Course --</option>
                    <option value="English">English</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Korean">Korean</option>
                </select>
            </div>

            <button type="submit">Register</button>
        </form>

        <!-- Success Message Display -->
        <?php if ($successMessage) { ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php } ?>
    </div>
</body>
</html>
