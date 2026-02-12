<?php
/**
 * Update Service Images Script
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "=== Updating Service Images ===\n\n";

$images = [
    'auto-allasta' => '/assets/img/services/auto-all-asta.png',
    'moto-allasta' => '/assets/img/services/moto-all-asta.png',
    'barche-allasta' => '/assets/img/services/barche-all-asta.png',
    'case-allasta' => '/assets/img/services/case-all-asta.png',
    'aste-immobiliari' => '/assets/img/services/aste-immobiliari.png',
    'aste-mobiliari' => '/assets/img/services/aste-mobiliari.png',
    'aste-giudiziarie' => '/assets/img/services/aste-giudiziarie.png',
    'aste-fallimentari' => '/assets/img/services/aste-fallimentari.png',
    'stock-di-magazzino-allasta' => '/assets/img/services/stock-magazzino.png',
    'crediti-portafogli-all-asta' => '/assets/img/services/crediti-portafogli.png',
    'locali-commerciali' => '/assets/img/services/locali-commerciali.png',
    'terreni-agricoli-edificabili' => '/assets/img/services/terreni-agricoli.png',
    'furgoni-veicoli-commerciali-all-asta' => '/assets/img/services/furgoni-veicoli.png',
    'beni-di-lusso-all-asta' => '/assets/img/services/beni-lusso.png'
];

foreach ($images as $slug => $path) {
    db()->update('servizi', ['immagine' => $path], 'slug = :slug', ['slug' => $slug]);
    echo "Updated image for: {$slug}\n";
}

echo "\n✓ Update completed!\n";
