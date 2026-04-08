<?php
session_start();

// 1. Database Connection Settings
$db_host = 'localhost';
$db_user = 'root'; // Change if your XAMPP uses a different user
$db_pass = '';     // Change if your XAMPP has a password
$db_name = 'flux_app';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Encryption Setup for the API Key
// WARNING: In production, NEVER hardcode this. Use an environment variable.
$encryption_key = "FLUX_SUPER_SECRET_VAULT_KEY_2026";
$cipher_algo = "aes-256-cbc";

// 3. Process the Form Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize user inputs
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $api_key = $_POST['api_key']; 
    
    // Catch the new academic fields
    $education_level = $conn->real_escape_string($_POST['education_level']);
    $grading_system = $conn->real_escape_string($_POST['grading_system']);
    $target_score = $conn->real_escape_string($_POST['target_score']);

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    // Encrypt the Google API Key
    $iv_length = openssl_cipher_iv_length($cipher_algo);
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted_key = openssl_encrypt($api_key, $cipher_algo, $encryption_key, 0, $iv);
    $final_api_key_stored = base64_encode($iv . "::" . $encrypted_key);
    $final_api_key_stored = $conn->real_escape_string($final_api_key_stored);

    // Insert or Update in Database
    if (isset($_SESSION['user_id'])) {
        // User is logged in via Google, update their profile
        $user_id = $_SESSION['user_id'];
        $sql = "UPDATE users SET 
                education_level = '$education_level', 
                grading_system = '$grading_system', 
                target_score = '$target_score', 
                google_api_key = '$final_api_key_stored' 
                WHERE id = $user_id";
    } else {
        // New user registration
        $sql = "INSERT INTO users (full_name, email, password_hash, google_api_key, education_level, grading_system, target_score) 
                VALUES ('$full_name', '$email', '$password_hash', '$final_api_key_stored', '$education_level', '$grading_system', '$target_score')";
    }

    if ($conn->query($sql) === TRUE) {
        // Success! Get the ID if it was a new user
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['full_name'] = $full_name;
        }

        // Redirect them to the main app dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Handle error (e.g., email already exists)
        echo "Error: " . $sql . "<br>" . $conn->error;
        // In a real app, you would redirect back to register.php with an error message
    }
}

$conn->close();
?>