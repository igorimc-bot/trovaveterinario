<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "<h1>Database Diagnostics</h1>";

try {
    $pdo = db()->getConnection();

    // 1. Check Table Columns
    echo "<h2>1. Table Structure (servizi)</h2>";
    $columns = $pdo->query("SHOW COLUMNS FROM servizi")->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' cellspacing='0' cellpadding='5'><tr><th>Field</th><th>Type</th><th>Default</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Default']}</td></tr>";
    }
    echo "</table>";

    // 2. Check Raw Data
    echo "<h2>2. Raw Data (First 5 Rows)</h2>";
    $rows = $pdo->query("SELECT id, nome, slug, categoria FROM servizi LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>" . print_r($rows, true) . "</pre>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>