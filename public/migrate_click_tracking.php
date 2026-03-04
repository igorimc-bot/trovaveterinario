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

    echo "Migration completed successfully.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
