<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "furniture_tracking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['role'] = $row['role'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
        } else {
            echo "Invalid login credentials!";
        }
    } elseif (isset($_POST['register'])) {
        $username = $_POST['new_username'];
        $password = $_POST['new_password'];
        $role = 'user'; // Default role for new users

        $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if ($conn->query($sql) === TRUE) {
            echo "Account created successfully! You can now log in.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Login</h1>
    </header>
    <h2>Login</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" name="login" value="Login">
    </form>

    <h2>Register</h2>
    <form method="POST" action="">
        <label for="new_username">Username:</label>
        <input type="text" name="new_username" required><br>
        <label for="new_password">Password:</label>
        <input type="password" name="new_password" required><br>
        <input type="submit" name="register" value="Register">
    </form>
   
        <p>&copy; 2024 College Furniture Tracker</p>
    
</body>
</html>
