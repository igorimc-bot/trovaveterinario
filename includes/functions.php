<?php
/**
 * Utility Functions
 * Common helper functions used throughout the application
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Sanitize input data
 */
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email address
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Italian format)
 */
function isValidPhone($phone)
{
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    return preg_match('/^(\+39)?[0-9]{9,10}$/', $phone);
}

/**
 * Generate CSRF token
 */
function generateCsrfToken()
{
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Verify CSRF token
 */
function verifyCsrfToken($token)
{
    return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Generate slug from string
 */
function generateSlug($string)
{
    $string = strtolower(trim($string));
    $string = preg_replace('/[àáâãäå]/', 'a', $string);
    $string = preg_replace('/[èéêë]/', 'e', $string);
    $string = preg_replace('/[ìíîï]/', 'i', $string);
    $string = preg_replace('/[òóôõö]/', 'o', $string);
    $string = preg_replace('/[ùúûü]/', 'u', $string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}

/**
 * Redirect to URL
 */
function redirect($url, $statusCode = 302)
{
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Get current URL
 */
function getCurrentUrl()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Require login
 */
function requireLogin()
{
    if (!isLoggedIn()) {
        redirect('/admin/login.php');
    }
}

/**
 * Get logged in user
 */
function getLoggedInUser()
{
    if (!isLoggedIn()) {
        return null;
    }

    $userId = $_SESSION['user_id'];
    return db()->fetchOne('SELECT * FROM users WHERE id = ?', [$userId]);
}

/**
 * Hash password
 */
function hashPassword($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Send email using PHPMailer
 */
function sendEmail($to, $subject, $body, $altBody = '')
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $altBody ?: strip_tags($body);

        $mail->send();
        return true;

    } catch (Exception $e) {
        logError('Email sending failed: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * Log error to file
 */
function logError($message, $file = 'error.log')
{
    $logFile = LOGS_PATH . '/' . $file;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";

    if (!is_dir(LOGS_PATH)) {
        mkdir(LOGS_PATH, 0755, true);
    }

    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

/**
 * Get setting from database
 */
function getSetting($key, $default = null)
{
    $result = db()->fetchOne('SELECT valore FROM impostazioni WHERE chiave = ?', [$key]);
    return $result ? $result['valore'] : $default;
}

/**
 * Set setting in database
 */
function setSetting($key, $value)
{
    $existing = db()->fetchOne('SELECT chiave FROM impostazioni WHERE chiave = ?', [$key]);

    if ($existing) {
        db()->update('impostazioni', ['valore' => $value], 'chiave = :chiave', ['chiave' => $key]);
    } else {
        db()->insert('impostazioni', ['chiave' => $key, 'valore' => $value]);
    }
}

/**
 * Get content from database
 */
function getContent($key, $default = '')
{
    $result = db()->fetchOne('SELECT valore FROM contenuti WHERE chiave = ?', [$key]);
    return $result ? $result['valore'] : $default;
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'd/m/Y H:i')
{
    if (empty($date))
        return '';
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    return date($format, $timestamp);
}

/**
 * Get client IP address
 */
function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }
}

/**
 * Get user agent
 */
function getUserAgent()
{
    return $_SERVER['HTTP_USER_AGENT'] ?? '';
}

/**
 * Get UTM parameters
 */
function getUtmParams()
{
    return [
        'utm_source' => $_GET['utm_source'] ?? '',
        'utm_medium' => $_GET['utm_medium'] ?? '',
        'utm_campaign' => $_GET['utm_campaign'] ?? '',
        'utm_term' => $_GET['utm_term'] ?? '',
        'utm_content' => $_GET['utm_content'] ?? ''
    ];
}

/**
 * Verify reCAPTCHA
 */
function verifyRecaptcha($token)
{
    if (empty(RECAPTCHA_SECRET_KEY)) {
        return true; // Skip if not configured
    }

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $token,
        'remoteip' => getClientIp()
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $result = json_decode($response, true);

    return isset($result['success']) && $result['success'] === true && $result['score'] >= 0.5;
}

/**
 * Get regione by slug
 */
function getRegioneBySlug($slug)
{
    return db()->fetchOne('SELECT * FROM regioni WHERE slug = ? AND attiva = 1', [$slug]);
}

/**
 * Get provincia by slug
 */
function getProvinciaBySlug($slug)
{
    return db()->fetchOne('SELECT * FROM province WHERE slug = ? AND attiva = 1', [$slug]);
}

/**
 * Get comune by slug
 */
function getComuneBySlug($slug, $provinciaId = null)
{
    if ($provinciaId) {
        return db()->fetchOne('SELECT * FROM comuni WHERE slug = ? AND provincia_id = ? AND attivo = 1', [$slug, $provinciaId]);
    }
    return db()->fetchOne('SELECT * FROM comuni WHERE slug = ? AND attivo = 1', [$slug]);
}

/**
 * Get servizio by slug
 */
function getServizioBySlug($slug)
{
    return db()->fetchOne('SELECT * FROM servizi WHERE slug = ? AND attivo = 1', [$slug]);
}

/**
 * Get all active servizi
 */
function getAllServizi()
{
    return db()->fetchAll('SELECT * FROM servizi WHERE attivo = 1 ORDER BY ordine ASC, nome ASC');
}

/**
 * Get all active regioni
 */
function getAllRegioni()
{
    return db()->fetchAll('SELECT * FROM regioni WHERE attiva = 1 ORDER BY nome ASC');
}

/**
 * Get province by regione
 */
function getProvinceByRegione($regioneId)
{
    return db()->fetchAll('SELECT * FROM province WHERE regione_id = ? AND attiva = 1 ORDER BY nome ASC', [$regioneId]);
}

/**
 * Get comuni by provincia
 */
function getComuniByProvincia($provinciaId)
{
    return db()->fetchAll('SELECT * FROM comuni WHERE provincia_id = ? AND attivo = 1 ORDER BY nome ASC', [$provinciaId]);
}

/**
 * Generate meta title
 */
function generateMetaTitle($servizio, $location, $locationType = 'regione')
{
    $templates = [
        'regione' => "{$servizio} in {$location} | Aste Giudiziarie 24",
        'provincia' => "{$servizio} in Provincia di {$location} | Aste Giudiziarie 24",
        'comune' => "{$servizio} a {$location} | Aste Giudiziarie 24"
    ];

    return $templates[$locationType] ?? "{$servizio} | Aste Giudiziarie 24";
}

/**
 * Generate meta description
 */
function generateMetaDescription($servizio, $location, $locationType = 'regione')
{
    $servizioLower = strtolower($servizio);

    $templates = [
        'regione' => "Cerchi {$servizioLower} in {$location}? Assistenza completa per aste giudiziarie e fallimentari. Consulenza gratuita, esperti del settore. Contattaci ora!",
        'provincia' => "Scopri le migliori {$servizioLower} in provincia di {$location}. Supporto legale, perizie, finanziamenti. Richiedi consulenza gratuita!",
        'comune' => "{$servizio} a {$location}: assistenza professionale per aste giudiziarie e fallimentari. Preventivo gratuito, esperti locali. Chiamaci!"
    ];

    return $templates[$locationType] ?? "Assistenza completa per {$servizioLower}. Consulenza gratuita.";
}

/**
 * Render breadcrumbs
 */
function renderBreadcrumbs($items)
{
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';

    foreach ($items as $index => $item) {
        $isLast = $index === count($items) - 1;

        if ($isLast) {
            $html .= '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($item['name']) . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item"><a href="' . htmlspecialchars($item['url']) . '">' . htmlspecialchars($item['name']) . '</a></li>';
        }
    }

    $html .= '</ol></nav>';
    return $html;
}

/**
 * JSON response
 */
function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Generate Service Schema Markup
 */
function generateServiceSchema($service, $location = null, $locationType = null)
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service['nome'],
        'description' => $service['descrizione_breve'] ?? 'Assistenza completa per ' . strtolower($service['nome']),
        'provider' => [
            '@type' => 'Organization',
            'name' => 'Aste Giudiziarie 24',
            'url' => APP_URL
        ]
    ];

    if ($location && $locationType) {
        $areaServedType = match ($locationType) {
            'regione' => 'State',
            'provincia' => 'AdministrativeArea',
            'comune' => 'City',
            default => 'Place'
        };

        $schema['areaServed'] = [
            '@type' => $areaServedType,
            'name' => $location['nome']
        ];
    } else {
        $schema['areaServed'] = [
            '@type' => 'Country',
            'name' => 'Italia'
        ];
    }

    return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Generate Breadcrumb Schema Markup
 */
function generateBreadcrumbSchema($items)
{
    $itemListElement = [];

    foreach ($items as $index => $item) {
        $itemListElement[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url']
        ];
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemListElement
    ];

    return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Generate LocalBusiness Schema Markup
 */
function generateLocalBusinessSchema($comune, $provincia, $regione)
{
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ProfessionalService',
        'name' => 'Aste Giudiziarie 24 - ' . $comune['nome'],
        'description' => 'Assistenza completa per aste giudiziarie e fallimentari a ' . $comune['nome'],
        'url' => APP_URL . '/comuni/' . $comune['slug'],
        'areaServed' => [
            '@type' => 'City',
            'name' => $comune['nome'],
            'containedIn' => [
                '@type' => 'AdministrativeArea',
                'name' => $provincia['nome'],
                'containedIn' => [
                    '@type' => 'State',
                    'name' => $regione['nome'],
                    'containedIn' => [
                        '@type' => 'Country',
                        'name' => 'Italia'
                    ]
                ]
            ]
        ],
        'serviceType' => [
            'Consulenza Aste Giudiziarie',
            'Assistenza Aste Fallimentari',
            'Perizie Immobiliari',
            'Assistenza Legale Aste'
        ]
    ];

    return json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Get provincia with regione data
 */
function getProvinciaWithRegione($slug)
{
    $sql = '
        SELECT p.*, r.nome as regione_nome, r.slug as regione_slug
        FROM province p
        INNER JOIN regioni r ON p.regione_id = r.id
        WHERE p.slug = ? AND p.attiva = 1
    ';
    return db()->fetchOne($sql, [$slug]);
}

/**
 * Get comune with provincia and regione data
 */
function getComuneWithDetails($slug)
{
    $sql = '
        SELECT c.*, 
               p.nome as provincia_nome, p.slug as provincia_slug, p.sigla as provincia_sigla, p.regione_id,
               r.nome as regione_nome, r.slug as regione_slug
        FROM comuni c
        INNER JOIN province p ON c.provincia_id = p.id
        INNER JOIN regioni r ON p.regione_id = r.id
        WHERE c.slug = ? AND c.attivo = 1
        LIMIT 1
    ';
    return db()->fetchOne($sql, [$slug]);
}
