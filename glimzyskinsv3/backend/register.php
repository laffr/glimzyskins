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

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'status' => false,
        'message' => 'Nieprawidłowy format email'
    ]);
    exit();
}

try {
    // Sprawdź czy użytkownik już istnieje
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        echo json_encode([
            'status' => false,
            'message' => 'Użytkownik o tym emailu już istnieje'
        ]);
        exit();
    }
    
    // Hashuj hasło
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Wygeneruj prostą nazwę użytkownika na podstawie emaila (wymagane pole w nowej bazie)
    $baseUsername = strstr($email, '@', true) ?: 'user';
    $username = $baseUsername . '_' . substr(md5(uniqid('', true)), 0, 6);
    
    // Domyślna data urodzenia, bo pole jest wymagane w nowej bazie
    $birthDate = '2000-01-01';
    
    // Wstaw nowego użytkownika
    $stmt = $pdo->prepare("
        INSERT INTO users (email, username, password, balance, birth_date, created_at) 
        VALUES (?, ?, ?, 1000.00, ?, NOW())
    ");
    $stmt->execute([$email, $username, $hashedPassword, $birthDate]);
    $userId = $pdo->lastInsertId();
    
    echo json_encode([
        'status' => true,
        'message' => 'Rejestracja zakończona pomyślnie',
        'user_id' => (int)$userId,
        'email' => $email,
        'balance' => 1000.00
    ]);
    
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd podczas rejestracji: ' . $e->getMessage()
    ]);
}
?>

