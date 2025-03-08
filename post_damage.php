<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture_tracking";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the upload directory
$upload_dir = __DIR__ . "/uploads/";

// Ensure the directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);

    // Validate file upload
    if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        echo "Failed to upload photo. Error Code: " . $_FILES['photo']['error'];
        exit();
    }

    $photo = basename($_FILES['photo']['name']);
    $target_file = $upload_dir . $photo;
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    // Validate file type
    if (!in_array($file_type, $allowed_types)) {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        exit();
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
        // Insert data into damages table
        $stmt = $conn->prepare("INSERT INTO damages (description, location, photo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $description, $location, $photo);

        if ($stmt->execute()) {
            $damage_id = $stmt->insert_id;

            // Insert into repairs table
            $repair_stmt = $conn->prepare("INSERT INTO repairs (damage_id) VALUES (?)");
            $repair_stmt->bind_param("i", $damage_id);
            $repair_stmt->execute();

            echo "Damage reported successfully!";
            $repair_stmt->close();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to move uploaded file.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Damaged Equipment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Post Damaged Equipment</h1>
    </header>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="description">Description:</label><br>
        <input type="text" id="description" name="description" required><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" required><br><br>

        <label for="photo">Photo:</label><br>
        <input type="file" id="photo" name="photo" required><br><br>

        <input type="submit" value="Post Damage">
    </form>
    <footer>
        <p>&copy; 2024 College Furniture Tracker</p>
    </footer>
</body>
</html>
