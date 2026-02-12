<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "=== Fixing SEO by ID ===\n\n";

$updates = [
    9 => [
        'title' => "Furgoni e Veicoli Commerciali all'Asta - Occasioni Fallimentari",
        'description' => "Trova furgoni, camion e veicoli commerciali all'asta a prezzi vantaggiosi. Ampia selezione di mezzi da fallimenti e procedure giudiziarie in tutta Italia."
    ],
    11 => [
        'title' => "Beni di Lusso e Preziosi all'Asta | Aste Giudiziarie 24",
        'description' => "Orologi, gioielli, arte e beni di lusso all'asta. Esclusività accessibile tramite vendite giudiziarie sicure."
    ]
];

foreach ($updates as $id => $data) {
    db()->update(
        'servizi',
        [
            'meta_title' => $data['title'],
            'meta_description' => $data['description']
        ],
        'id = :id',
        ['id' => $id]
    );
    echo "Updated SEO for ID: $id\n";
}
