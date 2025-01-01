<!-- filepath: fern/post_damage.php -->
<?php
session_start();
if ($_SESSION['role'] != 'student') {
    header("Location: login.php");
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
    $description = $_POST['description'];
    $location = $_POST['location'];
    $photo = $_FILES['photo']['name'];
    $target = "uploads/" . basename($photo);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
        $sql = "INSERT INTO damages (description, location, photo) VALUES ('$description', '$location', '$photo')";
        if ($conn->query($sql) === TRUE) {
            $damage_id = $conn->insert_id; // Get the ID of the newly inserted damage report

            // Insert a corresponding entry into the repairs table
            $repair_sql = "INSERT INTO repairs (damage_id) VALUES ('$damage_id')";
            if ($conn->query($repair_sql) === TRUE) {
                echo "Damage reported successfully!";
            } else {
                echo "Error: " . $repair_sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Failed to upload photo.";
    }
}

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

<?php
$conn->close();
?>