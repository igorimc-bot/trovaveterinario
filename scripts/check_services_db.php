<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "Reading services from DB...\n";
try {
    $services = db()->fetchAll('SELECT * FROM servizi');
    foreach ($services as $s) {
        $status = isset($s['attivo']) ? $s['attivo'] : 'undefined';
        echo "ID: {$s['id']} | Name: {$s['nome']} | Slug: {$s['slug']} | Attivo: {$status}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
