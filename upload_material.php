<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'teacher') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $teacher_id = $_SESSION['user_id'];
    $course = $_POST['course'];
    $file_name = basename($_FILES['file']['name']);
    $target_dir = "uploads/";
    $target_file = $target_dir . $file_name;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO materials (teacher_id, course, file_name, file_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $teacher_id, $course, $file_name, $target_file);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success mt-3'>Material uploaded successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger mt-3'>Database error: " . $stmt->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger mt-3'>File upload failed.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Materials</title>
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

        select, input[type="file"] {
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
</head>
<body>
    <div class="overlay"></div>

    <!-- Form Container -->
    <div class="form-container">
        <h2>Upload Study Material</h2>
        <?php echo $message; ?>

        <form method="POST" enctype="multipart/form-data">
            <label>Select Course:</label>
            <select name="course" required>
                <option value="English">English</option>
                <option value="Chinese">Chinese</option>
                <option value="Korean">Korean</option>
            </select>

            <label>Select File:</label>
            <input type="file" name="file" required>

            <button type="submit">Upload Material</button>
        </form>

        <!-- Go Back Button -->
        <a href="teacher_dashboard.php" class="back-btn">Go Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
