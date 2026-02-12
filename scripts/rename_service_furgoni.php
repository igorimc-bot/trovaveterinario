<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $db = db();

    $slug = 'furgoni-veicoli-commerciali';
    $newName = "Furgoni e Veicoli Commerciali all'Asta";

    // Check if it exists
    $service = $db->fetchOne("SELECT * FROM servizi WHERE slug = ?", [$slug]);

    if ($service) {
        $db->update('servizi', ['nome' => $newName], 'id = :id', ['id' => $service['id']]);
        echo "Service updated successfully: '{$newName}'\n";
    } else {
        echo "Service with slug '{$slug}' not found.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
