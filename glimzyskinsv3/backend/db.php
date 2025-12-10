<?php
$host = 'localhost';          // host XAMPP
$dbname = 'glimzyskins';      // nazwa nowej bazy z dumpa
$username = 'root';           // domyślnie w XAMPP
$password = '';               // domyślnie brak hasła w XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'Błąd połączenia z bazą danych: ' . $e->getMessage()
    ]);
    exit();
}
?>

