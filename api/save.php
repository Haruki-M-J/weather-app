<?php
header("Content-Type: application/json");
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed"
    ]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid JSON"
    ]);
    exit;
}

if (!isset($data["city"], $data["weather"], $data["memo"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Missing fields"
    ]);
    exit;
}

$city = trim((string) $data["city"]);
$weather = trim((string) $data["weather"]);
$memo = trim((string) $data["memo"]);

if ($city === "" || $weather === "") {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "City and weather are required"
    ]);
    exit;
}

try {
    $sql = "INSERT INTO weather_notes (city, weather, memo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$city, $weather, $memo]);

    echo json_encode([
        "status" => "ok"
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to save data"
    ]);
}
?>
