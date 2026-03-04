<?php
require_once __DIR__ . '/../includes/db.php';
$pdo = db()->getConnection();
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables in " . DB_NAME . ":\n";
foreach ($tables as $table) {
    echo "- $table\n";
}

$columns = $pdo->query("DESCRIBE partners")->fetchAll(PDO::FETCH_ASSOC);
echo "\nColumns in partners:\n";
foreach ($columns as $col) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}
?>