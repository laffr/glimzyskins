<?php
require_once "cors.php";
require_once "db.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["user_id"]) || !isset($data["items"])) {
    echo json_encode(["success" => false, "error" => "Invalid data"]);
    exit;
}

$user_id = intval($data["user_id"]);
$items = $data["items"];

$pdo->beginTransaction();

try {
    foreach ($items as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO inventory (user_id, item_name, item_image, item_value, acquired_from)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user_id,
            $item["name"],
            $item["image_path"],
            floatval($item["value"]),
            $item["case_name"] ?? "unknown"
        ]);
    }

    $pdo->commit();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}