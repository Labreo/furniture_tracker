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

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Insert new user
    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();

    // Log the action
    logAction($conn, $_SESSION['username'], "Added new user: $username with role $role");

    echo "User added successfully!";
}

// Update a user’s role
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_role'])) {
    $id = $_POST['id'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $role, $id);
    $stmt->execute();

    // Log the action
    logAction($conn, $_SESSION['username'], "Updated role for user ID $id to $role");

    echo "Role updated successfully!";
}

// Delete a user
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Fetch user for logging
    $sql_fetch = "SELECT username FROM users WHERE id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();
    $user = $result_fetch->fetch_assoc();

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Log the action
    logAction($conn, $_SESSION['username'], "Deleted user: " . $user['username']);

    echo "User deleted successfully!";
}

// Fetch all users
$sql = "SELECT id, username, role FROM users";
$result = $conn->query($sql);

// Function to log actions
function logAction($conn, $admin, $action) {
    $sql = "INSERT INTO audit_logs (user_name, action, user_role) VALUES (?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $admin, $action);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
</head>
<body>
    <h1>Manage Users</h1>

    <h2>Add User</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin">Admin</option>
            <option value="student">Student</option>
        </select>
        <button type="submit" name="add_user">Add User</button>
    </form>

    <h2>Users</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>";
                echo "<form method='POST' action='' style='display:inline;'>";
                echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                echo "<select name='role'>";
                echo "<option value='admin'" . ($row['role'] == 'admin' ? ' selected' : '') . ">Admin</option>";
                echo "<option value='student'" . ($row['role'] == 'student' ? ' selected' : '') . ">Student</option>";
                echo "</select>";
                echo "<button type='submit' name='update_role'>Update Role</button>";
                echo "</form> ";
                echo "<a href='?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this user?');\">Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No users found.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
