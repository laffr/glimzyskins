<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Metoda nie dozwolona']);
    exit();
}

require_once 'db.php';

$userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$userItemId = isset($_POST['user_item_id']) ? (int)$_POST['user_item_id'] : 0;

if (empty($userId) || empty($userItemId)) {
    echo json_encode(['status' => false, 'message' => 'Brak wymaganych parametrów']);
    exit();
}

try {
    $pdo->beginTransaction();
    
    // Sprawdź czy przedmiot należy do użytkownika w nowej tabeli inventory
    $stmt = $pdo->prepare("
        SELECT id, item_name, item_value, is_sold 
        FROM inventory 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$userItemId, $userId]);
    $userItem = $stmt->fetch();
    
    if (!$userItem) {
        throw new Exception('Przedmiot nie istnieje lub nie należy do użytkownika');
    }
    
    if (!empty($userItem['is_sold'])) {
        throw new Exception('Przedmiot został już sprzedany');
    }
    
    $itemPrice = (float)$userItem['item_value'];
    $sellPrice = $itemPrice; // w nowej bazie sprzedajemy za pełną wartość z inventory
    
    // Oznacz przedmiot jako sprzedany
    $stmt = $pdo->prepare("UPDATE inventory SET is_sold = 1, sold_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
    $stmt->execute([$userItemId, $userId]);
    
    // Dodaj saldo
    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$sellPrice, $userId]);
    
    // Pobierz nowe saldo
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    // Zapisz transakcję
    $stmt = $pdo->prepare("
        INSERT INTO history (user_id, action_type, item_name, item_value, amount, case_name) 
        VALUES (?, 'sell_item', ?, ?, ?, NULL)
    ");
    $stmt->execute([$userId, $userItem['item_name'], $userItem['item_value'], $sellPrice]);
    
    $pdo->commit();
    
    echo json_encode([
        'status' => true,
        'message' => 'Przedmiot sprzedany',
        'sold_price' => $sellPrice,
        'new_balance' => $user['balance']
    ]);
    
} catch(Exception $e) {
    $pdo->rollBack();
    http_response_code(400);
    echo json_encode([
        'status' => false,
        'message' => $e->getMessage()
    ]);
}
?>

