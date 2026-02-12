<?php
// Simulate sitemap requests
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "TESTING SITEMAP INDEX:\n";
$_SERVER['REQUEST_URI'] = '/sitemap.xml';
// Mock execution context for script inclusion
ob_start();
require __DIR__ . '/../public/sitemap-index.xml.php';
$output = ob_get_clean();
echo "Length: " . strlen($output) . " bytes\n";
echo "Contains <sitemapindex>: " . (strpos($output, '<sitemapindex>') !== false ? "YES" : "NO") . "\n";
echo "Contains sitemap-main.xml: " . (strpos($output, 'sitemap-main.xml') !== false ? "YES" : "NO") . "\n";

echo "\nTESTING SERVICE SITEMAP (auto-all-asta):\n";
$serviceSlug = 'auto-all-asta';
ob_start();
require __DIR__ . '/../public/sitemap-service.xml.php';
$output = ob_get_clean();
echo "Length: " . strlen($output) . " bytes\n";
echo "Contains <urlset>: " . (strpos($output, '<urlset>') !== false ? "YES" : "NO") . "\n";
echo "Contains /regioni/: " . (strpos($output, '/regioni/') !== false ? "YES" : "NO") . "\n";
