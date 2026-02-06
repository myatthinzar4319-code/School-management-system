<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch courses and enrolled students
$courses = $conn->query("SELECT DISTINCT course FROM student_courses");

$students_by_course = [];
$students_query = $conn->query("SELECT sc.course, u.id, u.username 
                                FROM student_courses sc 
                                JOIN users u ON sc.student_id = u.id 
                                WHERE u.role = 'student'");

while ($row = $students_query->fetch_assoc()) {
    $students_by_course[$row['course']][] = ['id' => $row['id'], 'username' => $row['username']];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student'];
    $grade = $_POST['grade'];
    $course = $_POST['course'];
    $teacher_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO grades (student_id, teacher_id, course, grade) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $student_id, $teacher_id, $course, $grade);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success mt-3'>Grade assigned successfully!</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Assign Grades</title>
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

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: bold;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            text-align: left;
            display: block;
        }

        input, select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.8);
        }

        button {
            background: rgb(87, 176, 226);
            color: white;
            font-weight: bold;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: rgb(224, 243, 169);
            color: black;
        }

        .alert {
            margin-top: 15px;
        }

        /* Go Back Button */
        .back-btn {
            margin-top: 15px;
            display: inline-block;
            padding: 10px 20px;
            background: rgb(87, 176, 226);
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: darkred;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const courseSelect = document.getElementById('course');
            const studentSelect = document.getElementById('student');

            // Load students dynamically based on the selected course
            courseSelect.addEventListener('change', function () {
                const course = courseSelect.value;
                const studentsData = <?php echo json_encode($students_by_course); ?>;

                // Clear current options
                studentSelect.innerHTML = '';

                if (studentsData[course]) {
                    studentsData[course].forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.id;
                        option.textContent = student.username;
                        studentSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.textContent = 'No students enrolled in this course';
                    option.disabled = true;
                    studentSelect.appendChild(option);
                }
            });
        });
    </script>
</head>
<body>
    <div class="overlay"></div>

    <!-- Form Container -->
    <div class="form-container">
        <h2>Assign Grades</h2>
        <form method="POST">
            <label>Select Course:</label>
            <select name="course" id="course" required>
                <option value="">-- Select Course --</option>
                <?php while ($course_row = $courses->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($course_row['course']); ?>">
                        <?php echo htmlspecialchars($course_row['course']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Select Student:</label>
            <select name="student" id="student" required>
                <option value="">-- Select Student --</option>
            </select>

            <label>Grade:</label>
            <input type="text" name="grade" required>

            <button type="submit">Submit Grade</button>
        </form>

        <!-- Go Back Button -->
        <a href="teacher_dashboard.php" class="back-btn">Go Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
