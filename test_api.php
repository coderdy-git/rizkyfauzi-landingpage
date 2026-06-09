<?php
$contentData = json_decode(file_get_contents(__DIR__ . '/data/content.json'), true);
$apiKey = $contentData['chatbot']['api_key'];
$model = $contentData['chatbot']['model_id'];

echo "Testing Connection...\n";
echo "Model: $model\n";
echo "API Key: " . (empty($apiKey) ? "EMPTY" : "EXISTS (Starts with " . substr($apiKey, 0, 5) . ")") . "\n\n";

$data = [
    'model' => $model,
    'messages' => [['role' => 'user', 'content' => 'hi']],
];

$ch = curl_init('https://api.deepseek.com/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Bypass SSL untuk testing jika CA bundle bermasalah

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo "CURL ERROR: " . $curlError . "\n";
} else {
    echo "HTTP CODE: " . $httpCode . "\n";
    echo "RESPONSE: " . $response . "\n";
}
