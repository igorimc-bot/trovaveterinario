<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $pdo = db()->getConnection();
    echo "Starting migration...\n";

    // 1. Create click_tracking table
    $sql_click = "CREATE TABLE IF NOT EXISTS `click_tracking` (
        `id` int NOT NULL AUTO_INCREMENT,
        `place_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `place_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `type` enum('telefono','sito') COLLATE utf8mb4_unicode_ci NOT NULL,
        `page_url` text COLLATE utf8mb4_unicode_ci,
        `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `user_agent` text COLLATE utf8mb4_unicode_ci,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `idx_type` (`type`),
        KEY `idx_created` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql_click);
    echo "Table 'click_tracking' checked/created.\n";

    // Add new columns to click_tracking if they don't exist
    $newColumns = [
        'servizio' => "VARCHAR(100) DEFAULT NULL",
        'regione' => "VARCHAR(100) DEFAULT NULL",
        'provincia' => "VARCHAR(100) DEFAULT NULL",
        'comune' => "VARCHAR(100) DEFAULT NULL",
        'website_url' => "TEXT DEFAULT NULL",
        'google_maps_url' => "TEXT DEFAULT NULL"
    ];

    $existingColumns = [];
    $stmt = $pdo->query("DESCRIBE click_tracking");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingColumns[] = $row['Field'];
    }

    foreach ($newColumns as $name => $definition) {
        if (!in_array($name, $existingColumns)) {
            echo "Adding column $name to click_tracking...\n";
            $pdo->exec("ALTER TABLE click_tracking ADD COLUMN $name $definition");
        }
    }

    // 2. Create partner_province table (needed by servizio-location-template.php)
    $sql_pp = "CREATE TABLE IF NOT EXISTS `partner_province` (
        `partner_id` int NOT NULL,
        `provincia_id` int NOT NULL,
        PRIMARY KEY (`partner_id`, `provincia_id`),
        KEY `idx_provincia` (`provincia_id`),
        CONSTRAINT `fk_pp_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_pp_provincia` FOREIGN KEY (`provincia_id`) REFERENCES `province` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql_pp);
    echo "Table 'partner_province' checked/created.\n";

    // 3. Create partner_regioni table (needed by servizio-location-template.php)
    $sql_pr = "CREATE TABLE IF NOT EXISTS `partner_regioni` (
        `partner_id` int NOT NULL,
        `regione_id` int NOT NULL,
        PRIMARY KEY (`partner_id`, `regione_id`),
        KEY `idx_regione` (`regione_id`),
        CONSTRAINT `fk_pr_partner` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
        CONSTRAINT `fk_pr_regione` FOREIGN KEY (`regione_id`) REFERENCES `regioni` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql_pr);
    echo "Table 'partner_regioni' checked/created.\n";

    // 4. Create clinic_notes table
    $sql_notes = "CREATE TABLE IF NOT EXISTS `clinic_notes` (
        `id` int NOT NULL AUTO_INCREMENT,
        `place_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
        `user_id` int DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `place_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `status` enum('non_gestito','gestito','contattato','partner') COLLATE utf8mb4_unicode_ci DEFAULT 'non_gestito',
        PRIMARY KEY (`id`),
        KEY `idx_place` (`place_id`),
        CONSTRAINT `fk_notes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $pdo->exec($sql_notes);
    echo "Table 'clinic_notes' checked/created.\n";

    // 5. Add status column to clinic_notes if missing
    $checkColumn = $pdo->query("SHOW COLUMNS FROM `clinic_notes` LIKE 'status'")->fetch();
    if (!$checkColumn) {
        $pdo->exec("ALTER TABLE `clinic_notes` ADD `status` enum('non_gestito','gestito','contattato','partner') COLLATE utf8mb4_unicode_ci DEFAULT 'non_gestito' AFTER `place_name` ");
        echo "Column 'status' added to 'clinic_notes'.\n";
    }

    echo "Migration completed successfully.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
