<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "=== Fixing Remaining SEO ===\n\n";

$updates = [
    'furgoni-veicoli-commerciali-all-asta' => [
        'title' => "Furgoni e Veicoli Commerciali all'Asta - Occasioni Fallimentari",
        'description' => "Trova furgoni, camion e veicoli commerciali all'asta a prezzi vantaggiosi. Ampia selezione di mezzi da fallimenti e procedure giudiziarie in tutta Italia."
    ],
    'beni-di-lusso-all-asta' => [
        'title' => "Beni di Lusso e Preziosi all'Asta | Aste Giudiziarie 24",
        'description' => "Orologi, gioielli, arte e beni di lusso all'asta. Esclusività accessibile tramite vendite giudiziarie sicure."
    ]
];

foreach ($updates as $slug => $data) {
    db()->update(
        'servizi',
        [
            'meta_title' => $data['title'],
            'meta_description' => $data['description']
        ],
        'slug = :slug',
        ['slug' => $slug]
    );
    echo "Updated SEO for: $slug\n";
}
