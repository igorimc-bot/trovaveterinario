<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "<h1>Updating Site Settings</h1>";

try {
    $pdo = db()->getConnection();

    // Check if settings table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'settings'");
    if ($stmt->rowCount() > 0) {
        $settings = [
            'site_name' => 'Trova Veterinario',
            'site_tagline' => 'Il portale N.1 per la salute del tuo animale',
            'site_description' => 'Trova il veterinario più vicino a te. Migliaia di specialisti, cliniche e pronto soccorso h24 per cani, gatti, esotici e animali da fattoria.',
            'contact_email' => 'info@trovaveterinario.it'
        ];

        foreach ($settings as $key => $value) {
            // Try update
            $update = $pdo->prepare("UPDATE settings SET value = :value WHERE setting_key = :key");
            $update->execute([':value' => $value, ':key' => $key]);

            if ($update->rowCount() > 0) {
                echo "<p>Updated '$key' to '$value'</p>";
            } else {
                // Try insert if not exists
                $insert = $pdo->prepare("INSERT INTO settings (setting_key, value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value = :value");
                $insert->execute([':key' => $key, ':value' => $value]);
                echo "<p>Inserted/Verified '$key'</p>";
            }
        }
    } else {
        echo "<p>Settings table does not exist. Configuring defaults in code.</p>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>