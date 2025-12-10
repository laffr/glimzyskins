<?php
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Połączenie
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "glimzyskins";

$conn = new mysqli($host, $user, $pass, $dbname);

if($conn->connect_error){
    echo json_encode(["success"=>false, "message"=>"Błąd połączenia"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$id = $data["id"] ?? 0;

$stmt = $conn->prepare("SELECT id, username FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows === 0){
    echo json_encode(["success"=>false, "message"=>"Admin nie istnieje"]);
    exit;
}

$admin = $res->fetch_assoc();

echo json_encode([
    "success" => true,
    "admin" => $admin
]);