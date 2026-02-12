<?php
/**
 * Main XML Sitemap Generator
 * Contains home, static pages, and generic location pages
 */

if (!defined('APP_URL')) {
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../includes/functions.php';
}

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
    'termini-condizioni' => ['freq' => 'yearly', 'priority' => '0.3'],
    'servizi' => ['freq' => 'weekly', 'priority' => '0.8'],
    'regioni' => ['freq' => 'monthly', 'priority' => '0.7'],
    'province' => ['freq' => 'monthly', 'priority' => '0.7'],
    'comuni' => ['freq' => 'monthly', 'priority' => '0.7']
];

foreach ($staticPages as $page => $meta) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/' . $page . '</loc>';
    echo '<changefreq>' . $meta['freq'] . '</changefreq>';
    echo '<priority>' . $meta['priority'] . '</priority>';
    echo '</url>';
}

// 1. Regioni Pages (/regioni/lombardia)
$regioni = getAllRegioni();
foreach ($regioni as $regione) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/regioni/' . $regione['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

// 2. Province Pages (/province/milano)
$province = db()->fetchAll('SELECT slug FROM province WHERE attiva = 1');
foreach ($province as $provincia) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/province/' . $provincia['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.7</priority>';
    echo '</url>';
}

// 3. Comuni Pages (/comuni/milano) -> LIMIT to 2000 for main sitemap to allow reasonable content
// The rest will be covered by service-specific sitemaps coverage.
// Ideally, this file should list generic common pages.
$comuni = db()->fetchAll('SELECT slug FROM comuni WHERE attivo = 1 LIMIT 2000');
foreach ($comuni as $comune) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/comuni/' . $comune['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.6</priority>';
    echo '</url>';
}

echo '</urlset>';
