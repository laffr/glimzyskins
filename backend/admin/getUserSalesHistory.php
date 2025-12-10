<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$mysqli = new mysqli("localhost", "root", "", "glimzyskins");

if ($mysqli->connect_errno) {
    echo json_encode(["error" => "db connect error"]);
    exit;
}

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Missing user_id"]);
    exit;
}

$user_id = intval($_GET['user_id']);

$query = $mysqli->prepare("
    SELECT 
        id,
        user_id,
        action_type,
        item_name,
        item_value,
        amount,
        created_at
    FROM history
    WHERE user_id = ?
    AND (action_type = 'sell_item' OR action_type = 'sell_won_item')
    ORDER BY created_at DESC
");
$query->bind_param("i", $user_id);
$query->execute();
$res = $query->get_result();

$history = [];

while ($r = $res->fetch_assoc()) {
    $value = 0;
    if (!empty($r['item_value'])) $value = floatval($r['item_value']);
    elseif (!empty($r['amount'])) $value = floatval($r['amount']);

    $history[] = [
        "id" => (int)$r['id'],
        "action_type" => $r['action_type'],
        "item_name" => $r['item_name'],
        "amount" => $value,
        "created_at" => $r['created_at'],
    ];
}

echo json_encode(["history" => $history]);
exit;