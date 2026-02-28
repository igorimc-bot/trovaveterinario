<?php
/**
 * API: Search Comuni
 * Returns a list of comuni with their province and region for autocomplete
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

try {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            c.id AS comune_id, 
            c.nome AS comune_nome, 
            p.id AS provincia_id, 
            p.nome AS provincia_nome, 
            p.sigla AS provincia_sigla, 
            r.id AS regione_id, 
            r.nome AS regione_nome 
        FROM comuni c 
        JOIN province p ON c.provincia_id = p.id 
        JOIN regioni r ON p.regione_id = r.id 
        WHERE c.nome LIKE ? AND c.attivo = 1 
        ORDER BY c.nome ASC 
        LIMIT 15
    ");
    $stmt->execute(['%' . $q . '%']);
    $comuni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = [];
    foreach ($comuni as $c) {
        $results[] = [
            'id' => $c['comune_id'],
            'text' => $c['comune_nome'] . ' (' . $c['provincia_sigla'] . ')',
            'provincia_id' => $c['provincia_id'],
            'regione_id' => $c['regione_id']
        ];
    }

    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode(['error' => 'Database error']);
}
