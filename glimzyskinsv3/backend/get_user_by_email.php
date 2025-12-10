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

$email = isset($_GET['email']) ? trim($_GET['email']) : (isset($_POST['email']) ? trim($_POST['email']) : '');

if (empty($email)) {
    echo json_encode(['status' => false, 'message' => 'Brak email']);
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT id, email, balance FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['status' => false, 'message' => 'Użytkownik nie istnieje']);
        exit();
    }
    
    echo json_encode([
        'status' => true,
        'user_id' => $user['id'],
        'email' => $user['email'],
        'balance' => (float)$user['balance']
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd: ' . $e->getMessage()
    ]);
}
?>

