<?php
header('Content-Type: application/json');
require __DIR__ . "/../../config/config.php";
require __DIR__ . "/../../function/function.php";
AdminAccess();
blockDirectAccess();
$input = json_decode(file_get_contents('php://input'), true);
$question = $input['question'] ?? '';

if (empty($question)) {
    echo json_encode(['reply' => "Please ask a valid question."]);
    exit;
}

try {
    $currentYear = date('Y');
    
    $batchStmt = $connection->prepare("SELECT current_semester FROM batches WHERE status = 'enable'");
    $batchStmt->execute();
    $activeBatches = $batchStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($activeBatches)) {
        echo json_encode(['reply' => "No active batches found to analyze."]);
        exit;
    }

    $validSessions = [];
    foreach ($activeBatches as $batch) {
        $sem = (int)$batch['current_semester'];
        $season = ($sem % 2 == 0) ? "Spring" : "Fall";
        $sessionString = "$currentYear($season)";
        
        if (!in_array($sessionString, $validSessions)) {
            $validSessions[] = $sessionString;
        }
    }

    if (empty($validSessions)) {
        echo json_encode(['reply' => "Could not determine valid sessions."]);
        exit;
    }


    $placeholders = implode(',', array_fill(0, count($validSessions), '?'));
    
    $sql = "SELECT section_1, section_2, section_3, section_4, section_5 
            FROM survey_progress 
            WHERE year_session IN ($placeholders) 
            ORDER BY started_at DESC LIMIT 50";

    $stmt = $connection->prepare($sql);
    $stmt->execute($validSessions);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $context_data = "";
    
    foreach ($rows as $row) {
        foreach ($row as $colName => $jsonContent) {
            if (empty($jsonContent)) continue;

            $data = json_decode($jsonContent, true);
            if (!$data) continue;

            $sectionName = getSectionName($colName);
            $readable_values = [];

            foreach ($data as $key => $val) {
                $clean_key = str_replace(['q_', 'r_', 'e_', '_'], ['', '', '', ' '], $key);
                $readable_values[] = "$clean_key: $val";
            }
            $context_data .= "Section [$sectionName]: " . implode(", ", $readable_values) . "\n";
        }
    }

    if (empty($context_data)) {
        echo json_encode(['reply' => "I checked the database, but there is no survey data available for the active sessions ($placeholders)."]);
        exit;
    }

    $myApiKey = ""; 

    $ai_reply = callGeminiAPI($question, $context_data, $myApiKey);

    echo json_encode(['reply' => $ai_reply]);

} catch (Exception $e) {
    echo json_encode(['reply' => "Error analyzing data: " . $e->getMessage()]);
}


function getSectionName($colName) {
    switch ($colName) {
        case 'section_1': return "Faculty Evaluation"; 
        case 'section_2': return "Academics & Labs";
        case 'section_3': return "Facilities";
        case 'section_4': return "Environment & Transport";
        case 'section_5': return "Suggestions/Complaints";
        default: return "General";
    }
}

function callGeminiAPI($question, $context, $apiKey) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;
    
    $prompt = "You are an expert Data Analyst for a University Survey System. 
    Below is the raw feedback data from students (JSON format) for the current active semester:
    
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
?>