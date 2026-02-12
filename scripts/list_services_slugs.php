<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$rows = db()->fetchAll('SELECT id, nome, slug, attivo FROM servizi ORDER BY id ASC');
foreach ($rows as $r) {
    echo "ID: {$r['id']} | Slug: {$r['slug']} | Active: {$r['attivo']}\n";
}
