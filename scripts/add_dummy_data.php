<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/config.php';

// Check if running from CLI
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.");
}

$pdo = db()->getConnection();

echo "Starting data seeding...\n";

// 1. Get IDs for foreign keys
$servizio = $pdo->query("SELECT id FROM servizi LIMIT 1")->fetchColumn();
$regione = $pdo->query("SELECT id FROM regioni WHERE nome = 'Lombardia'")->fetchColumn();
$provincia = $pdo->query("SELECT id FROM province WHERE nome = 'Milano'")->fetchColumn();
$comune = $pdo->query("SELECT id FROM comuni WHERE nome = 'Milano'")->fetchColumn();

if (!$servizio || !$regione) {
    die("Error: Missing base data (services or regions). Please populate them first.\n");
}

// 2. Insert Dummy Partner
$partnerData = [
    'nome_azienda' => 'Studio Legale Bianchi & Associati',
    'tipologia' => 'avvocato',
    'referente' => 'Avv. Luigi Bianchi',
    'telefono' => '0212345678',
    'email' => 'studio@bianchi.example.com',
    'whatsapp' => '3339876543',
    'regioni_competenza' => json_encode([$regione]), // Lombardia
    'province_competenza' => json_encode([$provincia]), // Milano
    'servizi_offerti' => json_encode([$servizio]),
    'stato' => 'attivo',
    'note' => 'Partner specializzato in aste immobiliari residenziali.'
];

$stmt = $pdo->prepare("INSERT INTO partners (nome_azienda, tipologia, referente, telefono, email, whatsapp, regioni_competenza, province_competenza, servizi_offerti, stato, note) VALUES (:nome_azienda, :tipologia, :referente, :telefono, :email, :whatsapp, :regioni_competenza, :province_competenza, :servizi_offerti, :stato, :note)");
$stmt->execute($partnerData);
$partnerId = $pdo->lastInsertId();

echo "Partner created: Studio Legale Bianchi (ID: $partnerId)\n";

// 3. Insert Dummy Lead
$leadData = [
    'nome' => 'Mario',
    'cognome' => 'Rossi',
    'telefono' => '3331234567',
    'email' => 'mario.rossi@example.com',
    'indirizzo' => 'Via Roma',
    'civico' => '10',
    'comune_id' => $comune,
    'provincia_id' => $provincia,
    'regione_id' => $regione,
    'cap' => '20100',
    'servizio_id' => $servizio,
    'tipo_richiesta' => json_encode(['consulenza', 'valutazione']),
    'descrizione' => 'Salve, vorrei ricevere maggiori informazioni sulle aste di appartamenti in zona centro a Milano. Budget massimo 200k.',
    'preferenza_contatto' => 'telefono',
    'orario_preferito' => 'Pomeriggio dopo le 17',
    'stato' => 'nuovo',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
];

$columns = implode(', ', array_keys($leadData));
$placeholders = ':' . implode(', :', array_keys($leadData));
$stmt = $pdo->prepare("INSERT INTO leads ($columns) VALUES ($placeholders)");
$stmt->execute($leadData);
$leadId = $pdo->lastInsertId();

echo "Lead created: Mario Rossi (ID: $leadId)\n";

echo "Done.\n";
