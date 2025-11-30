<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["user_id"]) || !isset($data["amount"])) {
    echo json_encode(["success" => false, "error" => "Missing parameters"]);
    exit;
}

$user_id = intval($data["user_id"]);
$amount = floatval($data["amount"]);

$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();

if ($balance === false) {
    echo json_encode(["success" => false, "error" => "User not found"]);
    exit;
}

if ($balance < $amount) {
    echo json_encode(["success" => false, "error" => "Insufficient balance"]);
    exit;
}

$new_balance = $balance - $amount;

$stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
$stmt->execute([$new_balance, $user_id]);

echo json_encode(["success" => true, "balance" => $new_balance]);