<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

$mysqli = new mysqli("localhost", "root", "", "glimzyskins");

if ($mysqli->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Błąd połączenia z bazą: " . $mysqli->connect_error
    ]);
    exit;
}

$mysqli->set_charset("utf8mb4");

/*
    ⚠️ DOPASUJ TYLKO TĘ LINIJKĘ DO SWOJEJ TABELI
    Jeśli masz np. "users" / "uzytkownicy" itp.
*/
$query = "SELECT id, username, email, balance, created_at FROM users ORDER BY id DESC";

$result = $mysqli->query($query);

$users = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode([
    "success" => true,
    "users" => $users
]);