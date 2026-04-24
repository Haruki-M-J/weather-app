<?php
header("Content-Type: application/json");
require "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed"
    ]);
    exit;
}

try {
    $sql = "SELECT * FROM weather_notes ORDER BY id DESC";
    $stmt = $conn->query($sql);

    $data = $stmt->fetchAll();

    echo json_encode($data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to fetch data"
    ]);
}
?>
