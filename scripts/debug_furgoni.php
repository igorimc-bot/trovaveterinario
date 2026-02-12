<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$pdo = Database::getInstance()->getConnection();
$id = 9;
$stmt = $pdo->prepare("SELECT * FROM servizi WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if ($service) {
    echo "ID: " . $service['id'] . "\n";
    echo "Slug: " . $service['slug'] . "\n";
    echo "Image: " . $service['immagine'] . "\n";

    $fullPath = __DIR__ . '/../public' . $service['immagine'];
    echo "Full Path: " . $fullPath . "\n";
    echo "Exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
} else {
    echo "Service ID $id not found.\n";
}
