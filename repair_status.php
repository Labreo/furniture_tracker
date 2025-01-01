<?php
session_start();
if ($_SESSION['role'] != 'admin') {
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

// Update repair status if a form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $repair_id = $_POST['repair_id'];
    $status = $_POST['status'];
    $technician = $_POST['technician'];
    $cost = $_POST['cost'];

    $update_sql = "UPDATE repairs SET status='$status', technician='$technician', cost='$cost' WHERE id='$repair_id'";
    if ($conn->query($update_sql) === TRUE) {
        echo "Repair status updated successfully!";
    } else {
        echo "Error: " . $update_sql . "<br>" . $conn->error;
    }
}

// Fetch all repairs and related damages
$sql = "SELECT 
            repairs.id AS repair_id, 
            damages.description AS damage_description, 
            damages.location AS damage_location, 
            damages.photo AS damage_photo, 
            repairs.status AS repair_status, 
            repairs.technician, 
            repairs.cost 
        FROM repairs 
        LEFT JOIN damages ON repairs.damage_id = damages.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Repair Status</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Repair Status</h1>
    </header>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<h3>Damage Description:</h3>";
                echo "<p>" . htmlspecialchars($row["damage_description"]) . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row["damage_location"]) . "</p>";
                echo "<img src='uploads/" . htmlspecialchars($row["damage_photo"]) . "' alt='Damage Photo' style='max-width:100%; height:auto;'><br>";
                echo "<p><strong>Status:</strong> " . htmlspecialchars($row["repair_status"]) . "</p>";
                echo "<p><strong>Technician:</strong> " . htmlspecialchars($row["technician"]) . "</p>";
                echo "<p><strong>Cost:</strong> $" . htmlspecialchars($row["cost"]) . "</p>";

                // Admin update form
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='repair_id' value='" . htmlspecialchars($row["repair_id"]) . "'>";
                echo "<label for='status'>Update Status:</label>";
                echo "<select name='status' required>";
                echo "<option value='Checked'" . ($row["repair_status"] == "Checked" ? " selected" : "") . ">Checked</option>";
                echo "<option value='Addressed'" . ($row["repair_status"] == "Addressed" ? " selected" : "") . ">Addressed</option>";
                echo "<option value='Ignored'" . ($row["repair_status"] == "Ignored" ? " selected" : "") . ">Ignored</option>";
                echo "</select><br>";
                echo "<label for='technician'>Technician:</label>";
                echo "<input type='text' name='technician' value='" . htmlspecialchars($row["technician"]) . "'><br>";
                echo "<label for='cost'>Cost:</label>";
                echo "<input type='number' name='cost' step='0.01' value='" . htmlspecialchars($row["cost"]) . "'><br>";
                echo "<input type='submit' value='Update'>";
                echo "</form>";

                echo "</div>";
            }
        } else {
            echo "<p>No repairs found</p>";
        }
        ?>
    </div>
  
        <p>&copy; 2024 College Furniture Tracker</p>
    
</body>
</html>

<?php
$conn->close();
?>
