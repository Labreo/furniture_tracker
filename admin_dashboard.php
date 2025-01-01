<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

echo "Welcome, Admin! You have full access to manage furniture.";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>
    <p>Here you can add or update furniture records.</p>

    <!-- Add form or links to manage furniture -->
    <div class="container">
        <a href="add_furniture.php">Add Furniture</a> | <a href="index.php">View Index</a> | <a href="repair_status.php">View Repair Status</a> | <a href="logout.php">Logout</a> 
        
    </div>
    
        <p>&copy; 2024 College Furniture Tracker</p>
    
</body>
</html>
