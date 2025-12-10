<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$mysqli = new mysqli("localhost", "root", "", "glimzyskins");

if ($mysqli->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Błąd połączenia: " . $mysqli->connect_error
    ]);
    exit;
}

$mysqli->set_charset("utf8mb4");

if (!isset($_POST['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Brak ID użytkownika"
    ]);
    exit;
}

$id = intval($_POST['id']);

$query = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$query->bind_param("i", $id);

if ($query->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Użytkownik został usunięty"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Błąd podczas usuwania"
    ]);
}