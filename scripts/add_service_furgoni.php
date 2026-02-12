<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    $db = db();

    // Check existing services
    echo "Servizi esistenti:\n";
    $stmt = $db->query("SELECT nome, slug FROM servizi");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "- {$row['nome']} ({$row['slug']})\n";
    }
    echo "\n";

    // New service data
    $nome = 'Furgoni e Veicoli Commerciali';
    $slug = 'furgoni-veicoli-commerciali-all-asta';
    $categoria = 'veicoli';
    $descrizione = 'Trova furgoni, camion e veicoli commerciali all\'asta a prezzi vantaggiosi. Ampia scelta di mezzi da fallimenti e procedure giudiziarie.';

    // Check if already exists
    $check = $db->prepare("SELECT id FROM servizi WHERE slug = ?");
    $check->execute([$slug]);

    if ($check->fetch()) {
        echo "Il servizio '{$nome}' esiste già.\n";
    } else {
        $stmt = $db->prepare("INSERT INTO servizi (nome, slug, categoria, descrizione_breve, attivo, ordine) VALUES (?, ?, ?, ?, 1, 10)");
        $stmt->execute([$nome, $slug, $categoria, $descrizione]);
        echo "Servizio '{$nome}' aggiunto con successo!\n";
    }

} catch (Exception $e) {
    echo "Errore: " . $e->getMessage() . "\n";
}
