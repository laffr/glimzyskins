<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT id, name, description, price,image FROM cases");
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["cases" => $cases]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "DB error", "details" => $e->getMessage()]);
}