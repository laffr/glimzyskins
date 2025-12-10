<?php
/**
 * Testowy skrypt do sprawdzenia, czy folder z obrazami istnieje i ile plików PNG zawiera
 */

$imagesPath = 'C:/xampp/htdocs/glimzyskins/skiny/';

echo "<h2>Test importu przedmiotów</h2>";
echo "<pre>";

// Sprawdź czy folder istnieje
if (!is_dir($imagesPath)) {
    echo "❌ BŁĄD: Folder nie istnieje: $imagesPath\n\n";
    echo "Rozwiązanie:\n";
    echo "1. Rozpakuj pliki z skiny.rar do folderu: C:\\xampp\\htdocs\\glimzyskins\\skiny\\\n";
    echo "2. Upewnij się, że wszystkie pliki PNG są w tym folderze (nie w podfolderach)\n";
    exit;
}

echo "✓ Folder istnieje: $imagesPath\n\n";

// Skanuj folder
$files = scandir($imagesPath);
$pngFiles = array_filter($files, function($file) use ($imagesPath) {
    $fullPath = $imagesPath . $file;
    return is_file($fullPath) && pathinfo($file, PATHINFO_EXTENSION) === 'png';
});

echo "Znaleziono " . count($pngFiles) . " plików PNG:\n\n";

$count = 0;
foreach ($pngFiles as $filename) {
    $count++;
    if ($count <= 10) {
        echo "  - $filename\n";
    } elseif ($count == 11) {
        echo "  ... i " . (count($pngFiles) - 10) . " więcej\n";
        break;
    }
}

echo "\n";
echo "========================================\n";
echo "Status: Gotowe do importu!\n";
echo "Aby zaimportować przedmioty, uruchom:\n";
echo "http://localhost/glimzyskins/import_items_auto.php\n";
echo "========================================\n";

echo "</pre>";

