<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$rows = db()->fetchAll('SELECT id, nome, slug, attivo, ordine FROM servizi WHERE attivo = 1 ORDER BY ordine ASC');
echo "Count: " . count($rows) . "\n";
foreach ($rows as $r) {
    echo "{$r['ordine']}. [{$r['id']}] {$r['nome']} ({$r['slug']})\n";
}
