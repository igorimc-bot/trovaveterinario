<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

try {
    $pdo = db()->getConnection();

    // Add columns if they don't exist
    $columns = [
        'prezzo' => "DECIMAL(10,2) DEFAULT NULL",
        'features' => "TEXT DEFAULT NULL"
    ];

    $existingColumns = [];
    $stmt = $pdo->query("DESCRIBE servizi");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
    }

    foreach ($columns as $name => $definition) {
        if (!in_array($name, $existingColumns)) {
            echo "Adding column $name...\n";
            $pdo->exec("ALTER TABLE servizi ADD COLUMN $name $definition AFTER descrizione_breve");
        } else {
            echo "Column $name already exists.\n";
        }
    }

    echo "Migration completed.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
