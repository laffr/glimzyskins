<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['user_id']) || !isset($data['items'])) {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
    exit;
}

$user_id = intval($data['user_id']);
$items = $data['items'];

if (count($items) === 0) {
    echo json_encode(["success" => false, "error" => "No items provided"]);
    exit;
}

$pdo->beginTransaction();

try {
    $total = 0;

    foreach ($items as $item) {
        $id = intval($item['id']);

        
        $stmt = $pdo->prepare("
            SELECT item_value, item_name 
            FROM inventory 
            WHERE id = ? AND user_id = ? AND is_sold = 0
        ");
        $stmt->execute([$id, $user_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) continue;

        $value = floatval($row['item_value']);
        $total += $value;

        
        $pdo->prepare("
            UPDATE inventory 
            SET is_sold = 1, sold_at = NOW() 
            WHERE id = ?
        ")->execute([$id]);

        
        $pdo->prepare("
            INSERT INTO history (user_id, action_type, item_name, item_value, amount)
            VALUES (?, 'sell_item', ?, ?, ?)
        ")->execute([$user_id, $row['item_name'], $value, $value]);
    }

    
    $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
        ->execute([$total, $user_id]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "total_value" => $total
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}