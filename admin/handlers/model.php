<?php
// pages/check_models.php
header('Content-Type: application/json');

// --- PASTE YOUR API KEY HERE ---
$apiKey = "AIzaSyCQqclRBmByToA6fKDJnYgT5_6CA1xnDXY"; 

$url = "https://generativelanguage.googleapis.com/v1beta/models?key=" . $apiKey;

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo "Connection Error: " . curl_error($ch);
    exit;
}

$data = json_decode($response, true);

if (isset($data['models'])) {
    echo "<h1>✅ Available Models for your Key:</h1>";
    echo "<ul>";
    foreach ($data['models'] as $model) {
        // Show only 'generateContent' supported models
        if (in_array("generateContent", $model['supportedGenerationMethods'])) {
            // Remove 'models/' prefix for easier reading
            $name = str_replace("models/", "", $model['name']);
            echo "<li><strong>" . $name . "</strong></li>";
        }
    }
    echo "</ul>";
} else {
    echo "<h1>❌ Error:</h1>";
    echo "<pre>" . print_r($data, true) . "</pre>";
}
?>