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

// Fetch ongoing repairs and their associated damages
$sql = "SELECT 
            repairs.id AS repair_id, 
            damages.description AS damage_description, 
            damages.location AS damage_location, 
            repairs.status AS repair_status, 
            repairs.technician 
        FROM repairs 
        LEFT JOIN damages ON repairs.damage_id = damages.id 
        WHERE repairs.status != 'completed'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ongoing Repairs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Ongoing Repairs</h1>
    </header>
    <table>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
            <th>Technician</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["repair_id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["damage_description"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["damage_location"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["repair_status"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["technician"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No ongoing repairs</td></tr>";
        }
        ?>
    </table>
    <footer>
        <p>&copy; 2024 College Furniture Tracker</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
