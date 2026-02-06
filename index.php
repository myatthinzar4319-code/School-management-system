<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home - Language Learning</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('images/student_bg2.png') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.5); /* Semi-transparent navbar */
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            z-index: 1000;
            top: 0;
        }

        /* Align Home, Dashboard, Register, and Login to the right */
        .navbar-collapse {
            justify-content: flex-end;
        }

        .navbar-nav .nav-item {
            margin-right: 15px;
        }

        /* Button Animations */
        .navbar-nav .nav-link {
            color: black !important;
            font-weight: bold;
            transition: transform 0.3s ease, opacity 1s ease-out;
            opacity: 0;
            transform: translateY(-20px);
        }

        .navbar-nav .nav-link:hover {
            color: rgb(0, 0, 0) !important;
            transform: scale(1.1);
        }

        /* Dropdown */
        .dropdown-menu {
            background: black !important;
            border: none;
        }

        .dropdown-item {
            color: white !important;
        }

        .dropdown-item:hover {
            background: rgb(154, 246, 223) !important;
            color: black !important;
        }

        /* Main Content */
        .main-content {
            margin-top: 120px;
            padding: 20px;
            color: black;
            max-width: 600px;
            margin-left: 50px;
        }

        /* Paragraph Animation */
        p {
            font-size: 1rem;
            line-height: 1.6;
            text-align: justify;
            opacity: 0;
            transform: translateX(-100px); /* Move from left */
            animation: slideIn 1s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Fade-in Effect for Buttons */
        @keyframes fadeInButtons {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Dashboard
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="view_attendance.php">View Attendance</a></li>
                        <li><a class="dropdown-item" href="view_grades.php">View Grades</a></li>
                        <li><a class="dropdown-item" href="view_materials.php">View Materials</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Home Content -->
<div class="main-content">
    <h1>Welcome to Language Learning Platform</h1>
    <p>At our platform, we believe that learning new languages should be exciting, accessible, and tailored to your needs. Designed for young learners, we provide a modern, interactive online environment to help you master new languages at your own pace. Whether you're starting from scratch or improving your fluency, our resources, personalized lessons, and practice activities are here to support your journey.</p>
    
    <p>Start today and unlock the power of communication across cultures with ease and confidence!</p>
</div>

<!-- JavaScript to trigger animations -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Fade-in effect for navigation buttons
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        navLinks.forEach((link, index) => {
            setTimeout(() => {
                link.style.opacity = "1";
                link.style.transform = "translateY(0)";
            }, index * 300); // Stagger effect
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
