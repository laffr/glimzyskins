<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$dbname = "glimzyskins";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Błąd połączenia z bazą", "details" => $e->getMessage()]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$username = trim($data["username"] ?? "");
$password = trim($data["password"] ?? "");
$birth_date = trim($data["date_of_birth"] ?? "");

if (!$email || !$username || !$password || !$birth_date) {
    echo json_encode(["success" => false, "message" => "Wszystkie pola są wymagane"]);
    exit;
}

// Sprawdź czy email już istnieje
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "Email jest już zajęty"]);
    exit;
}

// Sprawdź czy username już istnieje
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->rowCount() > 0) {
    echo json_encode(["success" => false, "message" => "Nazwa użytkownika jest zajęta"]);
    exit;
}


$hashedPassword = password_hash($password, PASSWORD_BCRYPT);


$stmt = $pdo->prepare("
    INSERT INTO users (email, username, password, birth_date, created_at)
    VALUES (?, ?, ?, ?, NOW())
");
if ($stmt->execute([$email, $username, $hashedPassword, $birth_date])) {
    $id = $pdo->lastInsertId();

    
    $stmt = $pdo->prepare("SELECT id, email, username, birth_date AS date_of_birth FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "message" => "Rejestracja zakończona sukcesem",
        "user" => $user
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Błąd podczas zapisu do bazy"]);
}