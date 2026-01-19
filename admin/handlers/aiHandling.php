<?php
header('Content-Type: application/json');
require __DIR__ . "/../../config/config.php";
require __DIR__ . "/../../function/function.php";



// 2. Get User Question
$input = json_decode(file_get_contents('php://input'), true);
$question = $input['question'] ?? '';

if (empty($question)) {
    echo json_encode(['reply' => "Please ask a valid question."]);
    exit;
}

try {
    // 3. Fetch Real Data from Database (Sections 2 to 5 only)
    $stmt = $connection->prepare("
        SELECT section_number, section_data 
        FROM survey_progress 
        WHERE section_number BETWEEN 2 AND 5 
        ORDER BY id DESC LIMIT 50
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 4. Prepare Data for AI Context
    $context_data = "";
    foreach ($rows as $row) {
        $data = json_decode($row['section_data'], true);
        $section_name = getSectionName($row['section_number']);
        
        $readable_values = [];
        foreach ($data as $key => $val) {
            $clean_key = str_replace(['q_', 'r_', 'e_', '_'], ['', '', '', ' '], $key);
            $readable_values[] = "$clean_key: $val";
        }
        $context_data .= "Section [$section_name]: " . implode(", ", $readable_values) . "\n";
    }

    if (empty($context_data)) {
        echo json_encode(['reply' => "I checked the database, but there is no survey data available yet to analyze."]);
        exit;
    }

    // --- API KEY SETUP ---
    // Yahan apni key paste karein jo aapne screenshot mein dikhayi hai
    $myApiKey = "AIzaSyC_VtqOv71R4uLut9Wu6Pm3rCiWt8_COcA"; 

    // 5. Call Gemini AI
    $ai_reply = callGeminiAPI($question, $context_data, $myApiKey);

    echo json_encode(['reply' => $ai_reply]);

} catch (Exception $e) {
    echo json_encode(['reply' => "Error analyzing data: " . $e->getMessage()]);
}

// --- Helper Functions ---

function getSectionName($num) {
    switch ($num) {
        case 2: return "Academics";
        case 3: return "Facilities";
        case 4: return "Environment";
        case 5: return "Suggestions";
        default: return "General";
    }
}

function callGeminiAPI($question, $context, $apiKey) {
// ✅ Using Gemini 2.0 Flash (Fast & Available in your list)
// ✅ Safe Option: 'gemini-flash-latest' automatically picks the working Free model
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;
    
    // AI Instructions
    $prompt = "You are an expert Data Analyst for a University Survey System. 
    Below is the raw feedback data from students (JSON format):
    
    --- DATA START ---
    $context
    --- DATA END ---
    
    User Question: $question
    
    Instruction: Answer the user's question strictly based on the provided data. 
    If the data suggests a problem, mention it. If students are happy, mention that. 
    Keep the answer concise (under 50 words) and professional.";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        return "Connection Error: " . curl_error($ch);
    }


    $json = json_decode($response, true);
    
    if (isset($json['error'])) {
        return "AI Error: " . $json['error']['message'];
    }

    return $json['candidates'][0]['content']['parts'][0]['text'] ?? "Sorry, I couldn't analyze the data at this moment.";
}