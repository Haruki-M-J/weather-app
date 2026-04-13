<?php
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$city = $data["city"];
$weather = $data["weather"];
$memo = $data["memo"];

$sql = "INSERT INTO weather_notes (city, weather, memo) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$city, $weather, $memo]);

echo json_encode(["status" => "ok"]);
?>
