<?php
// Last updated: 2026-02-13 17:21
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "<h1>Updating Services Images</h1>";

try {
    $pdo = db()->getConnection();

    // Map correct slug -> filename
    // Filenames have spaces, slugs have dashes
    // Map correct slug -> filename
    // Filenames have spaces, slugs have dashes
    $imageMap = [
        'veterinario-cani' => 'cani.webp',
        'veterinario-gatti' => 'gatti.webp',
        'veterinario-esotici' => 'animali esotici.webp', // manual map
        'veterinario-rettili' => 'rettili.webp',
        'veterinario-uccelli' => 'uccelli.webp',
        'veterinario-cavalli' => 'cavalli.webp',
        'veterinario-fattoria' => 'animali da fattoria.webp', // manual map
        'veterinario-pesci' => 'pesci e acquariofilia.webp', // manual map
        'veterinario-piccoli-mammiferi' => 'piccoli mammiferi.webp', // manual map
        'veterinario-anfibi' => 'anfibi.webp',

        'chirurgia-veterinaria' => 'chirurgia veterinaria.webp',
        'pronto-soccorso' => 'pronto soccorso h24.webp', // manual map
        'vaccinazioni' => 'vaccinazioni e microchip.webp', // manual map
        'visite-domicilio' => 'visite a domicilio.webp', // manual map
        'dermatologia-veterinaria' => 'dermatologia.webp',
        'dentista-veterinario' => 'odontostomatologia.webp', // manual map
        'oculista-veterinario' => 'oftalmologia.webp', // manual map
        'cardiologo-veterinario' => 'cardiologia.webp',
        'oncologo-veterinario' => 'oncologia.webp',
        'ecografia-radiologia' => 'diagnostica per immagini.webp' // manual map
    ];

    $updateCount = 0;
    $errorCount = 0;

    foreach ($imageMap as $slug => $filename) {
        // Construct web path
        $webPath = 'assets/img/services/' . $filename;

        try {
            $stmt = $pdo->prepare("UPDATE servizi SET immagine = :immagine WHERE slug = :slug");
            $result = $stmt->execute([
                ':immagine' => $webPath,
                ':slug' => $slug
            ]);

            if ($result) {
                // Check if row was actually updated
                if ($stmt->rowCount() > 0) {
                    $updateCount++;
                    echo "<p style='color: green;'>✓ Updated: <strong>$slug</strong> -> <a href='/$webPath' target='_blank'>$filename</a></p>";
                } else {
                    echo "<p style='color: gray;'>- No change: <strong>$slug</strong> (already set or not found)</p>";
                }
            } else {
                $errorCount++;
                echo "<p style='color: orange;'>⚠ Failed to update: <strong>$slug</strong></p>";
            }
        } catch (Exception $e) {
            $errorCount++;
            echo "<p style='color: red;'>✗ Error updating $slug: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    echo "<hr>";
    echo "<h2>Summary</h2>";
    echo "<p><strong>Updated:</strong> $updateCount services</p>";
    echo "<p><strong>Errors:</strong> $errorCount</p>";

    if ($updateCount > 0) {
        echo "<p style='color: green; font-weight: bold;'>✓ Service images have been successfully mapped!</p>";
        echo "<p>Please verify on the homepage or service pages.</p>";
    }

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>