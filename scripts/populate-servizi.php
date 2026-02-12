<?php
/**
 * Populate Servizi Script
 * Inserts all services into the database
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== Populating Servizi ===\n\n";

$servizi = [
    [
        'nome' => "Auto all'Asta",
        'categoria' => 'veicoli',
        'descrizione_breve' => 'Acquista auto da aste giudiziarie e fallimentari. Assistenza completa per valutazione e acquisto.',
        'ordine' => 1
    ],
    [
        'nome' => "Moto all'Asta",
        'categoria' => 'veicoli',
        'descrizione_breve' => 'Moto e scooter da aste giudiziarie. Supporto per perizie e documentazione.',
        'ordine' => 2
    ],
    [
        'nome' => "Barche all'Asta",
        'categoria' => 'veicoli',
        'descrizione_breve' => 'Imbarcazioni e natanti da aste fallimentari. Consulenza specializzata.',
        'ordine' => 3
    ],
    [
        'nome' => "Case all'Asta",
        'categoria' => 'immobili',
        'descrizione_breve' => 'Immobili residenziali da aste giudiziarie. Assistenza legale e perizie.',
        'ordine' => 4
    ],
    [
        'nome' => 'Aste Immobiliari',
        'categoria' => 'immobili',
        'descrizione_breve' => 'Immobili commerciali, terreni e fabbricati da aste. Supporto completo.',
        'ordine' => 5
    ],
    [
        'nome' => 'Aste Mobiliari',
        'categoria' => 'altro',
        'descrizione_breve' => 'Beni mobili, macchinari e attrezzature da aste giudiziarie.',
        'ordine' => 6
    ],
    [
        'nome' => 'Aste Giudiziarie',
        'categoria' => 'altro',
        'descrizione_breve' => 'Assistenza completa per tutte le tipologie di aste giudiziarie in Italia.',
        'ordine' => 7
    ],
    [
        'nome' => 'Aste Fallimentari',
        'categoria' => 'altro',
        'descrizione_breve' => 'Opportunità da procedure fallimentari. Consulenza e supporto legale.',
        'ordine' => 8
    ]
];

$inserted = 0;
$skipped = 0;

foreach ($servizi as $servizio) {
    $slug = generateSlug($servizio['nome']);

    // Check if already exists
    $existing = db()->fetchOne('SELECT id FROM servizi WHERE slug = ?', [$slug]);

    if ($existing) {
        echo "⊘ Skipping {$servizio['nome']} (already exists)\n";
        $skipped++;
        continue;
    }

    // Insert servizio
    $data = [
        'nome' => $servizio['nome'],
        'slug' => $slug,
        'categoria' => $servizio['categoria'],
        'descrizione_breve' => $servizio['descrizione_breve'],
        'ordine' => $servizio['ordine'],
        'attivo' => 1,
        'meta_title' => "{$servizio['nome']} in Italia | Aste Giudiziarie 24",
        'meta_description' => $servizio['descrizione_breve']
    ];

    db()->insert('servizi', $data);
    echo "✓ Inserted: {$servizio['nome']}\n";
    $inserted++;
}

echo "\n=== Summary ===\n";
echo "Inserted: {$inserted}\n";
echo "Skipped: {$skipped}\n";
echo "Total: " . count($servizi) . "\n\n";
echo "✓ Servizi population completed!\n\n";
