<?php
/**
 * Populate Regioni Script
 * Inserts all 20 Italian regions into the database
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== Populating Regioni ===\n\n";

$regioni = [
    ['nome' => 'Abruzzo', 'codice_istat' => '13'],
    ['nome' => 'Basilicata', 'codice_istat' => '17'],
    ['nome' => 'Calabria', 'codice_istat' => '18'],
    ['nome' => 'Campania', 'codice_istat' => '15'],
    ['nome' => 'Emilia-Romagna', 'codice_istat' => '08'],
    ['nome' => 'Friuli-Venezia Giulia', 'codice_istat' => '06'],
    ['nome' => 'Lazio', 'codice_istat' => '12'],
    ['nome' => 'Liguria', 'codice_istat' => '07'],
    ['nome' => 'Lombardia', 'codice_istat' => '03'],
    ['nome' => 'Marche', 'codice_istat' => '11'],
    ['nome' => 'Molise', 'codice_istat' => '14'],
    ['nome' => 'Piemonte', 'codice_istat' => '01'],
    ['nome' => 'Puglia', 'codice_istat' => '16'],
    ['nome' => 'Sardegna', 'codice_istat' => '20'],
    ['nome' => 'Sicilia', 'codice_istat' => '19'],
    ['nome' => 'Toscana', 'codice_istat' => '09'],
    ['nome' => 'Trentino-Alto Adige', 'codice_istat' => '04'],
    ['nome' => 'Umbria', 'codice_istat' => '10'],
    ['nome' => "Valle d'Aosta", 'codice_istat' => '02'],
    ['nome' => 'Veneto', 'codice_istat' => '05']
];

$inserted = 0;
$skipped = 0;

foreach ($regioni as $regione) {
    $slug = generateSlug($regione['nome']);

    // Check if already exists
    $existing = db()->fetchOne('SELECT id FROM regioni WHERE slug = ?', [$slug]);

    if ($existing) {
        echo "⊘ Skipping {$regione['nome']} (already exists)\n";
        $skipped++;
        continue;
    }

    // Insert regione
    $data = [
        'nome' => $regione['nome'],
        'slug' => $slug,
        'codice_istat' => $regione['codice_istat'],
        'attiva' => 1,
        'meta_title' => "Aste Giudiziarie {$regione['nome']} | Aste Giudiziarie 24",
        'meta_description' => "Aste giudiziarie e fallimentari in {$regione['nome']}. Assistenza completa, consulenza gratuita. Trova le migliori opportunità."
    ];

    db()->insert('regioni', $data);
    echo "✓ Inserted: {$regione['nome']}\n";
    $inserted++;
}

echo "\n=== Summary ===\n";
echo "Inserted: {$inserted}\n";
echo "Skipped: {$skipped}\n";
echo "Total: " . count($regioni) . "\n\n";
echo "✓ Regioni population completed!\n\n";
