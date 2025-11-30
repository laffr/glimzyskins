<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$user_id = intval($_GET['user_id']);

try {
    $stmt = $pdo->prepare("
        SELECT 
            id,
            user_id,
            action_type,
            item_name,
            item_value,
            amount,
            created_at
        FROM history
        WHERE user_id = ?
        AND (action_type = 'sell_item' OR action_type = 'sell_won_item')
        ORDER BY created_at DESC
    ");
    $stmt->execute([$user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $history = array_map(function($r) {
        $value = 0;
        if (!empty($r['item_value'])) $value = floatval($r['item_value']);
        elseif (!empty($r['amount'])) $value = floatval($r['amount']);

        return [
            "id" => (int)$r['id'],
            "action_type" => $r['action_type'],
            "item_name" => $r['item_name'],
            "amount" => $value,
            "created_at" => $r['created_at'],
        ];
    }, $rows);

    echo json_encode(["history" => $history]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Failed to load history",
        "details" => $e->getMessage()
    ]);
    exit;
}