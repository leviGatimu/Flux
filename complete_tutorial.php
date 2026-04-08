<?php
session_start();
if (!isset($_SESSION['user_id']))
    exit();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

$user_id = $_SESSION['user_id'];
// Mark tutorial as completed
$conn->query("UPDATE users SET has_seen_tutorial = 1 WHERE id = $user_id");

$conn->close();
?>