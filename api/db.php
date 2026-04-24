<?php
header("Content-Type: application/json");

function getEnvValue($key)
{
    $value = getenv($key);
    return $value === false ? null : $value;
}

$host = getEnvValue("DB_HOST");
$port = getEnvValue("DB_PORT") ?? "5432";
$dbname = getEnvValue("DB_NAME");
$user = getEnvValue("DB_USER");
$password = getEnvValue("DB_PASSWORD");

if (!$host || !$dbname || !$user || $password === null) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database configuration is missing"
    ]);
    exit;
}

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "DB connection failed"
    ]);
    exit;
}
?>
