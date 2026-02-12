<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $db = db();

    $servicesToAdd = [
        [
            'nome' => 'Stock di Magazzino all\'Asta',
            'slug' => 'stock-magazzino-all-asta',
            'categoria' => 'altro',
            'descrizione_breve' => 'Grandi lotti di merce, attrezzature e rimanenze di magazzino da fallimenti aziendali.',
            'ordine' => 11
        ],
        [
            'nome' => 'Beni di Lusso all\'Asta',
            'slug' => 'beni-lusso-all-asta',
            'categoria' => 'altro',
            'descrizione_breve' => 'Orologi, gioielli, opere d\'arte e beni preziosi provenienti da procedure esecutive.',
            'ordine' => 12
        ],
        [
            'nome' => 'Crediti e Portafogli all\'Asta',
            'slug' => 'crediti-portafogli-all-asta',
            'categoria' => 'altro',
            'descrizione_breve' => 'Acquisto di crediti deteriorati (NPL) e portafogli di crediti commerciali e finanziari.',
            'ordine' => 13
        ]
    ];

    foreach ($servicesToAdd as $serviceData) {
        // Check if it exists
        $existing = $db->fetchOne("SELECT * FROM servizi WHERE slug = ?", [$serviceData['slug']]);

        if ($existing) {
            echo "Service already exists: " . $existing['nome'] . "\n";
            // Optional: Update name if needed
            $db->update('servizi', ['nome' => $serviceData['nome']], 'id = :id', ['id' => $existing['id']]);
        } else {
            echo "Inserting: " . $serviceData['nome'] . "\n";

            $data = [
                'nome' => $serviceData['nome'],
                'slug' => $serviceData['slug'],
                'categoria' => $serviceData['categoria'],
                'descrizione_breve' => $serviceData['descrizione_breve'],
                'attivo' => 1,
                'ordine' => $serviceData['ordine']
            ];

            $db->insert('servizi', $data);
            echo "Success!\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
