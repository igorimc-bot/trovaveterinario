<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

echo "<h1>Setup Trovaveterinario Database</h1>";

try {
    $pdo = db()->getConnection();
    echo "<p>Connected to database successfully.</p>";

    // Check if services table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'servizi'");
    if ($stmt->rowCount() > 0) {
        echo "<p>Table 'servizi' found.</p>";

        // Read the SQL file
        $sqlFile = __DIR__ . '/setup_trovaveterinario.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $statements = array_filter(array_map('trim', explode(';', $sql)));

            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                        echo "<p style='color:green'>Executed: " . htmlspecialchars(substr($statement, 0, 50)) . "...</p>";
                    } catch (PDOException $e) {
                        echo "<p style='color:red'>Error executing statement: " . htmlspecialchars($e->getMessage()) . "</p>";
                    }
                }
            }
            echo "<p><strong>Migration completed!</strong></p>";
        } else {
            echo "<p style='color:red'>SQL file 'setup_trovaveterinario.sql' not found.</p>";
        }

    } else {
        echo "<p style='color:red'>Table 'servizi' NOT found. Please import the original dump first.</p>";
    }

} catch (Exception $e) {
    echo "<p style='color:red'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
