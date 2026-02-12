<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$services = db()->fetchAll("SELECT id, nome, slug, meta_title FROM servizi WHERE attivo = 1");

$missing = 0;
foreach ($services as $s) {
    if (empty($s['meta_title'])) {
        echo "[MISSING] ID: {$s['id']} | Name: {$s['nome']}\n";
        $missing++;
    }
}

if ($missing === 0) {
    echo "ALL OK\n";
}
