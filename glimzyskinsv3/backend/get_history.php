<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db.php';

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : (isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0);

if (empty($userId)) {
    echo json_encode(['status' => false, 'message' => 'Brak user_id']);
    exit();
}

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
    $stmt->execute([$userId]);
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

    echo json_encode([
        'status' => true,
        'history' => $history
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd: ' . $e->getMessage()
    ]);
}
?>
