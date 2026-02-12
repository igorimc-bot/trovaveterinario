<?php
require_once __DIR__ . '/../includes/db.php';
$pdo = db()->getConnection();
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
echo implode("\n", $tables);
?>