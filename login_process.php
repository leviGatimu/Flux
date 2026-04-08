<?php
session_start();

// 1. Database Connection Settings
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Process Login Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Retrieve user from database
    $sql = "SELECT id, full_name, password_hash FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Verify hashed password
        if (password_verify($password, $user['password_hash'])) {
            // Success! Initialize session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid password
            header("Location: login.php?error=invalid text");
        }
    } else {
        // User not found
        header("Location: login.php?error=invalid text");
    }
}

$conn->close();
?>
