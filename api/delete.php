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

if (!$data || !isset($data["id"])) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "id required"
    ]);
    exit;
}

$id = filter_var($data["id"], FILTER_VALIDATE_INT);

if ($id === false || $id <= 0) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Invalid id"
    ]);
    exit;
}

try {
    $sql = "DELETE FROM weather_notes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Data not found"
        ]);
        exit;
    }

    echo json_encode([
        "status" => "ok"
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete data"
    ]);
}
?>
