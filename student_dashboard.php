<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

echo "Welcome, Student! You can view the furniture status.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Student Dashboard</h1>
    </header>
    <p>Here you can view the furniture status.</p>

    <!-- Fetch and display furniture status -->
    <div class="container">
        <a href="index.php">View Furniture</a> | <a href="post_damage.php">Post Damaged Equipment</a> | <a href="view_repairs.php">View Ongoing Repairs</a> | <a href="logout.php">Logout</a>
    </div>
    <footer>
        <p>&copy; 2024 College Furniture Tracker</p>
    </footer>
</body>
</html>
