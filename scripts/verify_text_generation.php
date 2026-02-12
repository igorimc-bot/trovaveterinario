<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/text_generator.php';

// Mock data
$service = ['slug' => 'auto-all-asta', 'nome' => 'Auto all\'Asta'];
$locations = [
    ['slug' => 'lombardia', 'nome' => 'Lombardia', 'type' => 'regione'],
    ['slug' => 'milano', 'nome' => 'Milano', 'type' => 'provincia'],
    ['slug' => 'roma', 'nome' => 'Roma', 'type' => 'comune']
];

foreach ($locations as $loc) {
    echo "---------------------------------------------------\n";
    echo "TESTING: {$service['nome']} in {$loc['nome']} ({$loc['type']})\n";
    echo "---------------------------------------------------\n";

    $content = generateServiceLocationContent($service, $loc, $loc['type']);

    // Strip tags for easier reading in terminal
    echo strip_tags($content) . "\n\n";
}
