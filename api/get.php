<?php
header("Content-Type: application/json");
require "db.php";

try {
    $sql = "SELECT * FROM weather_notes ORDER BY id DESC";
    $stmt = $conn->query($sql);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>
