<?php
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed"
    ]);
    exit;
}

$city = isset($_GET["city"]) ? trim((string) $_GET["city"]) : "";
$apiKey = getenv("OPENWEATHER_API_KEY");

if ($city === "") {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "City is required"
    ]);
    exit;
}

if ($apiKey === false || trim($apiKey) === "") {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Weather API key is missing"
    ]);
    exit;
}

$url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . urlencode($apiKey) . "&units=metric";
$context = stream_context_create([
    "http" => [
        "ignore_errors" => true,
        "timeout" => 10
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    http_response_code(502);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch weather data"
    ]);
    exit;
}

$data = json_decode($response, true);

if (!is_array($data)) {
    http_response_code(502);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid response from weather service"
    ]);
    exit;
}

if (($data["cod"] ?? null) !== 200 || empty($data["weather"][0]["description"])) {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "都市が見つかりません"
    ]);
    exit;
}

echo json_encode([
    "status" => "ok",
    "weather" => $data["weather"][0]["description"]
]);
?>
