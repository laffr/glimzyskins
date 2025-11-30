<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$user_id = intval($_GET['user_id']);

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();

echo json_encode(["balance" => $balance]);