<?php
/**
 * DEBUG INSTALL SCRIPT
 * Upload to public/debug_install.php and visit to check environment
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug Installazione</h1>";

// 1. Check PHP Version
echo "<h2>1. Versione PHP</h2>";
echo "Versione corrente: " . phpversion() . "<br>";
if (version_compare(phpversion(), '7.4.0', '<')) {
    echo "<strong style='color:red'>ERRORE: Serve PHP 7.4 o superiore!</strong>";
} else {
    echo "<strong style='color:green'>OK</strong>";
}

// 2. Check File Paths
echo "<h2>2. Percorsi File</h2>";
$rootDir = dirname(__DIR__);
echo "Root Path (stimato): " . $rootDir . "<br>";

$filesToCheck = [
    '/router.php',
    '/includes/config.php',
    '/vendor/autoload.php',
    '/.env'
];

foreach ($filesToCheck as $file) {
    if (file_exists($rootDir . $file)) {
        echo "$file: <strong style='color:green'>TROVATO</strong><br>";
    } else {
        echo "$file: <strong style='color:red'>MANCANTE</strong> (Questo e' probabilmente il problema)<br>";
    }
}

// 3. Test Dotenv
echo "<h2>3. Test Caricamento .env</h2>";
if (file_exists($rootDir . '/vendor/autoload.php')) {
    require_once $rootDir . '/vendor/autoload.php';
    echo "Autoloader caricato.<br>";

    if (class_exists('Dotenv\Dotenv')) {
        echo "Libreria Dotenv trovata.<br>";
        try {
            $dotenv = Dotenv\Dotenv::createImmutable($rootDir);
            $dotenv->load();
            echo "File .env caricato correttamente.<br>";
            echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'NON TROVATO') . "<br>";
        } catch (Exception $e) {
            echo "<strong style='color:red'>Errore caricamento .env: " . $e->getMessage() . "</strong><br>";
        }
    } else {
        echo "<strong style='color:red'>Classe Dotenv non trovata. Vendor non caricato bene?</strong><br>";
    }
} else {
    echo "Saltato test .env perche' manca vendor.<br>";
}

// 4. Test DB Connection
echo "<h2>4. Test Connessione DB</h2>";
if (isset($_ENV['DB_HOST'])) {
    try {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4";
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
        echo "<strong style='color:green'>Connessione al database RIUSCITA!</strong>";
    } catch (PDOException $e) {
        echo "<strong style='color:red'>Errore connessione DB: " . $e->getMessage() . "</strong>";
    }
} else {
    echo "Dati DB mancanti.";
}
