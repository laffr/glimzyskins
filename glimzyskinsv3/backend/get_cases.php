
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

try {
    // Dopasowanie do różnych schematów: image_path vs image, is_active opcjonalne.
    $imageColumn = 'image_path';
    $checkImagePath = $pdo->query("SHOW COLUMNS FROM cases LIKE 'image_path'")->fetch();
    if (!$checkImagePath) {
        $checkImage = $pdo->query("SHOW COLUMNS FROM cases LIKE 'image'")->fetch();
        if ($checkImage) {
            $imageColumn = 'image';
        }
    }

    $isActiveSelect = '1 AS is_active';
    $checkActive = $pdo->query("SHOW COLUMNS FROM cases LIKE 'is_active'")->fetch();
    if ($checkActive) {
        $isActiveSelect = 'is_active';
    }

    $stmt = $pdo->query("
        SELECT 
            id, 
            name, 
            description, 
            {$imageColumn} AS image_path, 
            price, 
            {$isActiveSelect}
        FROM cases
        ORDER BY id
    ");
    $cases = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => true,
        'cases' => $cases
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd: ' . $e->getMessage()
    ]);
}
?>

