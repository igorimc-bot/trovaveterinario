<?php
/**
 * Populate Comuni Script
 * Inserts comuni into the database
 * 
 * Note: This is a sample structure for major comuni.
 * For production, import complete dataset (~8000 comuni) from ISTAT.
 * 
 * Dataset source: https://www.istat.it/it/archivio/6789
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== Populating Comuni ===\n\n";

// Sample comuni for major cities (add complete dataset in production)
$comuni = [
    // Milano province
    ['nome' => 'Milano', 'provincia' => 'Milano', 'cap' => '20100'],
    ['nome' => 'Monza', 'provincia' => 'Monza e della Brianza', 'cap' => '20900'],
    ['nome' => 'Sesto San Giovanni', 'provincia' => 'Milano', 'cap' => '20099'],
    ['nome' => 'Cinisello Balsamo', 'provincia' => 'Milano', 'cap' => '20092'],
    ['nome' => 'Rho', 'provincia' => 'Milano', 'cap' => '20017'],

    // Roma province
    ['nome' => 'Roma', 'provincia' => 'Roma', 'cap' => '00100'],
    ['nome' => 'Fiumicino', 'provincia' => 'Roma', 'cap' => '00054'],
    ['nome' => 'Guidonia Montecelio', 'provincia' => 'Roma', 'cap' => '00012'],

    // Torino province
    ['nome' => 'Torino', 'provincia' => 'Torino', 'cap' => '10100'],
    ['nome' => 'Moncalieri', 'provincia' => 'Torino', 'cap' => '10024'],
    ['nome' => 'Collegno', 'provincia' => 'Torino', 'cap' => '10093'],

    // Napoli province
    ['nome' => 'Napoli', 'provincia' => 'Napoli', 'cap' => '80100'],
    ['nome' => 'Giugliano in Campania', 'provincia' => 'Napoli', 'cap' => '80014'],
    ['nome' => 'Torre del Greco', 'provincia' => 'Napoli', 'cap' => '80059'],

    // Palermo province
    ['nome' => 'Palermo', 'provincia' => 'Palermo', 'cap' => '90100'],
    ['nome' => 'Bagheria', 'provincia' => 'Palermo', 'cap' => '90011'],

    // Genova province (Liguria)
    ['nome' => 'Genova', 'provincia' => 'Genova', 'cap' => '16100'],

    // Bologna province
    ['nome' => 'Bologna', 'provincia' => 'Bologna', 'cap' => '40100'],
    ['nome' => 'Imola', 'provincia' => 'Bologna', 'cap' => '40026'],

    // Firenze province
    ['nome' => 'Firenze', 'provincia' => 'Firenze', 'cap' => '50100'],
    ['nome' => 'Prato', 'provincia' => 'Prato', 'cap' => '59100'],

    // Bari province
    ['nome' => 'Bari', 'provincia' => 'Bari', 'cap' => '70100'],
    ['nome' => 'Altamura', 'provincia' => 'Bari', 'cap' => '70022'],

    // Catania province
    ['nome' => 'Catania', 'provincia' => 'Catania', 'cap' => '95100'],

    // Venezia province
    ['nome' => 'Venezia', 'provincia' => 'Venezia', 'cap' => '30100'],
    ['nome' => 'Mestre', 'provincia' => 'Venezia', 'cap' => '30172'],

    // Verona province
    ['nome' => 'Verona', 'provincia' => 'Verona', 'cap' => '37100'],

    // Padova province
    ['nome' => 'Padova', 'provincia' => 'Padova', 'cap' => '35100'],

    // Trieste province (Friuli-Venezia Giulia)
    ['nome' => 'Trieste', 'provincia' => 'Trieste', 'cap' => '34100'],

    // Brescia province
    ['nome' => 'Brescia', 'provincia' => 'Brescia', 'cap' => '25100'],

    // Taranto province
    ['nome' => 'Taranto', 'provincia' => 'Taranto', 'cap' => '74100'],

    // Reggio Calabria province
    ['nome' => 'Reggio Calabria', 'provincia' => 'Reggio Calabria', 'cap' => '89100'],

    // Modena province
    ['nome' => 'Modena', 'provincia' => 'Modena', 'cap' => '41100'],

    // Parma province
    ['nome' => 'Parma', 'provincia' => 'Parma', 'cap' => '43100'],

    // Perugia province (Umbria)
    ['nome' => 'Perugia', 'provincia' => 'Perugia', 'cap' => '06100'],

    // Ravenna province
    ['nome' => 'Ravenna', 'provincia' => 'Ravenna', 'cap' => '48100'],

    // Livorno province
    ['nome' => 'Livorno', 'provincia' => 'Livorno', 'cap' => '57100'],

    // Cagliari province (Sardegna)
    ['nome' => 'Cagliari', 'provincia' => 'Cagliari', 'cap' => '09100'],

    // Foggia province
    ['nome' => 'Foggia', 'provincia' => 'Foggia', 'cap' => '71100'],

    // Salerno province
    ['nome' => 'Salerno', 'provincia' => 'Salerno', 'cap' => '84100'],

    // Ferrara province
    ['nome' => 'Ferrara', 'provincia' => 'Ferrara', 'cap' => '44100'],

    // Sassari province (Sardegna)
    ['nome' => 'Sassari', 'provincia' => 'Sassari', 'cap' => '07100'],

    // Latina province
    ['nome' => 'Latina', 'provincia' => 'Latina', 'cap' => '04100'],

    // Giugliano province
    ['nome' => 'Giugliano', 'provincia' => 'Napoli', 'cap' => '80014'],

    // Add more comuni here for complete dataset...
];

$inserted = 0;
$skipped = 0;
$errors = 0;

foreach ($comuni as $com) {
    try {
        // Get provincia ID
        $provincia = db()->fetchOne('SELECT id FROM province WHERE nome = ?', [$com['provincia']]);

        if (!$provincia) {
            echo "✗ Error: Provincia '{$com['provincia']}' not found for {$com['nome']}\n";
            $errors++;
            continue;
        }

        $slug = generateSlug($com['nome']);

        // Check if already exists in this provincia
        $existing = db()->fetchOne(
            'SELECT id FROM comuni WHERE slug = ? AND provincia_id = ?',
            [$slug, $provincia['id']]
        );

        if ($existing) {
            echo "⊘ Skipping {$com['nome']} (already exists)\n";
            $skipped++;
            continue;
        }

        // Insert comune
        $data = [
            'nome' => $com['nome'],
            'slug' => $slug,
            'provincia_id' => $provincia['id'],
            'cap' => $com['cap'],
            'attivo' => 1,
            'meta_title' => "Aste Giudiziarie a {$com['nome']} | Aste Giudiziarie 24",
            'meta_description' => "Trova le migliori opportunità di aste giudiziarie e fallimentari a {$com['nome']}. Assistenza professionale locale."
        ];

        db()->insert('comuni', $data);
        echo "✓ Inserted: {$com['nome']} ({$com['cap']})\n";
        $inserted++;

    } catch (Exception $e) {
        echo "✗ Error inserting {$com['nome']}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Summary ===\n";
echo "Inserted: {$inserted}\n";
echo "Skipped: {$skipped}\n";
echo "Errors: {$errors}\n";
echo "Total processed: " . count($comuni) . "\n\n";

if ($inserted > 0) {
    echo "✓ Comuni population completed!\n\n";
    echo "Note: This script contains a sample of major comuni.\n";
    echo "For complete dataset (~8000 comuni), import from ISTAT:\n";
    echo "https://www.istat.it/it/archivio/6789\n\n";
}
