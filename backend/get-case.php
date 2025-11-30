<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Missing case id"]);
    exit;
}

$case_id = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT * FROM cases WHERE id = ?");
$stmt->execute([$case_id]);
$case = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$case) {
    echo json_encode(["error" => "Case not found"]);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM case_items WHERE case_id = ?");
$stmt->execute([$case_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "case" => $case,
    "items" => $items
]);