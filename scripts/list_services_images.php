<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$rows = db()->fetchAll("SELECT id, slug, immagine FROM servizi");
foreach ($rows as $r) {
    echo "ID: {$r['id']} | Slug: {$r['slug']} | Image: {$r['immagine']}\n";
}
