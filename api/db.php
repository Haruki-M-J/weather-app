<?php
$host = "localhost";
$dbname = "testdb";
$user = "testuser";
$password = "password";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo "DB接続エラー: " . $e->getMessage();
    exit;
}
?>
