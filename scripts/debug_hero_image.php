<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$pdo = db()->getConnection();
$slug = 'furgoni-veicoli-commerciali-all-asta';
$stmt = $pdo->prepare("SELECT * FROM servizi WHERE slug = ?");
$stmt->execute([$slug]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Slug: " . $service['slug'] . "\n";
echo "Image Path: " . $service['immagine'] . "\n";
echo "File Exists (relative to public): " . (file_exists(__DIR__ . '/../public' . $service['immagine']) ? 'YES' : 'NO') . "\n";
echo "Full path check: " . __DIR__ . '/../public' . $service['immagine'] . "\n";
