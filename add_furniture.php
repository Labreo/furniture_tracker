<?php
session_start();
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
    $name = $_POST['name'];
    $location = $_POST['location'];
    $Stat = $_POST['Stat'];
    $last_checked = $_POST['last_checked'];
    $cost = $_POST['cost'];

    $sql = "INSERT INTO furniture_status (name, location, Stat, last_checked, cost) 
            VALUES ('$name', '$location', '$Stat', '$last_checked', '$cost')";

    if ($conn->query($sql) === TRUE) {
        echo "New furniture added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Furniture</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Function to fetch cost from the API based on furniture type
        function fetchCost() {
            var type = document.getElementById("type").value;

            if (!type) {
                document.getElementById("cost").value = '';
                return;
            }

            var apiUrl = "simulated_api.php?type=" + type;

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    if (data.cost !== undefined) {
                        document.getElementById("cost").value = data.cost;
                    } else {
                        alert('Invalid furniture type selected.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching the cost:', error);
                });
        }
    </script>
</head>
<body>
    <header>
        <h1>Add New Furniture</h1>
    </header>
    <form method="POST" action="">
        <label for="name">Furniture Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location" required><br><br>

        <label for="Stat">Stat:</label><br>
        <input type="text" id="Stat" name="Stat" required><br><br>

        <label for="type">Furniture Type:</label><br>
        <select id="type" name="type" onchange="fetchCost()" required>
            <option value="">Select Type</option>
            <option value="chair">Chair</option>
            <option value="table">Table</option>
            <option value="desk">Desk</option>
            <option value="couch">Couch</option>
        </select><br><br>

        <label for="cost">Cost (Auto-Filled):</label><br>
        <input type="number" id="cost" name="cost" step="0.01" readonly required><br><br>

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
