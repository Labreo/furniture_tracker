<?php
session_start();
if (!isset($_SESSION['role'])) {
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

// Handle delete request
if (isset($_GET['delete_id']) && $_SESSION['role'] == 'admin') {
    $delete_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM furniture_status WHERE id = $delete_id";

    if ($conn->query($sql_delete) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch data from the database
$sql = "SELECT * FROM furniture_status";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Furniture Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>College Furniture Status</h1>
    </header>
    <p>Welcome, <?php echo $_SESSION['username']; ?> (Role: <?php echo $_SESSION['role']; ?>)</p>

    <!-- Dashboard Navigation -->
    <div class="dashboard-nav">
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="admin_dashboard.php" class="btn">Go to Admin Dashboard</a>
        <?php } elseif ($_SESSION['role'] == 'student') { ?>
            <a href="student_dashboard.php" class="btn">Go to Student Dashboard</a>
        <?php } ?>
    </div>

    <!-- Furniture Table -->
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Location</th>
            <th>Status</th>
            <th>Last Checked</th>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <th>Cost</th>
                <th>Action</th> <!-- Delete option for admin -->
            <?php } ?>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["location"] . "</td>";
                echo "<td>" . $row["Stat"] . "</td>";
                echo "<td>" . $row["last_checked"] . "</td>";
                
                if ($_SESSION['role'] == 'admin') {
                    echo "<td>$" . $row["cost"] . "</td>";
                    echo "<td><a href='?delete_id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a></td>";
                }

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No data available</td></tr>";
        }
        ?>
    </table>

    <br>
    <a href="logout.php">Logout</a>

    <footer>
        <p>&copy; 2024 College Furniture Tracker</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
