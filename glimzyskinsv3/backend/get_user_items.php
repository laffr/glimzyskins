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
    // Zwracamy surowe dane z inventory (nowy format używany w aplikacji mobilnej)
    $stmt = $pdo->prepare("
        SELECT 
            id,
            user_id,
            item_name,
            item_image,
            item_value,
            acquired_from,
            acquired_at
        FROM inventory
        WHERE user_id = ? AND (is_sold = 0 OR is_sold IS NULL)
        ORDER BY acquired_at DESC
    ");
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => true,
        'items' => $items,
        'count' => count($items)
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd: ' . $e->getMessage()
    ]);
}
?>

