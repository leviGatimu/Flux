<?php
session_start();

// 1. Database Connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// 2. Catch the Google Token
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['credential'])) {

    $jwt = $_POST['credential'];

    // 3. Verify the token with Google (MVP method without external libraries)
    // We send the token back to Google's API to ensure no one is faking it
    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . $jwt;
    $response = file_get_contents($url);
    $user_data = json_decode($response, true);

    // 4. Check if Google verified it successfully
    if (isset($user_data['email'])) {

        $email = $conn->real_escape_string($user_data['email']);
        $full_name = $conn->real_escape_string($user_data['name']);

        // 5. Check if user already exists in your database
        $check_sql = "SELECT id, google_api_key FROM users WHERE email = '$email'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            // Existing user - Log them in
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $full_name;

            // Check if they finished onboarding (do they have their BYOK key?)
            if (empty($row['google_api_key'])) {
                header("Location: register.php?step=2"); // Send to Academic Profile step
            } else {
                header("Location: dashboard.php"); // Fully setup, go to dashboard
            }
            exit();

        } else {
            // New user - Create account
            // We set a random password because they log in via Google
            $random_password = password_hash(bin2hex(random_bytes(10)), PASSWORD_BCRYPT);

            $insert_sql = "INSERT INTO users (full_name, email, password_hash) VALUES ('$full_name', '$email', '$random_password')";

            if ($conn->query($insert_sql) === TRUE) {
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['full_name'] = $full_name;

                // Redirect back to register.php but tell JS to jump to Step 2
                header("Location: register.php?step=2");
                exit();
            }
        }
    } else {
        die("Google Token Verification Failed.");
    }
} else {
    die("No credentials received.");
}
?>