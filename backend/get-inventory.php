<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$user_id = intval($_GET['user_id']);

$stmt = $pdo->prepare("
    SELECT id, user_id, item_name, item_image, item_value, acquired_from, acquired_at 
    FROM inventory 
    WHERE user_id = ? AND is_sold = 0
    ORDER BY acquired_at DESC
");
$stmt->execute([$user_id]);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["items" => $items]);