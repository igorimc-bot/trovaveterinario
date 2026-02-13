<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "<h1>Updating Hero Content</h1>";

try {
    $pdo = db()->getConnection();

    $content = [
        'hero_title' => 'Trova Veterinario',
        'hero_subtitle' => 'Il punto di riferimento per la salute del tuo animale. Cerca veterinari, cliniche e pronto soccorso H24 in tutta Italia.',
        'global_why_us_text' => 'Siamo il network leader per la salute animale. I nostri punti di forza:',
        'global_benefits_html' => '<li><strong>Ricerca Rapida</strong>: Trova subito il veterinario più vicino</li><li><strong>Specialisti Verificati</strong>: Solo professionisti certificati</li><li><strong>Pronto Soccorso H24</strong>: Assistenza immediata per le emergenze</li><li><strong>Tutte le Specie</strong>: Cani, gatti, esotici e animali da fattoria</li>'
    ];

    foreach ($content as $key => $value) {
        // Try update
        $update = $pdo->prepare("UPDATE contenuti SET test = :value WHERE slug = :key");
        // Wait, table structure from dump: `slug`, `titolo`, `testo`...
        // Let's check structure.
        // Dump says: INSERT INTO `contenuti` VALUES (1,'hero_title','...','testo'...)
        // Columns likely: id, slug, titolo, testo, ...
        // I will attempt to update `titolo` (which holds the text in the dump example for hero_title) or `testo` (which is 'testo' string in dump?)
        // Dump: (1,'hero_title','Aste Giudiziarie...','testo',...)
        // So `titolo` column holds the content for `hero_title`.
        // And for `hero_subtitle`? (2,'hero_subtitle','Consulenza...','testo',...)
        // So `titolo` holds the content.

        $update = $pdo->prepare("UPDATE contenuti SET titolo = :value WHERE slug = :key");
        $update->execute([':value' => $value, ':key' => $key]);

        if ($update->rowCount() > 0) {
            echo "<p>Updated '$key'</p>";
        } else {
            // Check if exists
            $check = $pdo->prepare("SELECT id FROM contenuti WHERE slug = :key");
            $check->execute([':key' => $key]);
            if ($check->rowCount() == 0) {
                $insert = $pdo->prepare("INSERT INTO contenuti (slug, titolo, testo) VALUES (:key, :value, 'testo')");
                $insert->execute([':key' => $key, ':value' => $value]);
                echo "<p>Inserted '$key'</p>";
            } else {
                echo "<p>'$key' already up to date or mismatch</p>";
            }
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>