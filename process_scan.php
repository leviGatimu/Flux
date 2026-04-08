<?php
session_start();

// 1. SECURITY & DATABASE
if (!isset($_SESSION['user_id']))
    exit();

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'flux_app';
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

$user_id = $_SESSION['user_id'];
$subject_id = $_POST['subject_id'];
$user_notes = $conn->real_escape_string($_POST['user_notes']);

// 2. FETCH & DECRYPT API KEY
$user_query = "SELECT google_api_key FROM users WHERE id = $user_id";
$user_res = $conn->query($user_query);
$user_row = $user_res->fetch_assoc();

// Decryption logic (Matching our auth_process.php)
$encryption_key = "FLUX_SUPER_SECRET_VAULT_KEY_2026";
$cipher_algo = "aes-256-cbc";
$data_parts = explode('::', base64_decode($user_row['google_api_key']));
$decrypted_key = openssl_decrypt($data_parts[1], $cipher_algo, $encryption_key, 0, $data_parts[0]);

// 3. PREPARE THE IMAGE
$image_path = $_FILES['assessment_image']['tmp_name'];
$image_data = base64_encode(file_get_contents($image_path));
$image_mime = $_FILES['assessment_image']['type'];

// 4. CONSTRUCT THE AI PROMPT
$prompt = "You are the Flux Academic Strategist. Analyze this graded test image. 
1. Extract the Score (percentage or points). 
2. Identify 3 specific topics the student failed or struggled with.
3. For each failed topic, provide a 1-sentence 'Diagnosis' and a specific YouTube search query.
Return your answer strictly in this JSON format:
{
  \"score\": 85,
  \"topic\": \"Quadratic Equations\",
  \"diagnosis\": \"Brief explanation of the error\",
  \"study_plan\": \"Custom cheat sheet text\",
  \"youtube_queries\": [\"query 1\", \"query 2\"]
}";

// 5. CALL GOOGLE GEMINI API (cURL)
$api_url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-pro:generateContent?key=" . $decrypted_key;

$payload = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt],
                ["inline_data" => ["mime_type" => $image_mime, "data" => $image_data]]
            ]
        ]
    ]
];

$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
curl_close($ch);

$ai_result = json_decode($response, true);
$clean_json = json_decode($ai_result['candidates'][0]['content']['parts'][0]['text'], true);

// 6. SAVE TO DATABASE
$score = $clean_json['score'];
$topic = $conn->real_escape_string($clean_json['topic']);
$diagnosis = $conn->real_escape_string($clean_json['diagnosis']);
$study_notes = $conn->real_escape_string($clean_json['study_plan']);
$links = json_encode($clean_json['youtube_queries']);

// Insert Assessment
$conn->query("INSERT INTO assessments (subject_id, assessment_title, topic_name, score) VALUES ('$subject_id', 'AI Scan Analysis', '$topic', '$score')");
$assessment_id = $conn->insert_id;

// Insert AI Strategy
$conn->query("INSERT INTO ai_strategies (assessment_id, diagnosis, study_notes, curated_links) VALUES ('$assessment_id', '$diagnosis', '$study_notes', '$links')");

$conn->close();

// 7. REDIRECT TO RESULTS
header("Location: subject_detail.php?id=" . $subject_id);
exit();
?>