<?php
/**
 * Service XML Sitemap Generator
 * Generates all location combinations for a specific service
 * Expects $serviceSlug to be set by router
 */

if (!defined('APP_URL')) {
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../includes/functions.php';
}

if (!isset($serviceSlug)) {
    http_response_code(404);
    exit;
}

$servizio = getServizioBySlug($serviceSlug);
if (!$servizio) {
    http_response_code(404);
    exit;
}

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// 1. Base Service Page (/servizi/auto-all-asta)
// NOTE: According to our router logic, /auto-all-asta is NOT a valid route for just the service description, 
// the service main page is /servizi/{slug}. But we also used /{slug} for specific landing in previous setups?
// Let's stick to the canonical /servizi/{slug} defined in functions.php and used in sitemap.xml.php
echo '<url>';
echo '<loc>' . APP_URL . '/servizi/' . $servizio['slug'] . '</loc>';
echo '<changefreq>weekly</changefreq>';
echo '<priority>0.9</priority>';
echo '</url>';

// 2. Service + Regione (/{service-slug}/{regione-slug})
$regioni = db()->fetchAll('SELECT slug FROM regioni WHERE attiva = 1');
foreach ($regioni as $regione) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/' . $servizio['slug'] . '/' . $regione['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

// 3. Service + Provincia (/{service-slug}/provincia-{provincia-slug})
// Using "provincia-" prefix as per router logic
$province = db()->fetchAll('SELECT slug FROM province WHERE attiva = 1');
foreach ($province as $provincia) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/' . $servizio['slug'] . '/provincia-' . $provincia['slug'] . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.8</priority>';
    echo '</url>';
}

// 4. Service + Comune (/{service-slug}/{comune-slug})
// This might be huge. If > 40k records, we might need further splitting. 
// For now, let's assume < 50k active comuni/url combinations safe.
// Assuming ~8000 comuni.
$comuni = db()->fetchAll('SELECT slug FROM comuni WHERE attivo = 1');
foreach ($comuni as $comune) {
    echo '<url>';
    echo '<loc>' . APP_URL . '/' . $servizio['slug'] . '/' . $comune['slug'] . '</loc>';
    echo '<changefreq>monthly</changefreq>';
    echo '<priority>0.7</priority>';
    echo '</url>';
}

echo '</urlset>';
