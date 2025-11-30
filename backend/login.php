<?php
require_once "cors.php";

$host = "localhost";
$dbname = "glimzyskins";
$user = "root";
$pass = "";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

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
    echo json_encode(["success" => false, "message" => "DB connection failed", "details" => $e->getMessage()]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data["email"] ?? "");
$password = trim($data["password"] ?? "");

if (!$email || !$password) {
    echo json_encode(["success" => false, "message" => "Wypełnij wszystkie pola"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, email, username, password FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "Nie znaleziono użytkownika o podanym emailu"]);
        exit;
    }

    if (!password_verify($password, $user["password"])) {
        echo json_encode(["success" => false, "message" => "Nieprawidłowe hasło"]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "message" => "Zalogowano pomyślnie",
        "user" => [
            "id" => $user["id"],
            "email" => $user["email"],
            "username" => $user["username"]
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Błąd serwera", "details" => $e->getMessage()]);
    exit;
}