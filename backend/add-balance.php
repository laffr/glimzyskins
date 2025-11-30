<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['amount'])) {
    echo json_encode(["success" => false, "error" => "Brak wymaganych parametrów"]);
    exit;
}

$user_id = intval($data['user_id']);
$amount = floatval($data['amount']);

if ($amount <= 0) {
    echo json_encode(["success" => false, "error" => "Kwota musi być większa niż 0"]);
    exit;
}


$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();

if ($balance === false) {
    echo json_encode(["success" => false, "error" => "Użytkownik nie znaleziony"]);
    exit;
}


$new_balance = $balance + $amount;
$stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
$stmt->execute([$new_balance, $user_id]);

echo json_encode([
    "success" => true,
    "balance" => $new_balance
]);
?>