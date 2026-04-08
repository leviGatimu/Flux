<?php
session_start();

// 1. Security Check
if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: dashboard.php");
    exit();
}

// 2. Database Connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Clean the Input and Save
$user_id = $_SESSION['user_id'];
$subject_name = $conn->real_escape_string(trim($_POST['subject_name']));

if (!empty($subject_name)) {
    $sql = "INSERT INTO subjects (user_id, subject_name) VALUES ('$user_id', '$subject_name')";
    $conn->query($sql);
}

$conn->close();

// 4. Send them right back to the dashboard instantly
header("Location: dashboard.php");
exit();
?>