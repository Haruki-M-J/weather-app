<?php
header("Content-Type: application/json");
require "db.php";

$sql = "SELECT * FROM weather_notes ORDER BY id DESC";
$stmt = $conn->query($sql);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>

