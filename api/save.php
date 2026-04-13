<?php
header("Content-Type: application/json");
require "db.php";

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

$city = $data["city"];
$weather = $data["weather"];
$memo = $data["memo"];

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
        "message" => $e->getMessage()
    ]);
}
?>
