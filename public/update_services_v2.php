<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "<h1>Updating Services for Trovaveterinario</h1>";

try {
    $pdo = db()->getConnection();

    // 1. Add 'categoria' column if it doesn't exist
    $columns = $pdo->query("SHOW COLUMNS FROM servizi LIKE 'categoria'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE servizi ADD COLUMN categoria VARCHAR(50) DEFAULT 'generale' AFTER slug");
        echo "<p>Added 'categoria' column.</p>";
    } else {
        echo "<p>'categoria' column already exists.</p>";
    }

    // 2. Truncate table (Disable FK checks to allow truncate)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE servizi");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "<p>Table 'servizi' truncated.</p>";

    // 3. Prepare Insert Statement
    $stmt = $pdo->prepare("INSERT INTO servizi (nome, slug, categoria, descrizione_breve, prezzo, features, attivo) VALUES (:nome, :slug, :categoria, :descrizione_breve, :prezzo, :features, 1)");

    // Data - Animals (Category: animali)
    $animali = [
        ['Cani', 'veterinario-cani', 'animali', 'Cura e assistenza completa per il tuo cane.'],
        ['Gatti', 'veterinario-gatti', 'animali', 'Specialisti in medicina felina.'],
        ['Animali Esotici', 'veterinario-esotici', 'animali', 'Cura per furetti, conigli e roditori.'],
        ['Rettili', 'veterinario-rettili', 'animali', 'Esperti in tartarughe, iguane e serpenti.'],
        ['Uccelli', 'veterinario-uccelli', 'animali', 'Ornitologia e cura dei volatili.'],
        ['Cavalli e Equini', 'veterinario-cavalli', 'animali', 'Medicina sportiva e chirurgia equina.'],
        ['Animali da Fattoria', 'veterinario-fattoria', 'animali', 'Bovini, ovini, suini e animali da reddito.'],
        ['Pesci e Acquariofilia', 'veterinario-pesci', 'animali', 'Esperti in malattie dei pesci.'],
        ['Piccoli Mammiferi', 'veterinario-piccoli-mammiferi', 'animali', 'Cura per criceti, cavie e cincillà.'],
        ['Anfibi', 'veterinario-anfibi', 'animali', 'Specialisti in rane e salamandre.']
    ];

    // Data - Interventions (Category: interventi)
    $interventi = [
        ['Chirurgia Veterinaria', 'chirurgia-veterinaria', 'interventi', 'Interventi tessuti molli e ortopedia.'],
        ['Pronto Soccorso H24', 'pronto-soccorso', 'interventi', 'Assistenza immediata per urgenze.'],
        ['Vaccinazioni e Microchip', 'vaccinazioni', 'interventi', 'Profilassi e identificazione elettronica.'],
        ['Visite a Domicilio', 'visite-domicilio', 'interventi', 'Il veterinario direttamente a casa tua.'],
        ['Dermatologia', 'dermatologia-veterinaria', 'interventi', 'Cura delle malattie della pelle e allergie.'],
        ['Odontostomatologia', 'dentista-veterinario', 'interventi', 'Pulizia denti e chirurgia orale.'],
        ['Oftalmologia', 'oculista-veterinario', 'interventi', 'Visite specialistiche per gli occhi.'],
        ['Cardiologia', 'cardiologo-veterinario', 'interventi', 'Ecocardio e visite cardiologiche.'],
        ['Oncologia', 'oncologo-veterinario', 'interventi', 'Terapie oncologiche e chemioterapia.'],
        ['Diagnostica per Immagini', 'ecografia-radiologia', 'interventi', 'Ecografie, RX e TAC.']
    ];

    $all_services = array_merge($animali, $interventi);

    foreach ($all_services as $service) {
        $stmt->execute([
            ':nome' => $service[0],
            ':slug' => $service[1],
            ':categoria' => $service[2],
            ':descrizione_breve' => $service[3],
            ':prezzo' => null,
            ':features' => null
        ]);
        echo "<p>Inserted: {$service[0]} ({$service[2]})</p>";
    }

    echo "<h3>Success! Added " . count($all_services) . " services.</h3>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Error: " . htmlspecialchars($e->getMessage()) . "</h3>";
}
?>