<?php
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data["id"];

$sql = "DELETE FROM weather_notes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

echo json_encode(["status" => "ok"]);
?>
