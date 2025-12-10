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
$caseId = isset($_POST['case_id']) ? (int)$_POST['case_id'] : 0;

if (empty($userId) || empty($caseId)) {
    echo json_encode(['status' => false, 'message' => 'Brak wymaganych parametrów']);
    exit();
}

try {
    $pdo->beginTransaction();
    
    // Sprawdź saldo użytkownika
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        throw new Exception('Użytkownik nie istnieje');
    }
    
    // Pobierz cenę skrzynki (nowa tabela nie ma is_active)
    $stmt = $pdo->prepare("SELECT price, name FROM cases WHERE id = ?");
    $stmt->execute([$caseId]);
    $case = $stmt->fetch();
    
    if (!$case) {
        throw new Exception('Skrzynka nie istnieje lub jest nieaktywna');
    }
    
    $casePrice = (float)$case['price'];
    
    if ($user['balance'] < $casePrice) {
        throw new Exception('Niewystarczające saldo');
    }
    
    // Pobierz wszystkie przedmioty z skrzynki z szansami.
    // Obsługujemy obie wersje schematu (image_path/value oraz image/price).
    $imageColumn = 'image_path';
    $checkImagePath = $pdo->query("SHOW COLUMNS FROM case_items LIKE 'image_path'")->fetch();
    if (!$checkImagePath) {
        $checkImage = $pdo->query("SHOW COLUMNS FROM case_items LIKE 'image'")->fetch();
        if ($checkImage) {
            $imageColumn = 'image';
        }
    }

    $priceColumn = 'value';
    $checkValue = $pdo->query("SHOW COLUMNS FROM case_items LIKE 'value'")->fetch();
    if (!$checkValue) {
        $checkPrice = $pdo->query("SHOW COLUMNS FROM case_items LIKE 'price'")->fetch();
        if ($checkPrice) {
            $priceColumn = 'price';
        }
    }

    $stmt = $pdo->prepare("
        SELECT 
            id,
            name,
            '' AS weapon_type,
            {$imageColumn} AS image_path,
            {$priceColumn} AS price,
            NULL AS rarity_id,
            'Standard' AS rarity_name,
            '#FFFFFF' AS rarity_color,
            chance AS chance_percent
        FROM case_items
        WHERE case_id = ?
        ORDER BY chance DESC, id ASC
    ");
    $stmt->execute([$caseId]);
    $items = $stmt->fetchAll();
    
    if (empty($items)) {
        throw new Exception('Skrzynka jest pusta');
    }
    
    // Losuj przedmiot na podstawie szans
    $random = mt_rand(1, 10000) / 100; // 0.01 - 100.00
    $cumulative = 0;
    $selectedItem = null;
    
    foreach ($items as $item) {
        $cumulative += (float)$item['chance_percent'];
        if ($random <= $cumulative) {
            $selectedItem = $item;
            break;
        }
    }
    
    // Jeśli nie wybrano (błąd w obliczeniach), weź pierwszy
    if (!$selectedItem) {
        $selectedItem = $items[0];
    }
    
    // Odejmij saldo
    $newBalance = $user['balance'] - $casePrice;
    $stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->execute([$newBalance, $userId]);
    
    // Dodaj przedmiot do inwentarza użytkownika
    $stmt = $pdo->prepare("
        INSERT INTO inventory (user_id, item_name, item_image, item_value, acquired_from) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $userId,
        $selectedItem['name'],
        $selectedItem['image_path'],
        $selectedItem['price'],
        $case['name']
    ]);
    $userItemId = $pdo->lastInsertId();
    
    // Zapisz historię (open_case + dodanie do inwentarza)
    $historyStmt = $pdo->prepare("
        INSERT INTO history (user_id, action_type, item_name, item_value, case_name, amount) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $historyStmt->execute([$userId, 'open_case', null, null, $case['name'], $casePrice]);
    $historyStmt->execute([$userId, 'add_to_inventory', $selectedItem['name'], $selectedItem['price'], $case['name'], null]);
    
    $pdo->commit();
    
    echo json_encode([
        'status' => true,
        'item' => [
            'id' => $selectedItem['id'],
            'name' => $selectedItem['name'],
            'weapon_type' => $selectedItem['weapon_type'],
            'image_path' => $selectedItem['image_path'],
            'rarity' => [
                'id' => $selectedItem['rarity_id'],
                'name' => $selectedItem['rarity_name'],
                'color' => $selectedItem['rarity_color']
            ],
            'price' => $selectedItem['price']
        ],
        'new_balance' => $newBalance,
        'user_item_id' => $userItemId
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

