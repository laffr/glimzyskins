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
    echo json_encode([
        'status' => false,
        'message' => 'Metoda nie dozwolona'
    ]);
    exit();
}

require_once 'db.php';

// Retrofit wysyła dane jako form-urlencoded, więc używamy $_POST
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Jeśli nie ma w $_POST, spróbuj JSON (dla testów)
if (empty($email) && empty($password)) {
    $data = json_decode(file_get_contents('php://input'), true);
    if ($data) {
        $email = isset($data['email']) ? trim($data['email']) : '';
        $password = isset($data['password']) ? trim($data['password']) : '';
    }
}

if (empty($email) || empty($password)) {
    echo json_encode([
        'status' => false,
        'message' => 'Email i hasło są wymagane'
    ]);
    exit();
}

try {
    // Znajdź użytkownika
    $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo json_encode([
            'status' => false,
            'message' => 'Nieprawidłowy email lub hasło'
        ]);
        exit();
    }
    
    // Weryfikuj hasło
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            'status' => true,
            'message' => 'Logowanie zakończone pomyślnie'
        ]);
    } else {
        echo json_encode([
            'status' => false,
            'message' => 'Nieprawidłowy email lub hasło'
        ]);
    }
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd podczas logowania: ' . $e->getMessage()
    ]);
}
?>

