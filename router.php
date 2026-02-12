<?php
/**
 * Router
 * Handles URL routing for the application
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Get the requested URL path
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir = dirname($scriptName);

// If the script is in a subdirectory (e.g. /subdir/index.php), remove the subdirectory from the path
if ($scriptDir !== '/' && $scriptDir !== '\\') {
    if (strpos($requestPath, $scriptDir) === 0) {
        $requestPath = substr($requestPath, strlen($scriptDir));
    }
}

// Remove query query string and trim slashes
$url = trim($requestPath, '/');

// Split URL into segments
$segments = array_filter(explode('/', $url));

// Route handling
if (empty($url) || $url === 'index.php') {
    // Homepage
    require PUBLIC_PATH . '/home.php';
    exit;
}

// Static pages
$staticPages = [
    'pubblicita' => 'pubblicita.php',
    'privacy-policy' => 'privacy-policy.php',
    'cookie-policy' => 'cookie-policy.php',
    'termini-condizioni' => 'termini-condizioni.php'
];

if (isset($staticPages[$url])) {
    require PUBLIC_PATH . '/' . $staticPages[$url];
    exit;
}


// Sitemap
if ($url === 'sitemap.xml' || $url === 'sitemap.xml.php') {
    require PUBLIC_PATH . '/sitemap-index.xml.php';
    exit;
}

if ($url === 'sitemap-main.xml') {
    require PUBLIC_PATH . '/sitemap-main.xml.php';
    exit;
}

// Regex for service sitemaps: sitemap-service-{slug}.xml
if (preg_match('/^sitemap-service-(.+)\.xml$/', $url, $matches)) {
    $serviceSlug = $matches[1];
    require PUBLIC_PATH . '/sitemap-service.xml.php';
    exit;
}


// Robots.txt
if ($url === 'robots.txt') {
    require PUBLIC_PATH . '/robots.txt';
    exit;
}

// Services List Page
if ($url === 'servizi') {
    require PUBLIC_PATH . '/servizi.php';
    exit;
}

// Admin routes
if ($segments[0] === 'admin') {
    $adminFile = PUBLIC_PATH . '/admin/' . implode('/', array_slice($segments, 1));

    if (empty($segments[1])) {
        require PUBLIC_PATH . '/admin/index.php';
    } elseif (file_exists($adminFile . '.php')) {
        require $adminFile . '.php';
    } elseif (is_dir($adminFile) && file_exists($adminFile . '/index.php')) {
        require $adminFile . '/index.php';
    } else {
        http_response_code(404);
        require PUBLIC_PATH . '/404.php';
    }
    exit;
}

// API routes
if ($segments[0] === 'api') {
    $apiFile = PUBLIC_PATH . '/api/' . implode('/', array_slice($segments, 1)) . '.php';

    if (file_exists($apiFile)) {
        require $apiFile;
    } else {
        jsonResponse(['error' => 'API endpoint not found'], 404);
    }
    exit;
}

// Servizi routes: /servizi/{slug}
if ($segments[0] === 'servizi' && isset($segments[1])) {
    $servizio = getServizioBySlug($segments[1]);

    if ($servizio) {
        require PUBLIC_PATH . '/servizi/servizio-template.php';
    } else {
        http_response_code(404);
        require PUBLIC_PATH . '/404.php';
    }
    exit;
}

// Regioni routes: /regioni/{slug}
if ($segments[0] === 'regioni' && isset($segments[1])) {
    $regione = getRegioneBySlug($segments[1]);

    if ($regione) {
        require PUBLIC_PATH . '/regioni/regione-template.php';
    } else {
        http_response_code(404);
        require PUBLIC_PATH . '/404.php';
    }
    exit;
}

// Province routes: /province/{slug}
if ($segments[0] === 'province' && isset($segments[1])) {
    $provincia = getProvinciaBySlug($segments[1]);

    if ($provincia) {
        require PUBLIC_PATH . '/province/provincia-template.php';
    } else {
        http_response_code(404);
        require PUBLIC_PATH . '/404.php';
    }
    exit;
}

// Comuni routes: /comuni/{slug}
if ($segments[0] === 'comuni' && isset($segments[1])) {
    $comune = getComuneBySlug($segments[1]);

    if ($comune) {
        require PUBLIC_PATH . '/comuni/comune-template.php';
    } else {
        http_response_code(404);
        require PUBLIC_PATH . '/404.php';
    }
    exit;
}

// Servizio + Location routes
// Pattern: /{servizio-slug}/{location-slug}
// Examples: /auto-all-asta/lombardia, /case-all-asta/milano
if (count($segments) === 2) {
    $servizio = getServizioBySlug($segments[0]);

    if ($servizio) {
        // Try to match location (regione, provincia, or comune)
        $locationSlug = $segments[1];

        // Check if it's a provincia with "provincia-" prefix
        if (strpos($locationSlug, 'provincia-') === 0) {
            $provinciaSlug = str_replace('provincia-', '', $locationSlug);
            $provincia = getProvinciaBySlug($provinciaSlug);

            if ($provincia) {
                $locationType = 'provincia';
                $location = $provincia;
                require PUBLIC_PATH . '/servizio-location-template.php';
                exit;
            }
        }

        // Try regione
        $regione = getRegioneBySlug($locationSlug);
        if ($regione) {
            $locationType = 'regione';
            $location = $regione;
            require PUBLIC_PATH . '/servizio-location-template.php';
            exit;
        }

        // Try provincia
        $provincia = getProvinciaBySlug($locationSlug);
        if ($provincia) {
            $locationType = 'provincia';
            $location = $provincia;
            require PUBLIC_PATH . '/servizio-location-template.php';
            exit;
        }

        // Try comune
        $comune = getComuneBySlug($locationSlug);
        if ($comune) {
            $locationType = 'comune';
            $location = $comune;
            require PUBLIC_PATH . '/servizio-location-template.php';
            exit;
        }
    }
}

// 404 - Not Found
http_response_code(404);
require PUBLIC_PATH . '/404.php';
