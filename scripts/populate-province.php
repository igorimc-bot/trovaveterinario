<?php
/**
 * Populate Province Script
 * Inserts all 107 Italian provinces into the database
 * 
 * Note: This is a sample with major provinces. 
 * For production, import complete dataset from ISTAT or other source.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== Populating Province ===\n\n";

// Sample of major provinces (add all 107 in production)
$province = [
    // Lombardia
    ['nome' => 'Milano', 'sigla' => 'MI', 'regione' => 'Lombardia'],
    ['nome' => 'Bergamo', 'sigla' => 'BG', 'regione' => 'Lombardia'],
    ['nome' => 'Brescia', 'sigla' => 'BS', 'regione' => 'Lombardia'],
    ['nome' => 'Como', 'sigla' => 'CO', 'regione' => 'Lombardia'],
    ['nome' => 'Cremona', 'sigla' => 'CR', 'regione' => 'Lombardia'],
    ['nome' => 'Lecco', 'sigla' => 'LC', 'regione' => 'Lombardia'],
    ['nome' => 'Lodi', 'sigla' => 'LO', 'regione' => 'Lombardia'],
    ['nome' => 'Mantova', 'sigla' => 'MN', 'regione' => 'Lombardia'],
    ['nome' => 'Monza e della Brianza', 'sigla' => 'MB', 'regione' => 'Lombardia'],
    ['nome' => 'Pavia', 'sigla' => 'PV', 'regione' => 'Lombardia'],
    ['nome' => 'Sondrio', 'sigla' => 'SO', 'regione' => 'Lombardia'],
    ['nome' => 'Varese', 'sigla' => 'VA', 'regione' => 'Lombardia'],

    // Lazio
    ['nome' => 'Roma', 'sigla' => 'RM', 'regione' => 'Lazio'],
    ['nome' => 'Frosinone', 'sigla' => 'FR', 'regione' => 'Lazio'],
    ['nome' => 'Latina', 'sigla' => 'LT', 'regione' => 'Lazio'],
    ['nome' => 'Rieti', 'sigla' => 'RI', 'regione' => 'Lazio'],
    ['nome' => 'Viterbo', 'sigla' => 'VT', 'regione' => 'Lazio'],

    // Veneto
    ['nome' => 'Venezia', 'sigla' => 'VE', 'regione' => 'Veneto'],
    ['nome' => 'Verona', 'sigla' => 'VR', 'regione' => 'Veneto'],
    ['nome' => 'Padova', 'sigla' => 'PD', 'regione' => 'Veneto'],
    ['nome' => 'Treviso', 'sigla' => 'TV', 'regione' => 'Veneto'],
    ['nome' => 'Vicenza', 'sigla' => 'VI', 'regione' => 'Veneto'],
    ['nome' => 'Belluno', 'sigla' => 'BL', 'regione' => 'Veneto'],
    ['nome' => 'Rovigo', 'sigla' => 'RO', 'regione' => 'Veneto'],

    // Piemonte
    ['nome' => 'Torino', 'sigla' => 'TO', 'regione' => 'Piemonte'],
    ['nome' => 'Alessandria', 'sigla' => 'AL', 'regione' => 'Piemonte'],
    ['nome' => 'Asti', 'sigla' => 'AT', 'regione' => 'Piemonte'],
    ['nome' => 'Biella', 'sigla' => 'BI', 'regione' => 'Piemonte'],
    ['nome' => 'Cuneo', 'sigla' => 'CN', 'regione' => 'Piemonte'],
    ['nome' => 'Novara', 'sigla' => 'NO', 'regione' => 'Piemonte'],
    ['nome' => 'Verbano-Cusio-Ossola', 'sigla' => 'VB', 'regione' => 'Piemonte'],
    ['nome' => 'Vercelli', 'sigla' => 'VC', 'regione' => 'Piemonte'],

    // Emilia-Romagna
    ['nome' => 'Bologna', 'sigla' => 'BO', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Ferrara', 'sigla' => 'FE', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Forlì-Cesena', 'sigla' => 'FC', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Modena', 'sigla' => 'MO', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Parma', 'sigla' => 'PR', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Piacenza', 'sigla' => 'PC', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Ravenna', 'sigla' => 'RA', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Reggio Emilia', 'sigla' => 'RE', 'regione' => 'Emilia-Romagna'],
    ['nome' => 'Rimini', 'sigla' => 'RN', 'regione' => 'Emilia-Romagna'],

    // Toscana
    ['nome' => 'Firenze', 'sigla' => 'FI', 'regione' => 'Toscana'],
    ['nome' => 'Arezzo', 'sigla' => 'AR', 'regione' => 'Toscana'],
    ['nome' => 'Grosseto', 'sigla' => 'GR', 'regione' => 'Toscana'],
    ['nome' => 'Livorno', 'sigla' => 'LI', 'regione' => 'Toscana'],
    ['nome' => 'Lucca', 'sigla' => 'LU', 'regione' => 'Toscana'],
    ['nome' => 'Massa-Carrara', 'sigla' => 'MS', 'regione' => 'Toscana'],
    ['nome' => 'Pisa', 'sigla' => 'PI', 'regione' => 'Toscana'],
    ['nome' => 'Pistoia', 'sigla' => 'PT', 'regione' => 'Toscana'],
    ['nome' => 'Prato', 'sigla' => 'PO', 'regione' => 'Toscana'],
    ['nome' => 'Siena', 'sigla' => 'SI', 'regione' => 'Toscana'],

    // Campania
    ['nome' => 'Napoli', 'sigla' => 'NA', 'regione' => 'Campania'],
    ['nome' => 'Avellino', 'sigla' => 'AV', 'regione' => 'Campania'],
    ['nome' => 'Benevento', 'sigla' => 'BN', 'regione' => 'Campania'],
    ['nome' => 'Caserta', 'sigla' => 'CE', 'regione' => 'Campania'],
    ['nome' => 'Salerno', 'sigla' => 'SA', 'regione' => 'Campania'],

    // Sicilia
    ['nome' => 'Palermo', 'sigla' => 'PA', 'regione' => 'Sicilia'],
    ['nome' => 'Catania', 'sigla' => 'CT', 'regione' => 'Sicilia'],
    ['nome' => 'Messina', 'sigla' => 'ME', 'regione' => 'Sicilia'],
    ['nome' => 'Agrigento', 'sigla' => 'AG', 'regione' => 'Sicilia'],
    ['nome' => 'Caltanissetta', 'sigla' => 'CL', 'regione' => 'Sicilia'],
    ['nome' => 'Enna', 'sigla' => 'EN', 'regione' => 'Sicilia'],
    ['nome' => 'Ragusa', 'sigla' => 'RG', 'regione' => 'Sicilia'],
    ['nome' => 'Siracusa', 'sigla' => 'SR', 'regione' => 'Sicilia'],
    ['nome' => 'Trapani', 'sigla' => 'TP', 'regione' => 'Sicilia'],

    // Puglia
    ['nome' => 'Bari', 'sigla' => 'BA', 'regione' => 'Puglia'],
    ['nome' => 'Barletta-Andria-Trani', 'sigla' => 'BT', 'regione' => 'Puglia'],
    ['nome' => 'Brindisi', 'sigla' => 'BR', 'regione' => 'Puglia'],
    ['nome' => 'Foggia', 'sigla' => 'FG', 'regione' => 'Puglia'],
    ['nome' => 'Lecce', 'sigla' => 'LE', 'regione' => 'Puglia'],
    ['nome' => 'Taranto', 'sigla' => 'TA', 'regione' => 'Puglia'],

    // Add more provinces here for complete dataset...
];

$inserted = 0;
$skipped = 0;
$errors = 0;

foreach ($province as $prov) {
    try {
        // Get regione ID
        $regione = db()->fetchOne('SELECT id FROM regioni WHERE nome = ?', [$prov['regione']]);

        if (!$regione) {
            echo "✗ Error: Regione '{$prov['regione']}' not found for {$prov['nome']}\n";
            $errors++;
            continue;
        }

        $slug = generateSlug($prov['nome']);

        // Check if already exists
        $existing = db()->fetchOne('SELECT id FROM province WHERE slug = ?', [$slug]);

        if ($existing) {
            echo "⊘ Skipping {$prov['nome']} (already exists)\n";
            $skipped++;
            continue;
        }

        // Insert provincia
        $data = [
            'nome' => $prov['nome'],
            'slug' => $slug,
            'sigla' => $prov['sigla'],
            'regione_id' => $regione['id'],
            'attiva' => 1,
            'meta_title' => "Aste Giudiziarie in Provincia di {$prov['nome']} | Aste Giudiziarie 24",
            'meta_description' => "Scopri le migliori opportunità di aste giudiziarie e fallimentari in provincia di {$prov['nome']}. Assistenza completa."
        ];

        db()->insert('province', $data);
        echo "✓ Inserted: {$prov['nome']} ({$prov['sigla']})\n";
        $inserted++;

    } catch (Exception $e) {
        echo "✗ Error inserting {$prov['nome']}: " . $e->getMessage() . "\n";
        $errors++;
    }
}

echo "\n=== Summary ===\n";
echo "Inserted: {$inserted}\n";
echo "Skipped: {$skipped}\n";
echo "Errors: {$errors}\n";
echo "Total processed: " . count($province) . "\n\n";

if ($inserted > 0) {
    echo "✓ Province population completed!\n\n";
    echo "Note: This script contains a sample of major provinces.\n";
    echo "For complete dataset (107 provinces), import from ISTAT or update this script.\n\n";
}
