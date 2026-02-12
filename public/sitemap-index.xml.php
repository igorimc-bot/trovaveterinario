<?php
/**
 * XML Sitemap Index Generator
 * Lists all sub-sitemaps
 */

if (!defined('APP_URL')) {
    require_once __DIR__ . '/../includes/config.php';
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../includes/functions.php';
}

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Main Sitemap
echo '<sitemap>';
echo '<loc>' . APP_URL . '/sitemap-main.xml</loc>';
echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
echo '</sitemap>';

// Service Sitemaps
$servizi = getAllServizi();
foreach ($servizi as $servizio) {
    echo '<sitemap>';
    echo '<loc>' . APP_URL . '/sitemap-service-' . $servizio['slug'] . '.xml</loc>';
    echo '<lastmod>' . date('Y-m-d') . '</lastmod>';
    echo '</sitemap>';
}

echo '</sitemapindex>';
