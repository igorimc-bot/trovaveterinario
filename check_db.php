<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

try {
    $pdo = db()->getConnection();
    $tableName = 'servizi';
    echo "Columns in $tableName:\n";
    $columns = $pdo->query("DESCRIBE $tableName")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "{$column['Field']} ({$column['Type']})\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
