<?php
header('Content-Type: application/json');

$contentData = json_decode(file_get_contents(__DIR__ . '/data/content.json'), true);

// Load env variables
$env = file_exists(__DIR__ . '/.env') ? parse_ini_file(__DIR__ . '/.env') : [];

// DeepSeek API Configuration
$apiKey = $env['DEEPSEEK_API_KEY'] ?? ($contentData['chatbot']['api_key'] ?? '');
$modelId = $contentData['chatbot']['model_id'] ?? 'deepseek-chat';
$systemBasePrompt = $contentData['chatbot']['system_prompt'] ?? 'You are a helpful assistant.';

// Log input for debugging
// file_put_contents('debug.log', "Input: " . file_get_contents('php://input') . "\n", FILE_APPEND);

define('DEEPSEEK_API_URL', 'https://api.deepseek.com/chat/completions');

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';
$lang = $input['lang'] ?? 'en';

if (empty($apiKey)) {
    echo json_encode(['reply' => 'Chatbot belum dikonfigurasi. Silakan isi API Key di halaman Admin.']);
    exit;
}

if (empty($userMessage)) {
    echo json_encode(['error' => 'Empty message']);
    exit;
}

// System Prompt
$cvContext = json_encode($contentData['id']);
$systemPrompt = $systemBasePrompt . " Current language mode: " . ($lang == 'id' ? 'Indonesian' : 'English') . ". Answer accordingly. Here is Rizky Fauzi's CV data context to base your answers on: " . $cvContext;

$data = [
    'model' => $modelId,
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userMessage]
    ],
    'temperature' => 0.7
];

// Use file_get_contents with stream context as fallback for cURL
function curl_replacement($url, $headers, $payload) {
    $headerStr = "";
    foreach ($headers as $h) {
        $headerStr .= $h . "\r\n";
    }
    
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => $headerStr,
            'content' => $payload,
            'ignore_errors' => true,
            'timeout' => 30
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false
        ]
    ]);
    
    $result = file_get_contents($url, false, $context);
    
    $status = 500;
    if (isset($http_response_header) && count($http_response_header) > 0) {
        $status_line = $http_response_header[0];
        if (preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match)) {
            $status = $match[1];
        }
    }
    
    return ['response' => $result, 'status' => $status];
}

if (!function_exists('curl_init')) {
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ];
    
    $res = curl_replacement(DEEPSEEK_API_URL, $headers, json_encode($data));
    
    $response = $res['response'];
    $httpCode = (int)$res['status'];
    $curlError = ($response === false) ? "Stream context failed" : "";
} else {
    $ch = curl_init(DEEPSEEK_API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
}

if ($curlError) {
    echo json_encode(['reply' => 'CURL Error: ' . $curlError]);
} elseif ($httpCode !== 200) {
    $errData = json_decode($response, true);
    $errMsg = $errData['error']['message'] ?? 'HTTP Error ' . $httpCode;
    echo json_encode(['reply' => 'API Error: ' . $errMsg]);
} else {
    $result = json_decode($response, true);
    $botReply = $result['choices'][0]['message']['content'] ?? 'Maaf, saya tidak mendapatkan respon dari AI.';
    echo json_encode(['reply' => $botReply]);
}
