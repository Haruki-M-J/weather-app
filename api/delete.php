<?php
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["id"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "id required"
    ]);
    exit;
}

$id = $data["id"];

try {
    $sql = "DELETE FROM weather_notes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

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
