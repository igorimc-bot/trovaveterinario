<?php
/**
 * Database Installation Script
 * Creates database tables and inserts initial data
 */

require_once __DIR__ . '/../includes/config.php';

echo "=== Aste Giudiziarie 24 - Database Installation ===\n\n";

// Read SQL file
$sqlFile = __DIR__ . '/../database.sql';

if (!file_exists($sqlFile)) {
    die("Error: database.sql file not found!\n");
}

echo "Reading database.sql...\n";
$sql = file_get_contents($sqlFile);

// Connect to MySQL without selecting database
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Connected to MySQL server.\n";

    // Create database if not exists
    echo "Creating database '" . DB_NAME . "'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");

    echo "Database created/selected.\n\n";

    // Execute SQL file
    echo "Executing SQL statements...\n";
    $pdo->exec($sql);

    echo "\n✓ Database installation completed successfully!\n\n";
    echo "Next steps:\n";
    echo "1. Run: php scripts/populate-regioni.php\n";
    echo "2. Run: php scripts/populate-province.php\n";
    echo "3. Run: php scripts/populate-comuni.php\n";
    echo "4. Run: php scripts/populate-servizi.php\n";
    echo "5. Run: php scripts/create-admin.php\n\n";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}
