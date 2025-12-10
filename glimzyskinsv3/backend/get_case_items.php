<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db.php';

$caseId = isset($_GET['case_id']) ? (int)$_GET['case_id'] : 0;

if (empty($caseId)) {
    echo json_encode(['status' => false, 'message' => 'Brak case_id']);
    exit();
}

try {
    /**
     * Dump v5 ma kolumnę image_path, ale wcześniejsze wersje miały kolumnę image.
     * Sprawdzamy, która kolumna istnieje, i budujemy zapytanie dynamicznie,
     * żeby uniknąć błędu 1054 "Unknown column 'image_path' in 'field list'".
     */
    $imageColumn = 'image_path';
    $checkImagePath = $pdo->query("SHOW COLUMNS FROM case_items LIKE 'image_path'")->fetch();
    if (!$checkImagePath) {
        $checkImage = $pdo->query("SHOW COLUMNS FROM case_items LIKE 'image'")->fetch();
        if ($checkImage) {
            $imageColumn = 'image';
        }
    }

    // Wartość przedmiotu – w dumpie v5 kolumna nazywa się value, w starszych price
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
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => true,
        'case_id' => $caseId,
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

