<?php
$host = "localhost";
$dbname = "testdb";
$user = "testuser";
$password = "abc";

try {
    $conn = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    http_response_code(500);
    header("Content-Type: application/json");

    echo json_encode([
        "status" => "error",
        "message" => "DB connection failed"
    ]);
    exit;
}
?>
