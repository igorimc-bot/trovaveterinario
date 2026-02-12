<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $db = db();

    $slug = 'furgoni-veicoli-commerciali';

    // Check if it exists
    $service = $db->fetchOne("SELECT * FROM servizi WHERE slug = ?", [$slug]);

    if ($service) {
        echo "Service already exists: " . $service['nome'] . "\n";
    } else {
        echo "Service not found. Inserting...\n";

        $data = [
            'nome' => 'Furgoni e Veicoli Commerciali',
            'slug' => $slug,
            'categoria' => 'veicoli',
            'descrizione_breve' => 'Aste giudiziarie di furgoni, camion, autocarri e veicoli commerciali. Mezzi da lavoro a prezzi d\'occasione da fallimenti.',
            'attivo' => 1,
            'ordine' => 10
        ];

        $db->insert('servizi', $data);

        echo "Service '{$data['nome']}' inserted successfully with slug '{$slug}'.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
