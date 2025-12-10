<?php
// Wyswietlanie bledow
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

// Polaczenie z baza
$mysqli = new mysqli("localhost", "root", "", "glimzyskins");

if ($mysqli->connect_errno) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed",
        "error" => $mysqli->connect_error
    ]);
    exit;
}

// Pobierz ID
if (!isset($_GET['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing user ID"
    ]);
    exit;
}

$id = intval($_GET['id']);

// Pobierz użytkownika + created_at
$query = $mysqli->prepare("
    SELECT id, email, username, created_at
    FROM users
    WHERE id = ?
");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
    exit;
}

// Zwroc dane
echo json_encode([
    "success" => true,
    "user" => $user
]);
exit;