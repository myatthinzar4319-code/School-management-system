<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch the student's enrolled course
$course_query = $conn->prepare("SELECT course FROM student_courses WHERE student_id = ?");
$course_query->bind_param("i", $student_id);
$course_query->execute();
$course_result = $course_query->get_result();
$enrolled_course = $course_result->fetch_assoc()['course'];

if ($enrolled_course) {
    // Fetch materials for the enrolled course
    $stmt = $conn->prepare("SELECT file_name, file_path, upload_date FROM materials WHERE course = ? ORDER BY upload_date DESC");
    $stmt->bind_param("s", $enrolled_course);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "No enrolled course found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Materials</title>
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
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 700px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        h2 {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: rgb(87, 176, 226);
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tr:hover {
            background: rgba(224, 243, 169, 0.7);
            transition: background 0.3s ease;
        }

        td {
            font-size: 1rem;
            color: #333;
        }

        .download-link {
            color: rgb(87, 176, 226);
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s;
        }

        .download-link:hover {
            color: rgb(224, 243, 169);
            text-decoration: underline;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            background: rgb(87, 176, 226);
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: rgb(224, 243, 169);
            color: black;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 1.3rem;
            }

            th, td {
                padding: 8px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="container">
        <h2>Study Materials for <?php echo htmlspecialchars($enrolled_course); ?></h2>

        <!-- Materials Table -->
        <?php if ($result->num_rows > 0) { ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Download</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['file_name']); ?></td>
                            <td><a class="download-link" href="<?php echo htmlspecialchars($row['file_path']); ?>" download>Download</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-warning mt-3">No materials available at the moment.</div>
        <?php } ?>

        <!-- Go Back Button -->
        <a href="student_dashboard.php" class="back-btn">Go Back</a>
    </div>
</body>
</html>
