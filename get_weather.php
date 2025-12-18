<?php
header('Content-Type: application/json');

$city = $_GET['city'] ?? '';

if (!$city) {
    echo json_encode(["error" => "No city provided"]);
    exit;
}

$apiKey = "0cd07545220de47cbeaad5fbf10356da";
$url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&units=metric&appid=" . $apiKey;

// Use cURL instead of file_get_contents for better compatibility
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'TravelAdda/1.0');

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($response === false || $curlError) {
    echo json_encode([
        "error" => "Failed to fetch weather data",
        "details" => $curlError ?: "cURL error",
        "city" => $city
    ]);
    exit;
}

if ($httpCode !== 200) {
    echo json_encode([
        "error" => "HTTP Error: " . $httpCode,
        "city" => $city
    ]);
    exit;
}

$data = json_decode($response, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "error" => "Invalid JSON response",
        "city" => $city
    ]);
    exit;
}

if (isset($data['main']['temp'])) {
    echo json_encode([
        "temp" => $data['main']['temp'],
        "description" => $data['weather'][0]['description'],
        "icon" => $data['weather'][0]['icon']
    ]);
} else {
    echo json_encode([
        "error" => "Invalid city or no data",
        "city" => $city
    ]);
}
?>
