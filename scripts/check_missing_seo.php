<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$services = db()->fetchAll("SELECT id, nome, slug, meta_title FROM servizi WHERE attivo = 1");

echo "Checking " . count($services) . " active services...\n";

foreach ($services as $s) {
    if (empty($s['meta_title'])) {
        echo "[MISSING] ID: {$s['id']} | Name: {$s['nome']} ({$s['slug']})\n";
    }
}
