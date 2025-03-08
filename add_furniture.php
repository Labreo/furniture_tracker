<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture_tracking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['name']) && !empty($_POST['location']) && !empty($_POST['Stat']) && !empty($_POST['last_checked']) && !empty($_POST['cost'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $location = $conn->real_escape_string($_POST['location']);
        $Stat = $conn->real_escape_string($_POST['Stat']);
        $last_checked = $conn->real_escape_string($_POST['last_checked']);
        $cost = $conn->real_escape_string($_POST['cost']);

        // Handle file upload
        if (!empty($_FILES["photo"]["name"])) {
            $target_dir = "uploads/";
            $photo = basename($_FILES["photo"]["name"]);
            $target_file = $target_dir . $photo;
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
        } else {
            $photo = "no-image.png"; // Default photo
        }

        $sql = "INSERT INTO furniture_status (name, location, Stat, last_checked, cost, photo) 
                VALUES ('$name', '$location', '$Stat', '$last_checked', '$cost', '$photo')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New furniture added successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Furniture</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Add New Furniture</h1>
    </header>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="name">Furniture Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" required><br><br>

        <label for="Stat">Status:</label><br>
        <input type="text" id="Stat" name="Stat" required><br><br>

        <label for="photo">Upload Photo:</label><br>
        <input type="file" id="photo" name="photo"><br><br>

        <label for="type">Furniture Type:</label><br>
        <select id="type" name="type" required>
            <option value="">Select Type</option>
            <option value="chair">Chair</option>
            <option value="table">Table</option>
            <option value="desk">Desk</option>
            <option value="couch">Couch</option>
        </select><br><br>

        <label for="cost">Cost:</label><br>
        <input type="number" id="cost" name="cost" step="0.01" required><br><br>

        <label for="last_checked">Last Checked Date:</label><br>
        <input type="date" id="last_checked" name="last_checked" required><br><br>

        <input type="submit" value="Add Furniture">
    </form>
    <br>
    <a href="index.php">Go Back</a>
    <footer>
        <p>&copy; 2024 College Furniture Tracker</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
