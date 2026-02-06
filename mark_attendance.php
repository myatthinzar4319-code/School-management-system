<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

// Fetch courses
$courses = $conn->query("SELECT DISTINCT course FROM student_courses");

// Fetch students by course
$students_by_course = [];
$students_query = $conn->query("
    SELECT sc.course, u.id, u.username 
    FROM student_courses sc 
    JOIN users u ON sc.student_id = u.id 
    WHERE u.role = 'student'
");

while ($row = $students_query->fetch_assoc()) {
    $students_by_course[$row['course']][] = ['id' => $row['id'], 'username' => $row['username']];
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_id = $_SESSION['user_id'];
    $course = $_POST['course'];

    // Mark attendance for each student
    foreach ($_POST['attendance'] as $student_id => $status) {
        $stmt = $conn->prepare("INSERT INTO attendance (student_id, teacher_id, course, date, status) VALUES (?, ?, ?, CURDATE(), ?)");
        $stmt->bind_param("iiss", $student_id, $teacher_id, $course, $status);
        $stmt->execute();
    }

    $message = "<div class='alert alert-success mt-3'>Attendance marked successfully!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Mark Attendance</title>
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
            max-width: 600px;
            text-align: center;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: bold;
        }

        label {
            font-weight: bold;
            text-align: left;
            display: block;
            margin-top: 10px;
        }

        .student-row {
            background: rgba(224, 243, 169, 0.7);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .student-name {
            font-weight: bold;
            font-size: 1.1rem;
            flex: 1;
            color: #333;
        }

        .attendance-options {
            display: flex;
            gap: 10px;
        }

        input[type="radio"] {
            transform: scale(1.2);
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
            background:rgb(87, 176, 226);
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
            const studentsContainer = document.getElementById('studentsContainer');

            // Students data from PHP
            const studentsData = <?php echo json_encode($students_by_course); ?>;

            // Load students based on selected course
            courseSelect.addEventListener('change', function () {
                const course = courseSelect.value;

                // Clear current student rows
                studentsContainer.innerHTML = '';

                if (studentsData[course]) {
                    studentsData[course].forEach(student => {
                        // Create student row
                        const studentRow = document.createElement('div');
                        studentRow.classList.add('student-row');

                        // Student name
                        const studentName = document.createElement('div');
                        studentName.classList.add('student-name');
                        studentName.textContent = student.username;

                        // Attendance options
                        const attendanceOptions = document.createElement('div');
                        attendanceOptions.classList.add('attendance-options');

                        const presentOption = document.createElement('label');
                        presentOption.innerHTML = `<input type="radio" name="attendance[${student.id}]" value="Present" required> Present`;

                        const absentOption = document.createElement('label');
                        absentOption.innerHTML = `<input type="radio" name="attendance[${student.id}]" value="Absent" required> Absent`;

                        attendanceOptions.appendChild(presentOption);
                        attendanceOptions.appendChild(absentOption);

                        // Append elements to student row
                        studentRow.appendChild(studentName);
                        studentRow.appendChild(attendanceOptions);

                        // Add student row to container
                        studentsContainer.appendChild(studentRow);
                    });
                } else {
                    studentsContainer.innerHTML = '<div class="alert alert-warning">No students enrolled in this course.</div>';
                }
            });
        });
    </script>
</head>
<body>
    <div class="overlay"></div>

    <!-- Form Container -->
    <div class="form-container">
        <h2>Mark Attendance</h2>
        <?php echo $message; ?>

        <form method="POST">
            <label>Select Course:</label>
            <select name="course" id="course" class="form-control mb-2" required>
                <option value="">-- Select Course --</option>
                <?php while ($course_row = $courses->fetch_assoc()) { ?>
                    <option value="<?php echo htmlspecialchars($course_row['course']); ?>">
                        <?php echo htmlspecialchars($course_row['course']); ?>
                    </option>
                <?php } ?>
            </select>

            <!-- Dynamic Student Attendance Rows -->
            <div id="studentsContainer"></div>

            <button type="submit">Submit Attendance</button>
        </form>

        <!-- Go Back Button -->
        <a href="teacher_dashboard.php" class="back-btn">Go Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
