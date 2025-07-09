<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$text = $data['text'] ?? '';
$from = $data['source_lang'] ?? 'rus_Cyrl';
$to   = $data['target_lang'] ?? 'uzn_Cyrl';

$payload = json_encode([
    'text' => $text,
    'source_lang' => $from,
    'target_lang' => $to,
    'model' => 'sayqalchi'
]);

$ch = curl_init("https://websocket.tahrirchi.uz/translate-v2");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer th_2dfbcce1-d80d-4922-a015-531529541ee4" // ← вставь свой ключ
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
