<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$pdo = db()->getConnection();
$stmt = $pdo->query("SELECT id, nome, slug, immagine FROM servizi");
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total services: " . count($services) . "\n";
foreach ($services as $s) {
    $hasImg = !empty($s['immagine']) ? 'YES' : 'NO';
    echo "ID: {$s['id']} | Slug: {$s['slug']} | HasImg: {$hasImg} | Path: {$s['immagine']}\n";
}
