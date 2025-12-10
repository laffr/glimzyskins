<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// === Połączenie z bazą ===
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "glimzyskins";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Błąd połączenia z bazą: " . $conn->connect_error
    ]);
    exit;
}

$conn->set_charset("utf8mb4");

// === Pobranie danych JSON ===
$data = json_decode(file_get_contents("php://input"), true);

$username = $data["username"] ?? "";
$password = $data["password"] ?? "";

// === Walidacja ===
if (!$username || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Uzupełnij wszystkie pola"
    ]);
    exit;
}

// === Pobranie admina ===
$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "success" => false,
        "message" => "Nieprawidłowy login"
    ]);
    exit;
}

$admin = $result->fetch_assoc();

// === Weryfikacja hasła (bez hashowania) ===
if ($password !== $admin["password"]) {
    echo json_encode([
        "success" => false,
        "message" => "Nieprawidłowe hasło"
    ]);
    exit;
}

// === Sukces ===
echo json_encode([
    "success" => true,
    "admin" => [
        "id" => $admin["id"],
        "username" => $admin["username"]
    ]
]);