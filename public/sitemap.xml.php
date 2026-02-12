<?php
/**
 * XML Sitemap Generator
 * Generates dynamic sitemap for SEO
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Homepage
echo '<url>';
echo '<loc>' . APP_URL . '/</loc>';
echo '<changefreq>daily</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

// Static pages
$staticPages = [
    'pubblicita' => ['freq' => 'monthly', 'priority' => '0.6'],
    'privacy-policy' => ['freq' => 'yearly', 'priority' => '0.3'],
    'cookie-policy' => ['freq' => 'yearly', 'priority' => '0.3'],
    'termini-condizioni' => ['freq' => 'yearly', 'priority' => '0.3']
];

foreach ($staticPages as $page => $meta) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/' . $page . '</loc>';
    echo '<changefreq>' . $meta['freq'] . '</changefreq>';
    echo '<priority>' . $meta['priority'] . '</priority>';
    echo '</url>';
}

// Services
$servizi = getAllServizi();
foreach ($servizi as $servizio) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/servizi/' . $servizio['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.9</priority>';
    echo '</url>';
}

// Regioni
$regioni = getAllRegioni();
foreach ($regioni as $regione) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/regioni/' . $regione['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

// Province (sample - limit to avoid huge sitemap)
$province = db()->fetchAll('SELECT slug FROM province WHERE attiva = 1 LIMIT 100');
foreach ($province as $provincia) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/province/' . $provincia['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.7</priority>';
    echo '</url>';
}

// Comuni (major cities only - limit to avoid huge sitemap)
$comuni = db()->fetchAll('SELECT slug FROM comuni WHERE attivo = 1 LIMIT 200');
foreach ($comuni as $comune) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/comuni/' . $comune['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.6</priority>';
    echo '</url>';
}

echo '</urlset>';
